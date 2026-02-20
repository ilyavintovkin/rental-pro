@extends('layouts.admin')

@section('title', 'Создать категорию')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Создание новой категории</h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Название -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Название категории *</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Описание -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                        <textarea name="description"
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <!-- ТОЛЬКО цвет, убираем иконку -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Цвет категории</label>
                        <input type="color"
                               name="color"
                               value="{{ old('color', '#3b82f6') }}"
                               class="w-full h-12 rounded-lg cursor-pointer">
                        <p class="mt-1 text-sm text-gray-500">HEX цвет для фона категории</p>
                    </div>

                    <!-- Изображение -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Изображение категории *</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition">
                            <div class="text-gray-400 text-4xl mb-3">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <p class="text-gray-600 mb-2">Перетащите изображение или нажмите для выбора</p>
                            <input type="file"
                                   name="image"
                                   id="imageInput"
                                   accept="image/*"
                                   required
                                   class="hidden">
                            <label for="imageInput"
                                   class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                                Выбрать файл
                            </label>
                            <p class="mt-2 text-sm text-gray-500">Рекомендуется 800×600px, JPG или PNG</p>
                        </div>
                        <div id="imagePreview" class="mt-3 hidden">
                            <img id="previewImage" class="max-w-xs rounded-lg shadow">
                        </div>
                        @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Сортировка и статус -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Порядок сортировки</label>
                            <input type="number"
                                   name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">Активная категория</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">Будет отображаться на сайте</p>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('admin.categories') }}"
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Отмена
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>Создать категорию
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
@endsection
