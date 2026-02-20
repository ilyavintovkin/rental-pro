@extends('layouts.app')

@section('title', 'RentalPro - Прокат спортивного инвентаря')

@section('content')

    <!-- Баннер категорий -->
    <div class="relative w-full mb-12 rounded-2xl overflow-hidden shadow-xl" style="margin-bottom: 15px;">
        @if(file_exists(storage_path('app/public/category_head_banner.png')))
            <img src="{{ asset('storage/category_head_banner.png') }}"
                 alt="Категории инвентаря"
                 class="w-full h-auto object-cover">
        @else
            <!-- Запасной вариант если картинки нет -->
            <div class="relative w-full h-[188px] bg-gradient-to-r from-[#00532d] via-[#de0722] to-[#4f46e5] flex items-center justify-center">
                <div class="text-center px-6">
                    <h2 class="text-4xl md:text-5xl font-black text-white mb-3 text-center drop-shadow-2xl tracking-tight">
                        КАТЕГОРИИ ИНВЕНТАРЯ
                    </h2>
                    <p class="text-lg font-medium text-white text-center max-w-2xl opacity-95 mt-2">
                        Весь спортивный инвентарь для вашего активного отдыха
                    </p>
                </div>
            </div>
        @endif
    </div>
    <!-- Категории инвентаря -->
    <div class="mb-16">

        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-gray-200">
                        <div class="aspect-video overflow-hidden relative">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="w-full h-full object-cover hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center"
                                     style="background: linear-gradient(135deg, {{ $category->color ?? '#4f46e5' }}20 0%, {{ $category->color ?? '#00532d' }}10 100%)">
                                    <i class="fas fa-folder text-6xl"
                                       style="color: {{ $category->color ?? '#4f46e5' }}"></i>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-sm font-medium rounded-full text-white"
                                      style="background-color: {{ $category->color ?? '#4f46e5' }}">
                                    {{ $category->items_count ?? 0 }} {{ trans_choice('товар|товара|товаров', $category->items_count ?? 0) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-1 rounded-full mr-3" style="background-color: {{ $category->color ?? '#4f46e5' }}"></div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $category->name }}</h3>
                            </div>

                            @if($category->description)
                                <p class="text-gray-700 mb-6 line-clamp-2">
                                    {{ $category->description }}
                                </p>
                            @endif

                            <div class="flex justify-between items-center">
                                <div class="flex items-center text-sm text-gray-600">
                                </div>
                                <a href="{{ route('catalog.category', $category) }}"
                                   class="px-5 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-blue-600 hover:divide-blue-800 transition font-medium group shadow-sm hover:shadow-md">
                                    <span>Смотреть</span>
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-200">
                <div class="w-24 h-24 bg-[#00532d] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-folder-open text-4xl text-[#00532d]"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Категории пока не добавлены</h3>
                <p class="text-gray-700 mb-6">Администратор еще не добавил категории инвентаря</p>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.categories.create') }}"
                           class="bg-[#4f46e5] text-white px-6 py-3 rounded-lg hover:bg-[#4338ca] inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i> Добавить первую категорию
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </div>

    <!-- Преимущества аренды -->
    <div class="mb-16 bg-[#fbf9fa] rounded-2xl p-8 md:p-10 border border-gray-200">
        <h2 class="text-3xl font-bold text-gray-800 text-center" style="margin-top: 35px; margin-bottom: 35px;">
            <span>Почему выбирают нас?</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="w-16 h-16 bg-[#00532d] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-medal text-2xl text-[#00532d]"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Качество инвентаря</h3>
                <p class="text-gray-700">Весь инвентарь проходит регулярное обслуживание и проверку перед каждой арендой</p>
            </div>

            <div class="text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="w-16 h-16 bg-[#4f46e5] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl text-[#4f46e5]"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Безопасность</h3>
                <p class="text-gray-700">Страхование каждого предмета. Ваша безопасность — наш приоритет</p>
            </div>

            <div class="text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="w-16 h-16 bg-[#de0722] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-2xl text-[#de0722]"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Экономия времени</h3>
                <p class="text-gray-700">Быстрая онлайн-бронирование. Доставка и самовывоз доступны</p>
            </div>

            <div class="text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="w-16 h-16 bg-[#00532d] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wallet text-2xl text-[#00532d]"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Выгодные цены</h3>
                <p class="text-gray-700">Аренда выгоднее покупки. Скидки для постоянных клиентов</p>
            </div>
        </div>
    </div>
@endsection
