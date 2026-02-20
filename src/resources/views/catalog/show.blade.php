@extends('layouts.app')

@section('title', $item->name)

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-6xl mx-auto p-4 sm:p-8">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 lg:p-8">

                    <!-- Изображения -->
                    <div class="space-y-4">
                        <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl overflow-hidden shadow-inner">
                            <img id="main" src="{{ $item->main_image_url }}"
                                 class="w-full h-full object-contain p-4 hover:scale-105 transition-transform duration-300"/>
                        </div>
                        @if(count($item->image_urls) > 0)
                            <div class="flex gap-3 overflow-x-auto pb-2">
                                @foreach($item->image_urls as $url)
                                    <button onclick="document.getElementById('main').src='{{ $url }}';
                                        this.classList.toggle('ring-2', !this.classList.contains('ring-2'))"
                                            class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border hover:border-blue-400 bg-white p-0.5 transition-all">
                                        <img src="{{ $url }}" class="w-full h-full object-cover rounded"/>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Информация -->
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">{{ $item->name }}</h1>
                                    <p class="text-gray-500 mt-2 text-sm">Арт. {{ $item->sku }}</p>
                                </div>
                                @if($item->is_available && $item->quantity > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-50 text-green-700">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        В наличии
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4">
                                <div class="text-3xl lg:text-4xl font-bold text-gray-900">
                                    {{ number_format($item->price_per_day, 0, ',', ' ') }} ₽
                                    <span class="text-lg text-gray-500 font-normal">/ сутки</span>
                                </div>
                                @if($item->deposit)
                                    <div class="text-gray-600 mt-1">+ залог {{ number_format($item->deposit, 0, ',', ' ') }} ₽</div>
                                @endif
                            </div>
                        </div>

                        @if($item->is_available && $item->quantity > 0)
                            <form id="book" class="space-y-5" method="POST" action="{{ route('booking.create', $item) }}">
                                @csrf

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1.5">Начало</label>
                                        <input type="date" name="start_date" id="start_date" required
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1.5">Окончание</label>
                                        <input type="date" name="end_date" id="end_date" required
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Аренда:</span>
                                        <span id="rent" class="font-medium">0 ₽</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Залог:</span>
                                        <span class="font-medium">{{ number_format($item->deposit, 0, ',', ' ') }} ₽</span>
                                    </div>
                                    <div class="flex justify-between font-semibold text-lg pt-2 border-t">
                                        <span>Итого:</span>
                                        <span id="total">0 ₽</span>
                                    </div>
                                </div>

                                @auth
                                    <button type="submit"
                                            class="w-full bg-indigo-600 text-white font-bold py-4 px-6 rounded-xl hover:bg-indigo-700 transition-all duration-300 shadow-lg hover:shadow-2xl active:scale-95">
                                        <i class="fas fa-calendar-check mr-3"></i>
                                        Забронировать
                                    </button>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="group relative flex items-center gap-4 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 p-4 text-white shadow-lg transition-all hover:from-indigo-600 hover:to-indigo-700 hover:shadow-xl active:scale-[0.98]">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                            <i class="fas fa-sign-in-alt text-lg text-white"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-indigo-100">Для бронирования товаров</div>
                                            <div class="text-base font-semibold">Войдите в аккаунт</div>
                                        </div>
                                        <i class="fas fa-arrow-right ml-2 text-xl transition-transform group-hover:translate-x-1"></i>
                                    </a>
                                @endauth
                            </form>
                        @else
                            <div class="bg-red-50 text-red-700 px-4 py-3 rounded-lg font-medium">
                                Товар временно недоступен
                            </div>
                        @endif

                        <div class="pt-4 border-t">
                            <h3 class="font-semibold text-gray-900 mb-3">Описание</h3>
                            <div class="text-gray-700 leading-relaxed prose prose-sm max-w-none">
                                {!! nl2br(e($item->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно успешного бронирования -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full mx-auto animate-scale-in">
            <div class="p-6 text-center">
                <!-- Иконка успеха -->
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-4xl text-green-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Бронирование создано!</h3>
            </div>
        </div>
    </div>

    <style>
        .animate-scale-in {
            animation: scaleIn 0.3s ease-out;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <script>
        const price = {{ $item->price_per_day }};
        const deposit = {{ $item->deposit ?? 0 }};

        document.addEventListener('DOMContentLoaded', function() {
            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');

            if (startInput && endInput) {
                const today = new Date().toISOString().split('T')[0];
                startInput.min = today;
                endInput.min = today;

                startInput.addEventListener('change', function() {
                    if (this.value) {
                        endInput.min = this.value;
                        calculate();
                    }
                });

                endInput.addEventListener('change', calculate);
            }

            const bookForm = document.getElementById('book');
            if (bookForm) {
                bookForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!startInput.value || !endInput.value) {
                        alert('Пожалуйста, выберите даты начала и окончания бронирования');
                        return;
                    }

                    const start = new Date(startInput.value);
                    const end = new Date(endInput.value);
                    if (end <= start) {
                        alert('Дата окончания должна быть позже даты начала');
                        return;
                    }

                    const originalButton = this.querySelector('button[type="submit"]');
                    if (originalButton) {
                        originalButton.disabled = true;
                        originalButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Обработка...';
                    }

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw err; });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Success:', data);

                            // Показываем модальное окно
                            const modal = document.getElementById('bookingModal');
                            if (modal) {
                                modal.classList.remove('hidden');
                                document.body.style.overflow = 'hidden';

                                setTimeout(() => {
                                    modal.classList.add('hidden');
                                    document.body.style.overflow = 'auto';
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    } else {
                                        window.location.href = "{{ route('profile.bookings') }}";
                                    }
                                }, 3000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            let errorMessage = 'Произошла ошибка при создании бронирования';

                            if (error.errors) {
                                // Ошибки валидации Laravel
                                errorMessage = Object.values(error.errors).flat().join('\n');
                            } else if (error.message) {
                                errorMessage = error.message;
                            } else if (error.error) {
                                errorMessage = error.error;
                            }

                            alert(errorMessage);

                            if (originalButton) {
                                originalButton.disabled = false;
                                originalButton.innerHTML = '<i class="fas fa-calendar-check mr-3"></i>Забронировать';
                            }
                        });
                });
            }

            const modal = document.getElementById('bookingModal');
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target.id === 'bookingModal') {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        window.location.href = "{{ route('profile.bookings') }}";
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('bookingModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        window.location.href = "{{ route('profile.bookings') }}";
                    }
                }
            });
        });

        function calculate() {
            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');

            if (!startInput || !endInput || !startInput.value || !endInput.value) return;

            const start = new Date(startInput.value);
            const end = new Date(endInput.value);

            if (end > start) {
                const days = Math.ceil((end - start) / 86400000);
                const rentCost = days * price;
                const totalCost = rentCost + deposit;

                const rentEl = document.getElementById('rent');
                const totalEl = document.getElementById('total');

                if (rentEl) rentEl.textContent = rentCost.toLocaleString('ru') + ' ₽';
                if (totalEl) totalEl.textContent = totalCost.toLocaleString('ru') + ' ₽';
            }
        }
    </script>
@endsection
