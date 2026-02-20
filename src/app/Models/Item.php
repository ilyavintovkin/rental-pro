<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'price_per_day',
        'deposit',
        'quantity',
        'is_active',
        'condition',
        'images',
        'specifications',
        'is_available',
        'main_image',
    ];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'is_available' => 'boolean',
        'price_per_day' => 'decimal:2',
        'deposit' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailableForDates($startDate, $endDate)
    {
        // Проверяем активные бронирования на пересекающиеся даты
        $conflictingBookingsCount = $this->bookings()
            ->whereIn('status', ['confirmed', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                // Проверяем пересечение периодов
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q2) use ($startDate, $endDate) {
                            $q2->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                });
            })
            ->count();

        // Проверяем, что после вычитания активных бронирований осталось хотя бы 1
        return $this->is_available && ($this->quantity - $conflictingBookingsCount) > 0;
    }

    public function getMainImageUrlAttribute(): string
    {
        if ($this->main_image) {
            // Простой и надежный способ для public disk
            return asset('storage/' . $this->main_image);
        }

        return '';
    }

    public function getImageUrlsAttribute(): array
    {
        $urls = [];

        // Добавляем главное изображение
        if ($this->main_image) {
            $urls[] = asset('storage/' . $this->main_image);
        }

        // Добавляем дополнительные изображения
        if ($this->images) {
            $images = json_decode($this->images, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    $urls[] = asset('storage/' . $image);
                }
            }
        }

        return $urls;
    }

    /**
     * Получить доступное количество с учетом активных бронирований
     */
    public function getAvailableQuantityAttribute()
    {
        $activeBookingsCount = $this->bookings()
            ->whereIn('status', ['confirmed', 'active'])
            ->where(function ($query) {
                $query->where('start_date', '<=', Carbon::now())
                    ->where('end_date', '>=', Carbon::now());
            })
            ->count();

        return max(0, $this->quantity - $activeBookingsCount);
    }

    /**
     * Проверить, доступен ли товар для бронирования
     */
    public function getIsAvailableForBookingAttribute()
    {
        return $this->is_available && $this->available_quantity > 0;
    }
}
