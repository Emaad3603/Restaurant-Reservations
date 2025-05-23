<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealTypesSeeder extends Seeder
{
    public function run(): void
    {
        $mealTypes = [
            [
                'meal_types_id' => 1,
                'company_id' => 3,
                'label' => 'Breakfast',
                'active' => 1,
                'created_by' => 'system'
            ],
            [
                'meal_types_id' => 2,
                'company_id' => 3,
                'label' => 'Lunch',
                'active' => 1,
                'created_by' => 'system'
            ],
            [
                'meal_types_id' => 3,
                'company_id' => 3,
                'label' => 'Dinner',
                'active' => 1,
                'created_by' => 'system'
            ]
        ];

        foreach ($mealTypes as $mealType) {
            DB::table('meal_types')->updateOrInsert(
                ['meal_types_id' => $mealType['meal_types_id']],
                $mealType
            );

            // Create translations for each meal type
            DB::table('meal_types_translation')->updateOrInsert(
                [
                    'meal_types_id' => $mealType['meal_types_id'],
                    'language_code' => 'en'
                ],
                [
                    'meal_types_id' => $mealType['meal_types_id'],
                    'name' => $mealType['label'],
                    'language_code' => 'en'
                ]
            );
        }
    }
} 