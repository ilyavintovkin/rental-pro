@extends('layouts.admin')

@section('title', 'Дашборд')
@section('subtitle', 'Общая статистика системы')

@section('content')
    <!-- Статистика -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-50 rounded-lg mr-4">
                    <i class="fas fa-calendar-alt text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</div>
                    <div class="text-gray-600">Всего бронирований</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-50 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_bookings'] }}</div>
                    <div class="text-gray-600">Ожидают подтверждения</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-50 rounded-lg mr-4">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['total_items'] }}</div>
                    <div class="text-gray-600">Товаров в каталоге</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-50 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['active_items'] }}</div>
                    <div class="text-gray-600">Доступно для бронирования</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-50 rounded-lg mr-4">
                    <i class="fas fa-folder text-purple-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['total_categories'] }}</div>
                    <div class="text-gray-600">Категорий</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-pink-50 rounded-lg mr-4">
                    <i class="fas fa-users text-pink-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</div>
                    <div class="text-gray-600">Пользователей</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Последние бронирования -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Последние бронирования</h3>
        </div>

        @if($recent_bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Клиент
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Инвентарь
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Даты
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
                    @foreach($recent_bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $booking->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $booking->item->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $booking->start_date->format('d.m.Y') }} - {{ $booking->end_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'active' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-gray-800 text-white',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];

                                    $statusLabels = [
                                        'pending' => 'Ожидание',
                                        'confirmed' => 'Подтверждено',
                                        'active' => 'Активно',
                                        'completed' => 'Завершено',
                                        'cancelled' => 'Отменено'
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$booking->status] }}">
        {{ $statusLabels[$booking->status] }}
    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.bookings') }}" class="text-indigo-600 hover:text-indigo-900">
                                    Управлять
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                Пока нет бронирований
            </div>
        @endif

        <div class="p-4 border-t text-center">
            <a href="{{ route('admin.bookings') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                <i class="fas fa-arrow-right mr-2"></i>Все бронирования
            </a>
        </div>
    </div>
@endsection
