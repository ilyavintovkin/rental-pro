@extends('layouts.admin')

@section('title', 'Редактировать товар')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Редактирование товара</h3>
                    <p class="text-gray-600 mt-1">{{ $item->name }} ({{ $item->sku }})</p>
                </div>
                <a href="{{ route('admin.items') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Назад
                </a>
            </div>

            <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-8">
                    <!-- Основная информация -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Основная информация</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Категория *</label>
                                <select name="category_id"
                                        required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">Выберите категорию</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Артикул (SKU) *</label>
                                <input type="text"
                                       name="sku"
                                       value="{{ old('sku', $item->sku) }}"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Название товара *</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', $item->name) }}"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Описание *</label>
                                <textarea name="description"
                                          rows="4"
                                          required
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ old('description', $item->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Цена и характеристики -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Цены и состояние</h4>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Цена за день (₽) *</label>
                                    <input type="number"
                                           name="price_per_day"
                                           value="{{ old('price_per_day', $item->price_per_day) }}"
                                           required
                                           min="0"
                                           step="0.01"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Залог (₽)</label>
                                    <input type="number"
                                           name="deposit"
                                           value="{{ old('deposit', $item->deposit) }}"
                                           min="0"
                                           step="0.01"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Количество *</label>
                                    <input type="number"
                                           name="quantity"
                                           value="{{ old('quantity', $item->quantity) }}"
                                           required
                                           min="1"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Состояние *</label>
                                    <select name="condition"
                                            required
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                        <option value="excellent" {{ $item->condition == 'excellent' ? 'selected' : '' }}>Отличное</option>
                                        <option value="good" {{ $item->condition == 'good' ? 'selected' : '' }}>Хорошее</option>
                                        <option value="fair" {{ $item->condition == 'fair' ? 'selected' : '' }}>Удовлетворительное</option>
                                        <option value="poor" {{ $item->condition == 'poor' ? 'selected' : '' }}>Плохое</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Изображения</h4>
                            <div class="space-y-6">
                                <!-- Текущее изображение -->
                                @if($item->main_image)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2">Текущее изображение</p>
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ $category->image }}"
                                                 alt="{{ $item->name }}"
                                                 class="w-24 h-24 object-cover rounded-lg border">
                                            <div class="text-sm text-gray-600">
                                                <p class="font-medium">Главное изображение</p>
                                                <p class="text-xs mt-1">Будет заменено при загрузке нового</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Замена изображения -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        @if($item->main_image)
                                            Заменить изображение
                                        @else
                                            Загрузить главное изображение *
                                        @endif
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-400 transition cursor-pointer bg-white"
                                         onclick="document.getElementById('main_image_input').click()">
                                        <div class="text-gray-400 text-3xl mb-3">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <p class="text-gray-600 mb-2">Нажмите или перетащите файл</p>
                                        <p class="text-sm text-gray-500">JPG, PNG до 2MB</p>
                                        <input type="file"
                                               name="main_image"
                                               id="main_image_input"
                                               accept="image/*"
                                               class="hidden"
                                               onchange="previewMainImage(this)">
                                    </div>
                                    <div id="mainImagePreview" class="mt-3 hidden">
                                        <p class="text-sm text-gray-600 mb-1">Новое изображение:</p>
                                        <img id="mainPreviewImage" class="w-32 h-32 object-cover rounded-lg border">
                                    </div>
                                </div>

                                <!-- Дополнительные изображения -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Дополнительные изображения</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-400 transition cursor-pointer bg-white"
                                         onclick="document.getElementById('extra_images_input').click()">
                                        <div class="text-gray-400 text-2xl mb-2">
                                            <i class="fas fa-images"></i>
                                        </div>
                                        <p class="text-gray-600">Добавить несколько изображений</p>
                                        <p class="text-xs text-gray-500 mt-1">Можно выбрать до 5 файлов</p>
                                        <input type="file"
                                               name="images[]"
                                               id="extra_images_input"
                                               multiple
                                               accept="image/*"
                                               class="hidden"
                                               onchange="handleExtraImages(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Характеристики -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Характеристики</h4>
                        <textarea name="specifications_text"
                                  rows="6"
                                  placeholder="Материал: Алюминий
Вес: 13.5 кг
Цвет: Черный
Размер: L
Год выпуска: 2023"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 font-mono text-sm bg-white">{{ old('specifications_text', $item->specifications_text) }}</textarea>
                        <p class="text-sm text-gray-500 mt-2">Формат: "Название: Значение" (каждая характеристика с новой строки)</p>
                    </div>

                    <!-- Статус -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center h-6">
                                <input type="checkbox"
                                       name="is_available"
                                       id="is_available"
                                       value="1"
                                       {{ old('is_available', $item->is_available) ? 'checked' : '' }}
                                       class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="is_available" class="text-sm font-medium text-gray-700">Товар доступен для бронирования</label>
                                <p class="text-sm text-gray-500 mt-1">Если отключено, товар не будет отображаться в каталоге</p>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('admin.items') }}"
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                            Отмена
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-md hover:shadow-lg flex items-center">
                            <i class="fas fa-save mr-2"></i>Сохранить изменения
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Функция для предпросмотра главного изображения
        function previewMainImage(input) {
            const preview = document.getElementById('mainImagePreview');
            const previewImage = document.getElementById('mainPreviewImage');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);

                // Отладка в консоль
                console.log('Выбран файл:', input.files[0].name, 'Размер:', (input.files[0].size / 1024).toFixed(2) + 'KB');
            } else {
                preview.classList.add('hidden');
            }
        }

        // Функция для дополнительных изображений
        function handleExtraImages(input) {
            if (input.files && input.files.length > 0) {
                console.log(`Выбрано ${input.files.length} дополнительных изображений:`);
                for (let i = 0; i < input.files.length; i++) {
                    console.log(`  ${i+1}. ${input.files[i].name} (${(input.files[i].size / 1024).toFixed(2)}KB)`);
                }
            }
        }

        // Проверка формы перед отправкой
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const fileInput = document.getElementById('main_image_input');
                    const file = fileInput.files[0];

                    if (file) {
                        const fileSizeMB = file.size / 1024 / 1024;
                        console.log('Отправка формы с файлом:', file.name, 'Размер:', fileSizeMB.toFixed(2) + 'MB');

                        if (fileSizeMB > 2) {
                            e.preventDefault();
                            alert('Файл слишком большой! Максимум 2MB');
                            return false;
                        }
                    } else {
                        console.log('Отправка формы без файла');
                    }

                    return true;
                });
            }
        });
    </script>
@endsection
