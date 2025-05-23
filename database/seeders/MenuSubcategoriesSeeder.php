<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSubcategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            [
                'menu_subcategories_id' => 1,
                'company_id' => 3,
                'menu_categories_id' => 1,
                'label' => 'Hot Appetizers',
                'created_by' => 'system'
            ],
            [
                'menu_subcategories_id' => 2,
                'company_id' => 3,
                'menu_categories_id' => 1,
                'label' => 'Cold Appetizers',
                'created_by' => 'system'
            ],
            [
                'menu_subcategories_id' => 3,
                'company_id' => 3,
                'menu_categories_id' => 2,
                'label' => 'Seafood',
                'created_by' => 'system'
            ],
            [
                'menu_subcategories_id' => 4,
                'company_id' => 3,
                'menu_categories_id' => 2,
                'label' => 'Meat',
                'created_by' => 'system'
            ],
            [
                'menu_subcategories_id' => 5,
                'company_id' => 3,
                'menu_categories_id' => 3,
                'label' => 'Cakes',
                'created_by' => 'system'
            ],
            [
                'menu_subcategories_id' => 6,
                'company_id' => 3,
                'menu_categories_id' => 4,
                'label' => 'Hot Drinks',
                'created_by' => 'system'
            ],
            [
                'menu_subcategories_id' => 7,
                'company_id' => 3,
                'menu_categories_id' => 4,
                'label' => 'Cold Drinks',
                'created_by' => 'system'
            ]
        ];

        foreach ($subcategories as $subcategory) {
            DB::table('menu_subcategories')->updateOrInsert(
                ['menu_subcategories_id' => $subcategory['menu_subcategories_id']],
                $subcategory
            );

            // Create translations for each subcategory
            DB::table('menu_subcategories_translation')->updateOrInsert(
                [
                    'menu_subcategories_id' => $subcategory['menu_subcategories_id'],
                    'language_code' => 'en'
                ],
                [
                    'menu_subcategories_id' => $subcategory['menu_subcategories_id'],
                    'name' => $subcategory['label'],
                    'language_code' => 'en'
                ]
            );
        }
    }
} 