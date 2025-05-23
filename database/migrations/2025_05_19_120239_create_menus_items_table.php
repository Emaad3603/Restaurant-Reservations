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
        Schema::create('menus_items', function (Blueprint $table) {
            $table->id('menus_items_id');
            $table->unsignedBigInteger('items_id');
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('currencies_id');
            $table->unsignedBigInteger('menus_id');
            $table->timestamp('created_at')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();

            $table->foreign('menus_id')
                  ->references('menus_id')
                  ->on('menus')
                  ->onDelete('cascade');

            $table->foreign('currencies_id')
                  ->references('currencies_id')
                  ->on('currencies')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus_items');
    }
};
