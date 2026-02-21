@extends('layouts.app')

@section('title', $category->name . ' - RentalPro')

@section('content')
    <div class="mb-6">
        <nav class="flex text-sm mb-4">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">Главная</a>
            <span class="mx-2">/</span>
            <span class="text-gray-400">{{ $category->name }}</span>
        </nav>

        <!-- Заголовок категории -->
        <div class="flex items-center mb-8">
            <div class="w-4 h-12 rounded-full mr-4" style="background-color: {{ $category->color ?? '#3b82f6' }}"></div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-gray-600 mt-2">{{ $category->description }}</p>
                @endif
            </div>
        </div>

        <!-- Информация о количестве -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex justify-between items-center">
                <div class="text-gray-600">
                    Найдено товаров: <span class="font-semibold">{{ $items->total() }}</span>
                </div>
                <!-- Здесь можно добавить сортировку или фильтры -->
            </div>
        </div>
    </div>

    <!-- Сетка товаров -->
    @if($items->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($items as $item)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition group">
                    <!-- Изображение товара -->
                    <div class="aspect-square overflow-hidden relative">
                        @if($item->main_image)
                            <img src="{{ str_starts_with($category->image, 'http') ? $category->image : Storage::url($category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-r from-gray-50 to-gray-100 flex items-center justify-center">
                                <i class="fas fa-box text-4xl text-gray-300"></i>
                            </div>
                        @endif

                        <!-- Бейдж доступности -->
                        @if(!$item->is_available_for_booking)
                            <div class="absolute top-3 right-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Нет в наличии
                            </div>
                        @elseif($item->available_quantity <= 3)
                            <div class="absolute top-3 right-3 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Осталось {{ $item->available_quantity }} шт.
                            </div>
                        @endif
                    </div>

                    <!-- Информация о товаре -->
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-800 group-hover:text-indigo-600 transition line-clamp-1">
                                {{ $item->name }}
                            </h3>
                            <span class="text-xs px-2 py-1 rounded" style="background-color: {{ $category->color ?? '#3b82f6' }}20; color: {{ $category->color ?? '#3b82f6' }}">
                                {{ $category->name }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit($item->description, 80) }}
                        </p>

                        <div class="flex items-center text-sm text-gray-500 mb-3 space-x-3">
                            <div class="flex items-center">
                                <i class="fas fa-box mr-1 text-xs"></i>
                                <span>
                                    @if($item->available_quantity > 0)
                                        {{ $item->available_quantity }} из {{ $item->quantity }} шт.
                                    @else
                                        0 из {{ $item->quantity }} шт.
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-1 text-xs"></i>
                                <span>
                                    @if($item->condition == 'excellent')
                                        Отличное
                                    @elseif($item->condition == 'good')
                                        Хорошее
                                    @elseif($item->condition == 'fair')
                                        Удовлетворительное
                                    @else
                                        Плохое
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-lg font-bold text-indigo-600">
                                    {{ number_format($item->price_per_day, 0, ',', ' ') }} ₽
                                </div>
                                <div class="text-xs text-gray-500">в день</div>
                            </div>

                            @if($item->is_available_for_booking)
                                <a href="{{ route('catalog.show', $item) }}"
                                   class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition font-medium">
                                    Подробнее
                                </a>
                            @else
                                <button disabled
                                        class="px-4 py-2 bg-gray-300 text-gray-500 text-sm rounded-lg cursor-not-allowed font-medium">
                                    Нет в наличии
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @else
        <!-- Сообщение, если товаров нет -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">В этой категории пока нет товаров</h3>
            <p class="text-gray-600 mb-6">Товары будут добавлены в ближайшее время</p>
            <a href="{{ route('home') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-medium">
                Вернуться на главную
            </a>
        </div>
    @endif
@endsection
