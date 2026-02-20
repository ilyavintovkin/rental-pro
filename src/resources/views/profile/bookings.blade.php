@extends('layouts.app')

@section('title', 'Мои бронирования')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Заголовок -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Мои бронирования</h1>
            <p class="text-gray-600">История всех ваших заказов на прокат инвентаря</p>
        </div>

        <!-- Уведомления -->
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

        <!-- Фильтры статусов -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('profile.bookings') }}"
                   class="px-4 py-2 rounded-full {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Все
                </a>
                <a href="{{ route('profile.bookings', ['status' => 'pending']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Ожидание
                </a>
                <a href="{{ route('profile.bookings', ['status' => 'confirmed']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Подтверждено
                </a>
                <a href="{{ route('profile.bookings', ['status' => 'active']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Активно
                </a>
                <a href="{{ route('profile.bookings', ['status' => 'completed']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'completed' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Завершено
                </a>
                <a href="{{ route('profile.bookings', ['status' => 'cancelled']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Отменено
                </a>
            </div>
        </div>

        <!-- Таблица бронирований -->
        @if($bookings->count() > 0)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Инвентарь
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Даты
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Стоимость
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Статус
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Инвентарь -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16 rounded-lg overflow-hidden bg-gray-100">
                                            @if($booking->item->main_image_url)
                                                <img src="{{ $booking->item->main_image_url }}"
                                                     alt="{{ $booking->item->name }}"
                                                     class="h-full w-full object-cover"
                                                     onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"64\" height=\"64\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%239CA3AF\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><rect x=\"3\" y=\"3\" width=\"18\" height=\"18\" rx=\"2\" ry=\"2\"></rect><circle cx=\"8.5\" cy=\"8.5\" r=\"1.5\"></circle><polyline points=\"21 15 16 10 5 21\"></polyline></svg>';">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center bg-gradient-to-r from-gray-100 to-gray-200">
                                                    @php
                                                        $icon = match($booking->item->category->name ?? '') {
                                                            'Велосипеды' => 'fa-bicycle',
                                                            'Самокаты' => 'fa-scooter',
                                                            'Ролики' => 'fa-skating',
                                                            'Сноуборды' => 'fa-snowboarding',
                                                            'Лыжи' => 'fa-skiing',
                                                            'Квадрокоптеры' => 'fa-drone',
                                                            'Фотоаппараты' => 'fa-camera',
                                                            default => 'fa-box'
                                                        };
                                                    @endphp
                                                    <i class="fas {{ $icon }} text-gray-400 text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $booking->item->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $booking->item->category->name ?? 'Без категории' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Даты -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->start_date)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        на {{ $booking->days }} {{ trans_choice('день|дня|дней', $booking->days) }}
                                    </div>
                                </td>

                                <!-- Стоимость -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format($booking->total_price, 0, ',', ' ') }} ₽
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        + залог {{ number_format($booking->deposit_amount, 0, ',', ' ') }} ₽
                                    </div>
                                </td>

                                <!-- Статус -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                            'confirmed' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                            'active' => 'bg-green-100 text-green-800 border border-green-200',
                                            'completed' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border border-red-200'
                                        ];

                                        $statusLabels = [
                                            'pending' => 'Ожидание',
                                            'confirmed' => 'Подтверждено',
                                            'active' => 'Активно',
                                            'completed' => 'Завершено',
                                            'cancelled' => 'Отменено'
                                        ];
                                    @endphp

                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$booking->status] }}">
                                        <i class="fas fa-circle text-[10px] mr-1 opacity-70"></i>
                                        {{ $statusLabels[$booking->status] }}
                                    </span>
                                </td>

                                <!-- Действия -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-4"> <!-- УВЕЛИЧИЛ space-x-3 до space-x-4 -->
                                        <a href="{{ route('catalog.show', $booking->item) }}"
                                           class="text-indigo-600 hover:text-indigo-900 transition p-2 rounded-lg hover:bg-indigo-50"
                                           title="Посмотреть инвентарь">
                                            <i class="fas fa-eye text-lg"></i>
                                        </a>

                                        @if(in_array($booking->status, ['pending', 'confirmed']))
                                            <form action="{{ route('booking.cancel', $booking) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Вы уверены, что хотите отменить бронирование?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition p-2 rounded-lg hover:bg-red-50"
                                                        title="Отменить бронирование">
                                                    <i class="fas fa-times text-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Пагинация -->
                @if($bookings->hasPages())
                    <div class="bg-white px-6 py-4 border-t border-gray-200">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>

            <!-- Статистика -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-50 rounded-lg mr-4">
                            <i class="fas fa-calendar-alt text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">{{ $bookings->total() }}</div>
                            <div class="text-gray-600 text-sm">Всего бронирований</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-50 rounded-lg mr-4">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ $bookings->where('status', 'pending')->count() }}
                            </div>
                            <div class="text-gray-600 text-sm">Ожидают подтверждения</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-50 rounded-lg mr-4">
                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $bookings->where('status', 'confirmed')->count() }}
                            </div>
                            <div class="text-gray-600 text-sm">Подтверждено</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-50 rounded-lg mr-4">
                            <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">
                                {{ $bookings->whereIn('status', ['completed', 'active'])->count() }}
                            </div>
                            <div class="text-gray-600 text-sm">Активно/Завершено</div>
                        </div>
                    </div>
                </div>
            </div>
        @else
        @endif
    </div>
@endsection
