<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Display the main catalog page.
     */
    public function index(Request $request): View
    {
        // Получаем активные категории для фильтра
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Начинаем запрос для товаров
        $query = Item::with('category')
            ->where('is_available', true)
            ->orderBy('created_at', 'desc');

        // Фильтрация по категории
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Поиск по названию или описанию
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Фильтрация по цене
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price_per_day', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max) {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        // Пагинация
        $items = $query->paginate(12);

        // Сохраняем параметры для пагинации
        if ($request->has('category')) {
            $items->appends(['category' => $request->category]);
        }
        if ($request->has('search')) {
            $items->appends(['search' => $request->search]);
        }

        return view('catalog.index', compact('items', 'categories'));
    }

    /**
     * Display the home page with categories.
     */
    public function home(): View
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
     * Display a single item page.
     */
    public function show(Item $item): View
    {
        // Проверяем доступность товара
        if (!$item->is_available) {
            abort(404, 'Товар временно недоступен');
        }

        // Получаем похожие товары из той же категории
        $relatedItems = Item::where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->where('is_available', true)
            ->limit(4)
            ->get();

        return view('catalog.show', compact('item', 'relatedItems'));
    }

    /**
     * Display items by category.
     */
    public function category(Category $category)
    {
        // Получаем товары с пагинацией
        $items = $category->items()
            ->where('is_active', true)
            ->paginate(12);

        return view('catalog.category', compact('category', 'items'));
    }
}
