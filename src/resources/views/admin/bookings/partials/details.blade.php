<div class="space-y-6">
    <!-- Клиент -->
    <div>
        <h4 class="text-sm font-medium text-gray-500 mb-2">Клиент</h4>
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center">
                <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-user text-indigo-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-900">{{ $booking->user->name }}</div>
                    <div class="text-sm text-gray-600">{{ $booking->user->email }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Инвентарь -->
    <div>
        <h4 class="text-sm font-medium text-gray-500 mb-2">Инвентарь</h4>
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center">
                <div class="h-16 w-16 rounded-lg overflow-hidden bg-gray-100 mr-4">
                    @if($booking->item->main_image_url)
                        <img src="{{ $booking->item->main_image_url }}"
                             alt="{{ $booking->item->name }}"
                             class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center">
                            <i class="fas fa-box text-gray-400 text-xl"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="font-medium text-gray-900">{{ $booking->item->name }}</div>
                    <div class="text-sm text-gray-600">{{ $booking->item->category->name ?? 'Без категории' }}</div>
                    <div class="text-sm text-gray-500">Арт. {{ $booking->item->sku }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Даты -->
    <div>
        <h4 class="text-sm font-medium text-gray-500 mb-2">Период бронирования</h4>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500">Начало</div>
                <div class="font-medium">{{ $booking->start_date->format('d.m.Y') }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500">Окончание</div>
                <div class="font-medium">{{ $booking->end_date->format('d.m.Y') }}</div>
            </div>
        </div>
        <div class="mt-2 text-center text-sm text-gray-600">
            Всего: {{ $booking->days }} {{ trans_choice('день|дня|дней', $booking->days) }}
        </div>
    </div>

    <!-- Стоимость -->
    <div>
        <h4 class="text-sm font-medium text-gray-500 mb-2">Стоимость</h4>
        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Аренда ({{ $booking->days }} дн.)</span>
                <span class="font-medium">{{ number_format($booking->total_price, 0, ',', ' ') }} ₽</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Цена за день</span>
                <span>{{ number_format($booking->daily_price, 0, ',', ' ') }} ₽/день</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Залог</span>
                <span>{{ number_format($booking->deposit_amount, 0, ',', ' ') }} ₽</span>
            </div>
            <div class="border-t pt-2 mt-2">
                <div class="flex justify-between font-semibold">
                    <span>Итого к оплате</span>
                    <span class="text-lg">{{ number_format($booking->total_price + $booking->deposit_amount, 0, ',', ' ') }} ₽</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Статус -->
    <div>
        <h4 class="text-sm font-medium text-gray-500 mb-2">Статус</h4>
        <div class="bg-gray-50 rounded-lg p-4">
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'confirmed' => 'bg-blue-100 text-blue-800',
                    'active' => 'bg-green-100 text-green-800',
                    'completed' => 'bg-gray-800 text-white',
                    'cancelled' => 'bg-red-100 text-red-800'
                ];

                $statusLabels = [
                    'pending' => 'Ожидание подтверждения',
                    'confirmed' => 'Подтверждено (ожидает оплаты)',
                    'active' => 'Активно (оплачено)',
                    'completed' => 'Завершено',
                    'cancelled' => 'Отменено'
                ];
            @endphp

            <span class="px-4 py-2 text-sm font-medium rounded-full {{ $statusColors[$booking->status] }}">
                {{ $statusLabels[$booking->status] }}
            </span>

            <div class="mt-3 text-sm text-gray-600">
                Создано: {{ $booking->created_at->format('d.m.Y H:i') }}
                @if($booking->updated_at != $booking->created_at)
                    <br>Обновлено: {{ $booking->updated_at->format('d.m.Y H:i') }}
                @endif
            </div>
        </div>
    </div>

    <!-- Примечания -->
    @if($booking->notes)
        <div>
            <h4 class="text-sm font-medium text-gray-500 mb-2">Примечания</h4>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700">{{ $booking->notes }}</p>
            </div>
        </div>
    @endif
</div>
