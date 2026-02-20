@extends('layouts.admin')

@section('title', 'Добавить товар')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Добавление нового товара</h3>

            {{-- ВЫВОД ОШИБОК ВАЛИДАЦИИ --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <strong class="font-bold">Ошибки:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" id="itemForm">
                @csrf

                <div class="space-y-6">
                    <!-- Основная информация -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Категория *</label>
                            <select name="category_id"
                                    required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Выберите категорию</option>
                                @foreach(App\Models\Category::where('is_active', true)->get() as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Артикул (SKU) *</label>
                            <input type="text"
                                   name="sku"
                                   value="{{ old('sku') }}"
                                   required
                                   placeholder="BIKE-001"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <!-- Название -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Название товара *</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>

                    <!-- Описание -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Описание *</label>
                        <textarea name="description"
                                  rows="4"
                                  required
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3">{{ old('description') }}</textarea>
                    </div>

                    <!-- Цена и залог -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Цена за день (₽) *</label>
                            <input type="number"
                                   name="price_per_day"
                                   value="{{ old('price_per_day') }}"
                                   required
                                   min="0"
                                   step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Залог (₽)</label>
                            <input type="number"
                                   name="deposit"
                                   value="{{ old('deposit') }}"
                                   min="0"
                                   step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <!-- Количество и состояние -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Количество *</label>
                            <input type="number"
                                   name="quantity"
                                   value="{{ old('quantity', 1) }}"
                                   required
                                   min="1"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Состояние *</label>
                            <select name="condition"
                                    required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                <option value="excellent" {{ old('condition') == 'excellent' ? 'selected' : '' }}>Отличное</option>
                                <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Хорошее</option>
                                <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Удовлетворительное</option>
                                <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Плохое</option>
                            </select>
                        </div>
                    </div>

                    <!-- Изображения -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Изображения товара</label>

                        <!-- Главное изображение -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Главное изображение *</p>
                            <input type="file"
                                   name="main_image"
                                   id="main_image"
                                   accept="image/*"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, до 2MB</p>
                        </div>

                        <!-- Дополнительные изображения -->
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Дополнительные изображения (до 5)</p>
                            <input type="file"
                                   name="images[]"
                                   multiple
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        </div>
                    </div>

                    <!-- Характеристики -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Характеристики</label>
                        <textarea name="specifications_text"
                                  rows="6"
                                  placeholder="Материал: Алюминий
Вес: 13.5 кг
Цвет: Черный"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 font-mono text-sm">{{ old('specifications_text') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Формат: Название: Значение (каждая характеристика с новой строки)</p>
                    </div>

                    <!-- Статус -->
                    <div class="flex items-center space-x-3">
                        <input type="checkbox"
                               name="is_available"
                               value="1"
                               {{ old('is_available', true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700">Товар доступен для бронирования</span>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('admin.items') }}"
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Отмена
                        </a>
                        <button type="submit"
                                id="submitBtn"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>Создать товар
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('itemForm');
            const submitBtn = document.getElementById('submitBtn');
            let isSubmitting = false;

            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                // Проверка файла перед отправкой
                const fileInput = document.getElementById('main_image');
                const file = fileInput.files[0];

                if (file) {
                    // Проверка размера файла (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        e.preventDefault();
                        alert('Файл слишком большой! Максимум 2MB');
                        return false;
                    }

                    // Проверка типа файла
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        e.preventDefault();
                        alert('Неправильный формат файла! Только JPG, PNG, GIF');
                        return false;
                    }
                }

                // Блокировка кнопки при отправке
                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Создание...';

                // Автоматическая отправка
                return true;
            });

            // Разблокировка формы если что-то пошло не так
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Создать товар';
                }
            });
        });
    </script>
@endsection
