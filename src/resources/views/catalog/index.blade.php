@extends('layouts.app')

@section('title', 'Каталог инвентаря')

@push('styles')
    <style>
        .filter-active {
            background-color: #4f46e5;
            color: white;
        }
        .price-slider .noUi-connect {
            background-color: #4f46e5;
        }
    </style>
@endpush
@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Каталог инвентаря</h1>
        <p class="text-gray-600">Выберите инвентарь для активного отдыха и спорта</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Боковая панель фильтров -->
        <aside class="lg:w-1/4">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4">Фильтры</h3>

                <!-- Категории -->
                <div class="mb-6">
                    <div class="font-medium text-gray-700 mb-3">Категории</div>
                    <div class="space-y-2">
                        <a href="{{ route('catalog.index') }}"
                           class="block px-3 py-2 rounded-lg hover:bg-gray-100 transition {{ !request('category') || request('category') == 'all' ? 'bg-indigo-50 text-indigo-600' : '' }}">
                            Все категории
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('catalog.index', ['category' => $category->slug]) }}"
                               class="block px-3 py-2 rounded-lg hover:bg-gray-100 transition {{ request('category') == $category->slug ? 'bg-indigo-50 text-indigo-600' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Цена -->
                <div class="mb-6">
                    <div class="font-medium text-gray-700 mb-3">Цена за день</div>
                    <form method="GET" action="{{ route('catalog.index') }}" id="priceFilterForm">
                        <div class="flex space-x-2 mb-3">
                            <input type="number"
                                   name="price_min"
                                   placeholder="От"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                   value="{{ request('price_min') }}">
                            <input type="number"
                                   name="price_max"
                                   placeholder="До"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                   value="{{ request('price_max') }}">
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700 transition">
                            Применить
                        </button>
                    </form>
                </div>

                <!-- Сброс фильтров -->
                @if(request()->anyFilled(['category', 'search', 'price_min', 'price_max']))
                    <a href="{{ route('catalog.index') }}" class="block text-center text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-times mr-2"></i>Сбросить фильтры
                    </a>
                @endif
            </div>

            <!-- Баннер -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                <div class="text-2xl font-bold mb-2">Акция!</div>
                <p class="mb-4">При первой аренде скидка 15%</p>
                <a href="#" class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-medium inline-block hover:bg-gray-100 transition">
                    Узнать подробнее
                </a>
            </div>
        </aside>

        <!-- Основной контент -->
        <main class="lg:w-3/4">
            <!-- Информация о фильтрах -->
            @if(request()->anyFilled(['category', 'search']))
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            @if(request('search'))
                                <span class="text-gray-600">Результаты поиска: <span class="font-semibold">{{ request('search') }}</span></span>
                            @endif
                            @if(request('category') && request('category') !== 'all')
                                <span class="ml-4 text-gray-600">Категория: <span class="font-semibold">{{ $categories->firstWhere('slug', request('category'))->name ?? '' }}</span></span>
                            @endif
                        </div>
                        <span class="text-gray-600">{{ $items->total() }} товаров</span>
                    </div>
                </div>
            @endif

            <!-- Сетка товаров -->
            @if($items->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($items as $item)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden card-hover">
                            <div class="relative">
                                <!-- Бейдж категории -->
                                <div class="absolute top-3 left-3 bg-indigo-600 text-white text-xs font-medium px-3 py-1 rounded-full">
                                    {{ $item->category->name }}
                                </div>

                                <!-- Заглушка для изображения -->
                                <div class="h-48 bg-gradient-to-r from-gray-50 to-gray-100 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $item->main_image_url }}"
                                         alt="{{ $item->name }}"
                                         class="w-full h-full object-cover hover:scale-105 transition duration-300"
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjFmNWY5Ii8+PHRleHQgeD0iNTAiIHk9IjUwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXNpemU9IjI0IiBmaWxsPSIjOGRiMWY1Ij7wn5SlPC90ZXh0Pjwvc3ZnPg=='">
                                </div>
                            </div>

                            <div class="p-5">
                                <!-- Название и SKU -->
                                <div class="mb-2">
                                    <h3 class="font-semibold text-lg text-gray-800">{{ $item->name }}</h3>
                                    <div class="text-gray-500 text-sm">{{ $item->sku }}</div>
                                </div>

                                <!-- Описание -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit($item->description, 100) }}
                                </p>

                                <!-- Характеристики -->
                                <div class="mb-4">
                                    <div class="flex items-center text-gray-600 text-sm mb-1">
                                        <i class="fas fa-tag mr-2 text-gray-400"></i>
                                        <span>{{ $item->condition == 'excellent' ? 'Отличное' : ($item->condition == 'good' ? 'Хорошее' : 'Удовлетворительное') }} состояние</span>
                                    </div>
                                    <div class="flex items-center text-gray-600 text-sm">
                                        <i class="fas fa-box mr-2 text-gray-400"></i>
                                        <span>В наличии: {{ $item->quantity }} шт.</span>
                                    </div>
                                </div>

                                <!-- Цена и кнопка -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-2xl font-bold text-indigo-600">{{ number_format($item->price_per_day, 0, ',', ' ') }} ₽</div>
                                        <div class="text-gray-500 text-sm">в день</div>
                                    </div>
                                    <a href="{{ route('catalog.show', $item) }}"
                                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                                        Подробнее
                                    </a>
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
                <!-- Пустой результат -->
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Товары не найдены</h3>
                    <p class="text-gray-600 mb-6">Попробуйте изменить параметры поиска или выбрать другую категорию</p>
                    <a href="{{ route('catalog.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-medium">
                        Сбросить фильтры
                    </a>
                </div>
            @endif
        </main>
    </div>
@endsection
