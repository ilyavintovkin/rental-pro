@extends('layouts.admin')

@section('title', 'Управление товарами')

@section('header-actions')
    <a href="{{ route('admin.items.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center">
        <i class="fas fa-plus mr-2"></i> Новый товар
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b">
            <p class="text-gray-600">Всего товаров: {{ $items->total() }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Товар</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Категория</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Цена</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Кол-во</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-500">#{{ $item->id }}</td>

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-12 w-12 bg-gray-100 rounded-lg overflow-hidden mr-3 flex items-center justify-center">
                                    @if($item->main_image)
                                        <img src="{{ asset('storage/' . $item->main_image) }}"
                                             alt="{{ $item->name }}"
                                             class="h-full w-full object-cover"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-full w-full flex items-center justify-center\'><i class=\'fas fa-box text-gray-400\'></i></div>';">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->sku }}</div>
                                </div>
                            </div>
                        </td>

        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded" style="background-color: {{ $item->category->color }}20; color: {{ $item->category->color }}">
                                {{ $item->category->name }}
                            </span>
        </td>

        <td class="px-6 py-4 font-medium">{{ number_format($item->price_per_day, 0, ',', ' ') }} ₽</td>

        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $item->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->quantity }} шт.
                            </span>
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex space-x-2">
                <!-- Редактировать -->
                <a href="{{ route('admin.items.edit', $item) }}"
                   class="text-indigo-600 hover:text-indigo-800" title="Редактировать">
                    <i class="fas fa-edit"></i>
                </a>

                <!-- Удалить -->
                <form action="{{ route('admin.items.destroy', $item) }}"
                      method="POST"
                      onsubmit="return confirm('Удалить товар?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800" title="Удалить">
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

    @if($items->hasPages())
        <div class="bg-white px-6 py-4 border-t">
            {{ $items->links() }}
        </div>
        @endif
        </div>
        @endsection
