<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description');
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('deposit', 10, 2)->nullable();
            $table->integer('quantity')->default(1);
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->json('images')->nullable();
            $table->json('specifications')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
