@extends('layouts.app')

@section('title', 'Управление бронированиями')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Заголовок -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Управление бронированиями</h1>
            <p class="text-gray-600">Подтверждение и управление бронированиями клиентов</p>
        </div>

        <!-- Фильтры -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.bookings') }}"
                   class="px-4 py-2 rounded-full {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Все
                </a>
                <a href="{{ route('admin.bookings', ['status' => 'pending']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Ожидание ({{ \App\Models\Booking::where('status', 'pending')->count() }})
                </a>
                <a href="{{ route('admin.bookings', ['status' => 'confirmed']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Подтверждено
                </a>
                <a href="{{ route('admin.bookings', ['status' => 'active']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Активно
                </a>
                <a href="{{ route('admin.bookings', ['status' => 'completed']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') == 'completed' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Завершено
                </a>
                <a href="{{ route('admin.bookings', ['status' => 'cancelled']) }}"
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
                                Клиент
                            </th>
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
                                <!-- Клиент -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->user->email }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $booking->created_at->format('d.m.Y H:i') }}
                                    </div>
                                </td>

                                <!-- Инвентарь -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 rounded-lg overflow-hidden bg-gray-100 mr-3">
                                            @if($booking->item->main_image_url)
                                                <img src="{{ $booking->item->main_image_url }}"
                                                     alt="{{ $booking->item->name }}"
                                                     class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
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
                                        {{ $booking->start_date->format('d.m.Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        до {{ $booking->end_date->format('d.m.Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $booking->days }} дней
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

                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$booking->status] }}">
                                        {{ $statusLabels[$booking->status] }}
                                    </span>
                                </td>

                                <!-- Действия -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Просмотр -->
                                        <button onclick="showBookingDetails({{ $booking->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 p-1.5 rounded hover:bg-indigo-50"
                                                title="Подробнее">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Подтвердить -->
                                        @if($booking->status == 'pending')
                                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-900 p-1.5 rounded hover:bg-green-50"
                                                        title="Подтвердить">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Оплачено -->
                                        @if($booking->status == 'confirmed')
                                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit"
                                                        class="text-blue-600 hover:text-blue-900 p-1.5 rounded hover:bg-blue-50"
                                                        title="Отметить как оплачено">
                                                    <i class="fas fa-credit-card"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Завершить -->
                                        @if($booking->status == 'active')
                                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit"
                                                        class="text-gray-600 hover:text-gray-900 p-1.5 rounded hover:bg-gray-50"
                                                        title="Завершить">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Отменить -->
                                        @if(in_array($booking->status, ['pending', 'confirmed']))
                                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 p-1.5 rounded hover:bg-red-50"
                                                        title="Отменить"
                                                        onclick="return confirm('Отменить бронирование?')">
                                                    <i class="fas fa-times"></i>
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
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ \App\Models\Booking::where('status', 'pending')->count() }}
                    </div>
                    <div class="text-gray-600">Ожидают подтверждения</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ \App\Models\Booking::where('status', 'confirmed')->count() }}
                    </div>
                    <div class="text-gray-600">Подтверждено (не оплачено)</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="text-2xl font-bold text-green-600">
                        {{ \App\Models\Booking::where('status', 'active')->count() }}
                    </div>
                    <div class="text-gray-600">Активные бронирования</div>
                </div>
            </div>
        @else
            <!-- Пустой список -->
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Нет бронирований</h3>
                <p class="text-gray-600 mb-6">Пока нет активных бронирований</p>
            </div>
        @endif
    </div>

    <!-- Модальное окно деталей бронирования -->
    <div id="bookingDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full mx-auto max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Детали бронирования</h3>
                    <button onclick="closeBookingDetails()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="bookingDetailsContent">
                    <!-- Контент загружается через JS -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBookingDetails(bookingId) {
            fetch(`/admin/bookings/${bookingId}/details`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('bookingDetailsContent').innerHTML = data.html;
                    document.getElementById('bookingDetailsModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
        }

        function closeBookingDetails() {
            document.getElementById('bookingDetailsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Закрытие по клику на фон
        document.getElementById('bookingDetailsModal').addEventListener('click', function(e) {
            if (e.target.id === 'bookingDetailsModal') {
                closeBookingDetails();
            }
        });

        // Закрытие по Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBookingDetails();
            }
        });
    </script>
@endsection
