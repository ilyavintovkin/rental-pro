<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_items' => Item::count(),
            'active_items' => Item::where('is_available', true)->where('quantity', '>', 0)->count(),
            'total_categories' => Category::count(),
            'total_users' => User::count(),
        ];

        // Последние бронирования
        $recent_bookings = Booking::with(['user', 'item'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }
    // Категории: список
    public function categories()
    {
        $categories = Category::orderBy('sort_order')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    // Категории: форма создания
    public function createCategory()
    {
        return view('admin.categories.create');
    }

    // Категории: сохранение
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Обработка изображения
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = $path;
        }

        Category::create($data);

        return redirect()->route('admin.categories')
            ->with('success', 'Категория создана успешно!');
    }

    // Категории: форма редактирования
    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Категории: обновление
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Обновление изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = $path;
        }

        $category->update($data);

        return redirect()->route('admin.categories')
            ->with('success', 'Категория обновлена успешно!');
    }

    // Категории: удаление
    public function destroyCategory(Category $category)
    {
        // Проверяем, есть ли товары в категории
        if ($category->items()->count() > 0) {
            return redirect()->route('admin.categories')
                ->with('error', 'Нельзя удалить категорию, в которой есть товары');
        }

        // Удаляем изображение
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Категория удалена успешно!');
    }

    // Товары: список
    public function items()
    {
        $items = Item::with('category')->latest()->paginate(15);
        return view('admin.items.index', compact('items'));
    }

    // Товары: форма создания
    public function createItem()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.items.create', compact('categories'));
    }

    public function storeItem(Request $request)
    {
        // ОТЛАДКА: Что пришло в запросе
        \Log::info('=== STORE ITEM START ===');
        \Log::info('Has file main_image:', ['has' => $request->hasFile('main_image')]);
        \Log::info('All files:', $request->allFiles());

        // 1. ВАЛИДАЦИЯ (полная, как в updateItem)
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:items,sku',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:excellent,good,fair,poor',
            'main_image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
        ]);

        \Log::info('Validation passed:', $validated);

        // 2. Берем данные ИЗ ВАЛИДИРОВАННОГО МАССИВА
        $data = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'description' => $validated['description'],
            'price_per_day' => $validated['price_per_day'],
            'quantity' => $validated['quantity'],
            'condition' => $validated['condition'],
        ];

        // Добавляем опциональные поля
        if (isset($validated['deposit'])) {
            $data['deposit'] = $validated['deposit'];
        }

        // Обработка is_available
        $data['is_available'] = $request->has('is_available') ? true : false;

        // 3. ОБРАБОТКА ФАЙЛОВ (точно как в updateItem)
        if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
            try {
                $path = $request->file('main_image')->store('items', 'public');
                $data['main_image'] = $path;
                \Log::info('Main image saved successfully:', ['path' => $path]);
            } catch (\Exception $e) {
                \Log::error('Failed to save main image:', ['error' => $e->getMessage()]);
            }
        } else {
            \Log::info('No main image file uploaded or file was invalid.');
        }

        // 4. ДОПОЛНИТЕЛЬНЫЕ ИЗОБРАЖЕНИЯ
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                try {
                    $path = $image->store('items', 'public');
                    $imagePaths[] = $path;
                } catch (\Exception $e) {
                    \Log::error('Additional image save error:', ['error' => $e->getMessage()]);
                }
            }
            if (!empty($imagePaths)) {
                $data['images'] = json_encode($imagePaths);
                \Log::info('Additional images saved:', $imagePaths);
            }
        }

        // 5. ХАРАКТЕРИСТИКИ
        if ($request->has('specifications_text') && !empty($request->specifications_text)) {
            $specs = [];
            $lines = explode("\n", $request->specifications_text);
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    if ($key && $value) {
                        $specs[$key] = $value;
                    }
                }
            }
            if (!empty($specs)) {
                $data['specifications'] = json_encode($specs);
            }
        }

        \Log::info('Final data for item:', $data);

        try {
            $item = Item::create($data);
            \Log::info('Item CREATED:', [
                'id' => $item->id,
                'name' => $item->name,
                'main_image' => $item->main_image,
                'images' => $item->images
            ]);

            return redirect()->route('admin.items')
                ->with('success', 'Товар создан! Изображение: ' . ($item->main_image ? 'ДА' : 'НЕТ'));

        } catch (\Exception $e) {
            \Log::error('CREATE ERROR:', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Товары: форма редактирования
    public function editItem(Item $item)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    // Товары: обновление
    public function updateItem(Request $request, Item $item)
    {
        // 1. ОТЛАДКА: Что пришло в запросе
        \Log::info('=== UPDATE ITEM START ===');
        \Log::info('Has main_image file:', ['has' => $request->hasFile('main_image')]);
        \Log::info('All request data:', $request->all());
        \Log::info('Files:', $_FILES ?? []);
        // 2. БАЗОВАЯ ВАЛИДАЦИЯ (без файлов)
        $validated =   $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:items,sku',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:excellent,good,fair,poor',
            'main_image' => 'nullable|image|max:2048', // Валидация присутствует
            'images.*' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['main_image', 'images', 'specifications_text']);

        // ВАЖНО: Явная проверка наличия файла и его валидности
        if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
            try {
                // Используем более надежный метод store() из Laravel[citation:3][citation:5][citation:7]
                // 'items' - подпапка на диске 'public', где будут лежать файлы
                $path = $request->file('main_image')->store('items', 'public');
                $data['main_image'] = $path; // В БД сохранится путь вида 'items/randomfilename.jpg'
                \Log::info('Main image saved successfully:', ['path' => $path]);
            } catch (\Exception $e) {
                \Log::error('Failed to save main image:', ['error' => $e->getMessage()]);
                // Можно вернуть ошибку пользователю или сохранить товар без фото
                // return back()->withInput()->withErrors(['main_image' => 'Не удалось сохранить изображение.']);
            }
        } else {
            \Log::info('No main image file uploaded or file was invalid.');
        }

        // 4. ОБНОВЛЯЕМ ДРУГИЕ ПОЛЯ
        $item->fill($validated);

        // 5. ХАРАКТЕРИСТИКИ
        if ($request->has('specifications_text')) {
            $specs = [];
            $lines = explode("\n", $request->specifications_text);
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    if ($key && $value) {
                        $specs[$key] = $value;
                    }
                }
            }
            $item->specifications = !empty($specs) ? json_encode($specs) : null;
        }

        // 6. СОХРАНЕНИЕ
        $saved = $item->save();

        \Log::info('Save result:', ['success' => $saved, 'item_id' => $item->id]);
        \Log::info('New main_image value:', ['main_image' => $item->main_image]);
        \Log::info('=== UPDATE ITEM END ===');

        return redirect()->route('admin.items')
            ->with('success', 'Товар обновлен!')
            ->with('debug_info', 'Файл: ' . ($request->hasFile('main_image') ? 'Да' : 'Нет'));
    }

    /**
     * Удаление товара
     */
    public function destroyItem(Item $item)
    {
        // Проверка прав (опционально)
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('admin.items')
                ->with('error', 'Недостаточно прав для удаления');
        }

        // Удаляем товар (связи удалятся каскадно из-за foreign key constraint)
        $item->delete();

        return redirect()->route('admin.items')
            ->with('success', 'Товар успешно удален!');
    }

    /**
     * Главная страница с категориями
     */
    public function home()
    {
        // Получаем активные категории с количеством товаров
        $categories = Category::where('is_active', true)
            ->withCount('items')
            ->orderBy('sort_order')
            ->limit(9)
            ->get();

        // Получаем популярные товары (последние добавленные)
        $popularItems = Item::where('is_available', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'popularItems'));
    }

    /**
     * Display all bookings for admin.
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'item.category'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Get booking details for modal.
     */
    public function bookingDetails(Booking $booking)
    {
        $booking->load(['user', 'item.category']);

        $html = view('admin.bookings.partials.details', compact('booking'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Update booking status.
     */
    public function updateBooking(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,cancelled'
        ]);

        $oldStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Статус бронирования обновлен');
    }
}
