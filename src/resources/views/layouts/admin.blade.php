<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - RentalPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar { min-height: 100vh; background: white; border-right: 1px solid #e5e7eb; }
        .nav-link { transition: all 0.2s; }
        .nav-link:hover { background-color: rgba(59, 130, 246, 0.1); }
        .nav-link.active { background-color: #3b82f6; color: white; }
    </style>
</head>
<body>
<div class="flex">
    <!-- Sidebar -->
    <div class="sidebar w-64">
        <div class="p-4 border-b">
            <h1 class="text-xl font-bold text-indigo-600">
                <i class="fas fa-cog mr-2"></i>Админ-панель
            </h1>
            <p class="text-sm text-gray-500 mt-1">Управление контентом</p>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link block px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-tachometer-alt mr-2"></i>Дашборд
            </a>

            <!-- ДОБАВЬТЕ ЭТУ ССЫЛКУ -->
            <a href="{{ route('admin.bookings') }}"
               class="nav-link block px-3 py-2 rounded-lg {{ request()->routeIs('admin.bookings*') ? 'active bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-calendar-alt mr-2"></i>Бронирования
                @php
                    $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.categories') }}"
               class="nav-link block px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories*') ? 'active bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-folder mr-2"></i>Категории
            </a>

            <a href="{{ route('admin.items') }}"
               class="nav-link block px-3 py-2 rounded-lg {{ request()->routeIs('admin.items*') ? 'active bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-box mr-2"></i>Товары
            </a>

            <div class="pt-4 mt-4 border-t">
                <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                    <i class="fas fa-external-link-alt mr-1"></i>На сайт
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-sign-out-alt mr-1"></i>Выйти
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1">
        <!-- Header -->
        <div class="bg-white border-b px-6 py-4">
            <h2 class="text-2xl font-bold text-gray-800">@yield('title', 'Админ-панель')</h2>
            <p class="text-gray-600">@yield('subtitle', 'Управление контентом сайта')</p>
        </div>

        <!-- Content -->
        <main class="p-6">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
