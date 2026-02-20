<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'color',
        'sort_order',
        'is_active'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="400" height="400">
            <rect width="100" height="100" fill="#4f46e5" opacity="0.1" rx="15"/>
            <text x="50" y="55" text-anchor="middle" font-size="40" font-family="Arial" fill="#4f46e5">' . $icon . '</text>
            <text x="50" y="85" text-anchor="middle" font-size="10" font-family="Arial" fill="#666">' . $this->name . '</text>
        </svg>
    ');
    }
}
