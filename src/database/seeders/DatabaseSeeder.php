<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Booking;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==================== 1. ПРАВИЛЬНОЕ ОТКЛЮЧЕНИЕ ВНЕШНИХ КЛЮЧЕЙ ====================
        // Для MySQL нужно использовать DB::statement
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Удаляем в ПРАВИЛЬНОМ ПОРЯДКЕ
        DB::table('bookings')->truncate();
        DB::table('items')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();

        // Включаем проверку обратно
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ДАЛЕЕ ВАШ КОД БЕЗ ИЗМЕНЕНИЙ...
        // ==================== 2. ПОЛЬЗОВАТЕЛИ ====================
        // Сначала создаём пользователей
        $users = [
            [
                'name' => 'Администратор',
                'email' => 'admin@rental.ru',
                'email_verified_at' => now(),
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Иван Петров',
                'email' => 'ivan@mail.ru',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Мария Сидорова',
                'email' => 'maria@mail.ru',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Алексей Козлов',
                'email' => 'alex@mail.ru',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // ==================== 3. КАТЕГОРИИ ====================
        $categories = [
            [
                'name' => 'Велосипеды',
                'slug' => 'bicycles',
                'description' => 'Горные, шоссейные и городские велосипеды',
                'image' => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&h=600&fit=crop',
                'icon' => '🚴',
                'color' => '#3b82f6',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Лыжи и сноуборды',
                'slug' => 'ski-snowboard',
                'description' => 'Горнолыжное снаряжение для зимнего отдыха',
                'image' => 'https://images.unsplash.com/photo-1533873987715-b1e2f9a6c51b?w=800&h=600&fit=crop',
                'icon' => '🎿',
                'color' => '#06b6d4',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Туристическое снаряжение',
                'slug' => 'camping',
                'description' => 'Палатки, спальники, рюкзаки и другое снаряжение',
                'image' => 'https://images.unsplash.com/photo-1504851149312-7a075b496cc7?w=800&h=600&fit=crop',
                'icon' => '🏕️',
                'color' => '#10b981',
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Водный спорт',
                'slug' => 'water-sports',
                'description' => 'Каяки, SUP-доски, гидрокостюмы',
                'image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop',
                'icon' => '🚤',
                'color' => '#6366f1',
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Экстрим и активность',
                'slug' => 'extreme',
                'description' => 'Скейты, гироскутеры, оборудование для скалолазания',
                'image' => 'https://images.unsplash.com/photo-1520045892732-304bc3ac5d8e?w=800&h=600&fit=crop',
                'icon' => '🛹',
                'color' => '#8b5cf6',
                'sort_order' => 5,
                'is_active' => true
            ],
            [
                'name' => 'Фото и видео техника',
                'slug' => 'photo-video',
                'description' => 'Профессиональные камеры, объективы, стабилизаторы',
                'image' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&h=600&fit=crop',
                'icon' => '📷',
                'color' => '#f59e0b',
                'sort_order' => 6,
                'is_active' => true
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // ==================== 3. ТОВАРЫ ====================
        // Изображения для товаров (разные для разнообразия)
        $itemImages = [
            [
                'main' => 'https://images.unsplash.com/photo-1576435728678-68d0fbf94e91?w=800&h=600&fit=crop',
                'all' => [
                    'https://images.unsplash.com/photo-1576435728678-68d0fbf94e91?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=800&h=600&fit=crop'
                ]
            ],
            [
                'main' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&h=600&fit=crop',
                'all' => [
                    'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=800&h=600&fit=crop',
                ]
            ],
            [
                'main' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=600&fit=crop',
                'all' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&h=600&fit=crop',
                ]
            ],
            [
                'main' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=800&h=600&fit=crop',
                'all' => [
                    'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=800&h=600&fit=crop',
                ]
            ],
        ];

        $items = [
            // Велосипеды
            [
                'category_id' => 1,
                'name' => 'Горный велосипед Trek Marlin 7',
                'sku' => 'BIKE-001',
                'description' => 'Современный горный велосипед с алюминиевой рамой, гидравлическими тормозами и 21 скоростью. Идеален для трейлов и лесных дорог.',
                'price_per_day' => 1500,
                'deposit' => 10000,
                'quantity' => 3,
                'condition' => 'excellent',
                'main_image' => $itemImages[0]['main'],
                'images' => json_encode($itemImages[0]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Горный (MTB)',
                    'Размер рамы' => '19 дюймов',
                    'Материал рамы' => 'Алюминиевый сплав',
                    'Количество скоростей' => '21',
                    'Тормоза' => 'Гидравлические дисковые',
                    'Вес' => '13.5 кг',
                    'Рост пользователя' => '170-185 см'
                ]),
                'is_available' => true
            ],
            [
                'category_id' => 1,
                'name' => 'Шоссейный велосипед Cannondale CAAD12',
                'sku' => 'BIKE-002',
                'description' => 'Легкий и быстрый шоссейный велосипед для асфальта. Карбоновая вилка, группа Shimano 105, вес всего 8.5 кг.',
                'price_per_day' => 2200,
                'deposit' => 15000,
                'quantity' => 2,
                'condition' => 'excellent',
                'main_image' => $itemImages[1]['main'],
                'images' => json_encode($itemImages[1]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Шоссейный',
                    'Размер рамы' => '56 см',
                    'Материал рамы' => 'Алюминий с карбоновой вилкой',
                    'Группа компонентов' => 'Shimano 105',
                    'Количество скоростей' => '22',
                    'Вес' => '8.5 кг',
                    'Рост пользователя' => '175-185 см'
                ]),
                'is_available' => true
            ],
            [
                'category_id' => 1,
                'name' => 'Городской велосипед Electra Loft',
                'sku' => 'BIKE-003',
                'description' => 'Комфортный городской велосипед с вертикальной посадкой. Идеален для прогулок по городу.',
                'price_per_day' => 800,
                'deposit' => 5000,
                'quantity' => 5,
                'condition' => 'good',
                'main_image' => $itemImages[2]['main'],
                'images' => json_encode($itemImages[2]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Городской',
                    'Размер рамы' => 'Универсальный',
                    'Материал рамы' => 'Сталь',
                    'Количество скоростей' => '7',
                    'Тормоза' => 'Ободные V-brake',
                    'Вес' => '16 кг',
                    'Рост пользователя' => '160-190 см'
                ]),
                'is_available' => true
            ],

            // Лыжи и сноуборды
            [
                'category_id' => 2,
                'name' => 'Горные лыжи Rossignol Experience 84',
                'sku' => 'SKI-001',
                'description' => 'Универсальные горные лыжи для подготовленных склонов. Длина 170 см, радиус 14 м.',
                'price_per_day' => 1200,
                'deposit' => 8000,
                'quantity' => 4,
                'condition' => 'excellent',
                'main_image' => $itemImages[0]['main'],
                'images' => json_encode($itemImages[0]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Горные лыжи',
                    'Длина' => '170 см',
                    'Радиус поворота' => '14 м',
                    'Ширина талии' => '84 мм',
                    'Уровень катания' => 'Средний'
                ]),
                'is_available' => true
            ],
            [
                'category_id' => 2,
                'name' => 'Сноуборд Burton Custom Flying V',
                'sku' => 'SNOW-001',
                'description' => 'Легендарный сноуборд для фристайла и фрирайда. Подходит для всех типов снега.',
                'price_per_day' => 1400,
                'deposit' => 9000,
                'quantity' => 3,
                'condition' => 'good',
                'main_image' => $itemImages[1]['main'],
                'images' => json_encode($itemImages[1]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Сноуборд',
                    'Длина' => '158 см',
                    'Ширина' => '25.5 см',
                    'Прогиб' => 'Flying V (гибридный)',
                    'Уровень катания' => 'Средний-продвинутый',
                    'Крепления' => 'Покупаются отдельно'
                ]),
                'is_available' => true
            ],

            // Туристическое снаряжение
            [
                'category_id' => 3,
                'name' => 'Палатка Tramp 4-Season',
                'sku' => 'CAMP-001',
                'description' => '4-местная палатка для всех сезонов. Водонепроницаемость 5000 мм, алюминиевые дуги.',
                'price_per_day' => 600,
                'deposit' => 4000,
                'quantity' => 6,
                'condition' => 'good',
                'main_image' => $itemImages[2]['main'],
                'images' => json_encode($itemImages[2]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Палатка 4-сезонная',
                    'Вместимость' => '4 человека',
                    'Вес' => '4.2 кг',
                    'Водонепроницаемость' => '5000 мм',
                    'Материал' => 'Полиэстер 210T',
                    'Размер' => '240×240×130 см'
                ]),
                'is_available' => true
            ],
            [
                'category_id' => 3,
                'name' => 'Спальник Deuter Sleepy Cat',
                'sku' => 'CAMP-002',
                'description' => 'Теплый спальник для летнего сезона. Комфортная температура +5°C.',
                'price_per_day' => 300,
                'deposit' => 2000,
                'quantity' => 10,
                'condition' => 'excellent',
                'main_image' => $itemImages[3]['main'],
                'images' => json_encode($itemImages[3]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Спальник',
                    'Температура комфорта' => '+5°C',
                    'Температура экстрима' => '-5°C',
                    'Вес' => '1.2 кг',
                    'Материал' => 'Полиэстер',
                    'Размер' => '215×80 см'
                ]),
                'is_available' => true
            ],
            [
                'category_id' => 3,
                'name' => 'Рюкзак Osprey Atmos 65',
                'sku' => 'CAMP-003',
                'description' => 'Вентилируемый рюкзак для многодневных походов. Объем 65 литров.',
                'price_per_day' => 400,
                'deposit' => 3000,
                'quantity' => 8,
                'condition' => 'excellent',
                'main_image' => $itemImages[0]['main'],
                'images' => json_encode($itemImages[0]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Туристический рюкзак',
                    'Объем' => '65 литров',
                    'Вес' => '1.9 кг',
                    'Система вентиляции' => 'Anti-Gravity',
                    'Рост пользователя' => 'M/L (170-195 см)'
                ]),
                'is_available' => true
            ],

            // Водный спорт
            [
                'category_id' => 4,
                'name' => 'SUP-доска Red Paddle Co 10.6',
                'sku' => 'WATER-001',
                'description' => 'Надувная SUP-доска для начинающих и любителей. В комплекте весло и насос.',
                'price_per_day' => 900,
                'deposit' => 7000,
                'quantity' => 4,
                'condition' => 'good',
                'main_image' => $itemImages[1]['main'],
                'images' => json_encode($itemImages[1]['all']),
                'specifications' => json_encode([
                    'Тип' => 'SUP-доска надувная',
                    'Длина' => '10.6 футов (323 см)',
                    'Ширина' => '32 дюйма (81 см)',
                    'Толщина' => '6 дюймов (15 см)',
                    'Вес' => '10.5 кг',
                    'Грузоподъемность' => '120 кг'
                ]),
                'is_available' => true
            ],

            // Экстрим
            [
                'category_id' => 5,
                'name' => 'Электросамокат Xiaomi Mi Pro 2',
                'sku' => 'EXT-001',
                'description' => 'Мощный электросамокат с запасом хода 45 км. Максимальная скорость 25 км/ч.',
                'price_per_day' => 700,
                'deposit' => 25000,
                'quantity' => 2,
                'condition' => 'excellent',
                'main_image' => $itemImages[2]['main'],
                'images' => json_encode($itemImages[2]['all']),
                'specifications' => json_encode([
                    'Тип' => 'Электросамокат',
                    'Мощность двигателя' => '300 Вт',
                    'Максимальная скорость' => '25 км/ч',
                    'Запас хода' => '45 км',
                    'Вес' => '14.2 кг',
                    'Время зарядки' => '8 часов'
                ]),
                'is_available' => true
            ]
        ];

        foreach ($items as $itemData) {
            Item::create($itemData);
        }

        // ==================== 4. БРОНИРОВАНИЯ ====================
        $bookings = [
            [
                'user_id' => 2, // Иван
                'item_id' => 1, // Велосипед Trek
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->subDays(8),
                'days' => 3,
                'daily_price' => 1500,
                'total_price' => 4500,
                'deposit_amount' => 10000,
                'status' => 'completed',
                'notes' => 'Все прошло отлично, клиент доволен'
            ],
            [
                'user_id' => 3, // Мария
                'item_id' => 4, // Лыжи
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(5),
                'days' => 4,
                'daily_price' => 1200,
                'total_price' => 4800,
                'deposit_amount' => 8000,
                'status' => 'confirmed',
                'notes' => 'Предоплата внесена'
            ],
            [
                'user_id' => 2, // Иван
                'item_id' => 7, // Палатка
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(10),
                'days' => 4,
                'daily_price' => 600,
                'total_price' => 2400,
                'deposit_amount' => 4000,
                'status' => 'pending',
                'notes' => 'Ждет подтверждения'
            ]
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
        }

        // ==================== 5. ИТОГИ ====================
        $this->command->info('========================================');
        $this->command->info('✅ БАЗА ДАННЫХ УСПЕШНО ЗАПОЛНЕНА!');
        $this->command->info('========================================');
        $this->command->info('👥 ПОЛЬЗОВАТЕЛИ:');
        $this->command->info('   👤 Администратор: admin@rental.ru / admin123');
        $this->command->info('   👤 Иван Петров: ivan@mail.ru / password');
        $this->command->info('   👤 Мария Сидорова: maria@mail.ru / password');
        $this->command->info('   👤 Алексей Козлов: alex@mail.ru / password');
        $this->command->info('');
        $this->command->info('📦 КАТЕГОРИИ: ' . Category::count() . ' шт.');
        $this->command->info('🛒 ТОВАРЫ: ' . Item::count() . ' шт.');
        $this->command->info('📅 БРОНИРОВАНИЯ: ' . Booking::count() . ' шт.');
        $this->command->info('');
        $this->command->info('🌐 Сайт: http://localhost:8000');
        $this->command->info('📊 phpMyAdmin: http://localhost:8080');
        $this->command->info('========================================');
    }
}
