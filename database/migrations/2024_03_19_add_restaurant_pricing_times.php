<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRestaurantPricingTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert breakfast time slots
        DB::table('restaurant_pricing_times')->insert([
            [
                'restaurant_id' => 14,
                'hotel_id' => 18,
                'company_id' => 3,
                'currency_id' => 9,
                'time' => '07:00:00',
                'meal_type' => 18, // breakfast
                'per_person' => 1,
                'reservation_by_room' => 1,
                'extra_seats' => 10,
                'calculate_price' => 1,
                'created_at' => now()
            ],
            [
                'restaurant_id' => 14,
                'hotel_id' => 18,
                'company_id' => 3,
                'currency_id' => 9,
                'time' => '08:00:00',
                'meal_type' => 18, // breakfast
                'per_person' => 1,
                'reservation_by_room' => 1,
                'extra_seats' => 10,
                'calculate_price' => 1,
                'created_at' => now()
            ],
            [
                'restaurant_id' => 14,
                'hotel_id' => 18,
                'company_id' => 3,
                'currency_id' => 9,
                'time' => '09:00:00',
                'meal_type' => 18, // breakfast
                'per_person' => 1,
                'reservation_by_room' => 1,
                'extra_seats' => 10,
                'calculate_price' => 1,
                'created_at' => now()
            ],
            // Lunch time slots
            [
                'restaurant_id' => 14,
                'hotel_id' => 18,
                'company_id' => 3,
                'currency_id' => 9,
                'time' => '12:00:00',
                'meal_type' => 19, // lunch
                'per_person' => 1,
                'reservation_by_room' => 1,
                'extra_seats' => 10,
                'calculate_price' => 1,
                'created_at' => now()
            ],
            [
                'restaurant_id' => 14,
                'hotel_id' => 18,
                'company_id' => 3,
                'currency_id' => 9,
                'time' => '13:00:00',
                'meal_type' => 19, // lunch
                'per_person' => 1,
                'reservation_by_room' => 1,
                'extra_seats' => 10,
                'calculate_price' => 1,
                'created_at' => now()
            ],
            // Dinner time slots
            [
                'restaurant_id' => 14,
                'hotel_id' => 18,
                'company_id' => 3,
                'currency_id' => 9,
                'time' => '18:00:00',
                'meal_type' => 20, // dinner
                'per_person' => 1,
                'reservation_by_room' => 1,
                'extra_seats' => 10,
                'calculate_price' => 1,
                'created_at' => now()
            ],
            [
                'restaurant_id' => 14,
                'hotel_id' => 18,
                'company_id' => 3,
                'currency_id' => 9,
                'time' => '19:00:00',
                'meal_type' => 20, // dinner
                'per_person' => 1,
                'reservation_by_room' => 1,
                'extra_seats' => 10,
                'calculate_price' => 1,
                'created_at' => now()
            ],
            [
                'restaurant_id' => 14,
                'hotel_id' => 18,
                'company_id' => 3,
                'currency_id' => 9,
                'time' => '20:00:00',
                'meal_type' => 20, // dinner
                'per_person' => 1,
                'reservation_by_room' => 1,
                'extra_seats' => 10,
                'calculate_price' => 1,
                'created_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('restaurant_pricing_times')
            ->where('restaurant_id', 14)
            ->where('hotel_id', 18)
            ->delete();
    }
} 