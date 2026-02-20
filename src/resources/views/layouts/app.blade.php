<!DOCTYPE html>
<html lang="ru" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RentalPro - Прокат инвентаря')</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .admin-badge {
            background: linear-gradient(45deg, #8b5cf6, #3b82f6);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 4px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        /* Для выпадающего меню которое не скрывается */
        .dropdown-container:hover .dropdown-menu {
            display: block !important;
        }
        .dropdown-menu:hover {
            display: block !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 min-h-full flex flex-col">
<!-- Навигация -->
<nav class="bg-white shadow-sm border-b sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Левая часть: навигационные ссылки -->
            <div class="flex items-center space-x-6">
                <!-- Логотип -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 text-xl font-bold text-indigo-600">
                    <i class="fas fa-bicycle"></i>
                    <span>RentalPro</span>
                </a>

                <!-- Разделитель -->
                <span class="text-gray-300 hidden md:inline">|</span>

                <!-- Административные ссылки (только для админов) -->
                @auth
                    @if(Auth::user()->isAdmin())
                        <div class="hidden md:flex items-center space-x-6">
                            <!-- Дашборд -->
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-gray-600 hover:text-indigo-600 transition font-medium">
                                Дашборд
                            </a>

                            <!-- Товары -->
                            <a href="{{ route('admin.items') }}"
                               class="text-gray-600 hover:text-indigo-600 transition font-medium">
                                Товары
                            </a>

                            <!-- Добавить товар -->
                            <a href="{{ route('admin.items.create') }}"
                               class="text-gray-600 hover:text-indigo-600 transition font-medium">
                                Добавить Товар
                            </a>

                            <!-- Категории -->
                            <a href="{{ route('admin.categories') }}"
                               class="text-gray-600 hover:text-indigo-600 transition font-medium">
                                Категории
                            </a>

                            <!-- Добавить категорию -->
                            <a href="{{ route('admin.categories.create') }}"
                               class="text-gray-600 hover:text-indigo-600 transition font-medium">
                                Добавить Категорию
                            </a>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Правая часть: пользователь -->
            <div class="flex items-center space-x-4">
                <!-- АДМИН-БЕЙДЖ (только для администраторов) -->
                @auth
                    @if(Auth::user()->isAdmin())
                        <div class="hidden md:block">
                            <span class="admin-badge text-xs px-3 py-1">ADMIN</span>
                        </div>
                    @endif

                    <!-- Пользователь -->
                        <!-- Пользователь -->
                        <div class="flex items-center space-x-3">
                            <!-- Кнопка меню пользователя с именем -->
                            <div class="relative" id="userMenuContainer">
                                <button id="userMenuButton"
                                        class="flex items-center justify-center px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition focus:outline-none text-gray-700 font-medium">
                                    <i class="fas fa-user mr-2 text-sm"></i>
                                    {{ Auth::user()->name }}
                                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                </button>

                                <!-- Выпадающее меню пользователя -->
                                <div id="userMenuDropdown"
                                     class="absolute hidden bg-white shadow-lg rounded-lg mt-2 py-2 w-48 z-50 right-0 border border-gray-100">
                                    <a href="{{ route('profile.bookings') }}"
                                       class="block px-4 py-2 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        <i class="fas fa-user-circle mr-2"></i>Личный кабинет
                                    </a>
                                    <div class="border-t mt-1 pt-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Выйти
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                @else
                    <!-- Для неавторизованных -->
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        Войти
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        Регистрация
                    </a>
                @endauth
            </div>

            <!-- Мобильное меню (скрыто на десктопе) -->
            <button class="md:hidden text-gray-600 focus:outline-none" id="mobileMenuButton">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Мобильное меню (скрыто) -->
        <div class="md:hidden hidden border-t mt-2 pt-2 pb-4" id="mobileMenu">
            <!-- Админ-ссылки для мобильных -->
            @auth
                @if(Auth::user()->isAdmin())
                    <div class="mb-4">
                        <div class="px-4 py-2 text-sm font-medium text-gray-400">Админ-панель</div>
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded">
                            <i class="fas fa-tachometer-alt mr-3"></i>Дашборд
                        </a>
                        <a href="{{ route('admin.items') }}" class="block py-2 px-4 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded">
                            <i class="fas fa-box mr-3"></i>Товары
                        </a>
                        <a href="{{ route('admin.items.create') }}" class="block py-2 px-4 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded">
                            <i class="fas fa-plus mr-3"></i>Добавить товар
                        </a>
                        <a href="{{ route('admin.categories') }}" class="block py-2 px-4 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded">
                            <i class="fas fa-folder mr-3"></i>Категории
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="block py-2 px-4 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded">
                            <i class="fas fa-plus mr-3"></i>Добавить категорию
                        </a>
                    </div>
                @endif
            @endauth

            <!-- Основные ссылки -->
            <a href="{{ route('catalog.index') }}" class="block py-2 px-4 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded">
                <i class="fas fa-th-large mr-3"></i>Каталог
            </a>

            @auth
                <!-- Личный кабинет -->
                <a href="{{ route('profile.bookings') }}" class="block py-2 px-4 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded">
                    <i class="fas fa-user-circle mr-3"></i>Личный кабинет
                </a>
                <a href="{{ route('profile.edit') }}" class="block py-2 px-6 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded">
                    <i class="fas fa-cog mr-3 text-sm"></i>Настройки
                </a>

                <!-- Выход -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 px-4 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded mt-1">
                        <i class="fas fa-sign-out-alt mr-3"></i>Выйти
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<!-- Основной контент -->
<main class="flex-grow container mx-auto px-4 py-8">
    @yield('content')
</main>

<!-- Футер -->
<footer class="bg-gray-800 text-white mt-12">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="text-xl font-bold mb-4">RentalPro</div>
                <p class="text-gray-400 text-sm">
                    Лучший прокат спортивного инвентаря и оборудования для активного отдыха.
                </p>
            </div>

            <div>
                <div class="font-medium mb-4">Категории</div>
                <ul class="space-y-2 text-gray-400 text-sm">
                    @foreach(App\Models\Category::where('is_active', true)->limit(5)->get() as $category)
                        <li><a href="{{ route('catalog.category', $category) }}" class="hover:text-white transition">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <div class="font-medium mb-4">Контакты</div>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><i class="fas fa-phone mr-2"></i> +7 (999) 123-45-67</li>
                    <li><i class="fas fa-envelope mr-2"></i> info@rentalpro.ru</li>
                    <li><i class="fas fa-map-marker-alt mr-2"></i> г. Москва, ул. Спортивная, 15</li>
                </ul>
            </div>

            <div>
                <div class="font-medium mb-4">Мы в соцсетях</div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-vk text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-telegram text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram text-xl"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
            © {{ date('Y') }} RentalPro. Все права защищены.
        </div>
    </div>
</footer>

<!-- Скрипты -->
<script>
    // Мобильное меню
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    });

    // Меню пользователя
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenuDropdown = document.getElementById('userMenuDropdown');
        const userMenuContainer = document.getElementById('userMenuContainer');

        if (userMenuButton && userMenuDropdown && userMenuContainer) {
            let isUserMenuOpen = false;
            let userMenuCloseTimeout = null;

            // Функция открытия/закрытия меню пользователя
            function toggleUserMenu() {
                isUserMenuOpen = !isUserMenuOpen;
                if (isUserMenuOpen) {
                    userMenuDropdown.classList.remove('hidden');
                    // Отменяем таймер закрытия если был
                    if (userMenuCloseTimeout) {
                        clearTimeout(userMenuCloseTimeout);
                        userMenuCloseTimeout = null;
                    }
                } else {
                    userMenuDropdown.classList.add('hidden');
                }
            }

            // Клик по кнопке меню пользователя
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleUserMenu();
            });

            // Клик в любом месте документа
            document.addEventListener('click', function(e) {
                if (isUserMenuOpen &&
                    !userMenuContainer.contains(e.target) &&
                    !userMenuDropdown.contains(e.target)) {
                    isUserMenuOpen = false;
                    userMenuDropdown.classList.add('hidden');
                }
            });

            // При наведении на меню - отменяем закрытие
            userMenuDropdown.addEventListener('mouseenter', function() {
                if (userMenuCloseTimeout) {
                    clearTimeout(userMenuCloseTimeout);
                    userMenuCloseTimeout = null;
                }
            });

            // При уходе с меню - закрываем с задержкой
            userMenuDropdown.addEventListener('mouseleave', function() {
                userMenuCloseTimeout = setTimeout(function() {
                    isUserMenuOpen = false;
                    userMenuDropdown.classList.add('hidden');
                }, 300);
            });

            // При наведении на кнопку - отменяем закрытие
            userMenuButton.addEventListener('mouseenter', function() {
                if (userMenuCloseTimeout) {
                    clearTimeout(userMenuCloseTimeout);
                    userMenuCloseTimeout = null;
                }
            });

            // При уходе с кнопки (но не на меню) - закрываем с задержкой
            userMenuButton.addEventListener('mouseleave', function(e) {
                // Проверяем, перешел ли курсор на меню
                const relatedTarget = e.relatedTarget;
                if (!userMenuDropdown.contains(relatedTarget)) {
                    userMenuCloseTimeout = setTimeout(function() {
                        if (isUserMenuOpen && !userMenuDropdown.matches(':hover')) {
                            isUserMenuOpen = false;
                            userMenuDropdown.classList.add('hidden');
                        }
                    }, 300);
                }
            });
        }
    });

    // Закрытие мобильного меню при клике вне его
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('mobileMenu');
        const button = document.getElementById('mobileMenuButton');

        if (menu && !menu.classList.contains('hidden') &&
            !menu.contains(event.target) &&
            !button.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });

    // Закрытие мобильного меню при нажатии Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const menu = document.getElementById('mobileMenu');
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }

            // Также закрываем меню пользователя если открыто
            const userMenu = document.getElementById('userMenuDropdown');
            if (userMenu && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
                // Сбрасываем флаг
                const userMenuButton = document.getElementById('userMenuButton');
                if (userMenuButton) {
                    userMenuButton.setAttribute('aria-expanded', 'false');
                }
            }
        }
    });
</script>

@stack('scripts')
</body>
</html>
