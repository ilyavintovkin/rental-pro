<?php

use App\Http\Controllers\TelegramAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [CatalogController::class, 'home'])->name('home');

// Каталог инвентаря
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{item}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/category/{category:slug}', [CatalogController::class, 'category'])->name('catalog.category');

// Дашборд
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Маршруты аутентификации Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Бронирование (только для авторизованных)
Route::middleware('auth')->group(function () {
    Route::post('/items/{item}/book', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/profile/bookings', [BookingController::class, 'index'])->name('profile.bookings');
    Route::delete('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
});

// Админ-панель
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        // Дашборд
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Бронирования (ДОБАВЬТЕ ЭТИ МАРШРУТЫ)
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{booking}/details', [AdminController::class, 'bookingDetails'])->name('bookings.details');
        Route::put('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');

        // Категории
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        // Товары
        Route::get('/items', [AdminController::class, 'items'])->name('items');
        Route::get('/items/create', [AdminController::class, 'createItem'])->name('items.create');
        Route::post('/items', [AdminController::class, 'storeItem'])->name('items.store');
        Route::get('/items/{item}/edit', [AdminController::class, 'editItem'])->name('items.edit');
        Route::put('/items/{item}', [AdminController::class, 'updateItem'])->name('items.update');
        Route::delete('/items/{item}', [AdminController::class, 'destroyItem'])->name('items.destroy');
    });
});
Route::post('/auth/telegram/callback', [TelegramAuthController::class, 'callback'])->name('telegram.callback');

require __DIR__.'/auth.php';
