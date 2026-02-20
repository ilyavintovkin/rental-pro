<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $conditions = ['excellent', 'good', 'fair', 'poor'];
        $categoryIds = Category::pluck('id')->toArray();

        return [
            'category_id' => $this->faker->randomElement($categoryIds),
            'name' => $this->faker->words(3, true),
            'sku' => strtoupper($this->faker->bothify('???-###')),
            'description' => $this->faker->paragraph(3),
            'price_per_day' => $this->faker->numberBetween(300, 3000),
            'deposit' => $this->faker->numberBetween(2000, 20000),
            'quantity' => $this->faker->numberBetween(1, 10),
            'condition' => $this->faker->randomElement($conditions),
            'specifications' => json_encode([
                'Производитель' => $this->faker->company,
                'Вес' => $this->faker->numberBetween(1, 30) . ' кг',
                'Цвет' => $this->faker->colorName,
            ]),
            'is_available' => $this->faker->boolean(90),
        ];
    }
}
