<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurant_meal_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('meal_type_id');
            $table->timestamps();

            $table->foreign('restaurant_id')
                ->references('restaurants_id')
                ->on('restaurants')
                ->onDelete('cascade');

            $table->foreign('meal_type_id')
                ->references('meal_type_id')
                ->on('meal_types')
                ->onDelete('cascade');

            $table->unique(['restaurant_id', 'meal_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_meal_types');
    }
}; 