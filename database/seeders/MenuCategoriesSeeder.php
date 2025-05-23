<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'menu_categories_id' => 1,
                'company_id' => 3,
                'label' => 'Appetizers',
                'created_by' => 'system'
            ],
            [
                'menu_categories_id' => 2,
                'company_id' => 3,
                'label' => 'Main Courses',
                'created_by' => 'system'
            ],
            [
                'menu_categories_id' => 3,
                'company_id' => 3,
                'label' => 'Desserts',
                'created_by' => 'system'
            ],
            [
                'menu_categories_id' => 4,
                'company_id' => 3,
                'label' => 'Beverages',
                'created_by' => 'system'
            ]
        ];

        foreach ($categories as $category) {
            DB::table('menu_categories')->updateOrInsert(
                ['menu_categories_id' => $category['menu_categories_id']],
                $category
            );

            // Create translations for each category
            DB::table('menu_categories_translation')->updateOrInsert(
                [
                    'menu_categories_id' => $category['menu_categories_id'],
                    'language_code' => 'en'
                ],
                [
                    'menu_categories_id' => $category['menu_categories_id'],
                    'name' => $category['label'],
                    'language_code' => 'en'
                ]
            );
        }
    }
} 