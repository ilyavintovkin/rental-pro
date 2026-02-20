<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Для категорий - добавляем icon и color
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('image');
            $table->string('color')->default('#4f46e5')->after('icon');
        });

        // Для товаров - добавляем main_image (поле images УЖЕ ЕСТЬ в таблице!)
        Schema::table('items', function (Blueprint $table) {
            $table->string('main_image')->nullable()->after('specifications');
            // Поле images уже есть (мы видели в tinker), его НЕ добавляем!
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('main_image');
            // Поле images не удаляем, оно было изначально!
        });
    }
};
