@extends('layouts.admin')

@section('title', 'Управление категориями')
@section('subtitle', 'Добавление, редактирование и удаление категорий')

@section('header-actions')
    <a href="{{ route('admin.categories.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center">
        <i class="fas fa-plus mr-2"></i> Новая категория
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Номер</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Иконка & Цвет</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Товаров</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $category)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $category->id }}</td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($category->image)
                                    <div class="h-16 w-16 rounded-lg overflow-hidden mr-3 bg-gray-100 flex items-center justify-center">
                                        <img src="{{ $category->image }}" alt="{{ $category->name }}"
                                             class="max-h-full max-w-full object-contain">
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $category->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <div class="h-6 w-6 rounded-full border" style="background-color: {{ $category->color ?? '#4f46e5' }}"></div>
                                <span class="text-sm text-gray-600">{{ $category->color ?? '#4f46e5' }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">
                            {{ $category->items->count() }} товаров
                        </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->is_active)
                                <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                            Активна
                        </span>
                            @else
                                <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">
                            Неактивна
                        </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- Редактировать -->
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-indigo-600 hover:text-indigo-900" title="Редактировать">
                                    <i class="fas fa-edit"></i>
                                </a>


                                <!-- Удалить -->
                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST"
                                      onsubmit="return confirm('Удалить категорию?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Удалить">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="bg-white px-6 py-4 border-t">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    @if($categories->isEmpty())
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Нет категорий</h3>
            <p class="text-gray-600 mb-6">Создайте первую категорию для вашего инвентаря</p>
            <a href="{{ route('admin.categories.create') }}"
               class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Создать категорию
            </a>
        </div>
    @endif
@endsection
