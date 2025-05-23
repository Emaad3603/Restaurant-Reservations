<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'items_id' => 1,
                'company_id' => 3,
                'menu_categories_id' => 1,
                'menu_subcategories_id' => 1,
                'label' => 'Bruschetta',
                'created_by' => 'system'
            ],
            [
                'items_id' => 2,
                'company_id' => 3,
                'menu_categories_id' => 1,
                'menu_subcategories_id' => 2,
                'label' => 'Caesar Salad',
                'created_by' => 'system'
            ],
            [
                'items_id' => 3,
                'company_id' => 3,
                'menu_categories_id' => 2,
                'menu_subcategories_id' => 3,
                'label' => 'Grilled Salmon',
                'created_by' => 'system'
            ],
            [
                'items_id' => 4,
                'company_id' => 3,
                'menu_categories_id' => 2,
                'menu_subcategories_id' => 4,
                'label' => 'Beef Tenderloin',
                'created_by' => 'system'
            ],
            [
                'items_id' => 5,
                'company_id' => 3,
                'menu_categories_id' => 3,
                'menu_subcategories_id' => 5,
                'label' => 'Chocolate Cake',
                'created_by' => 'system'
            ],
            [
                'items_id' => 6,
                'company_id' => 3,
                'menu_categories_id' => 4,
                'menu_subcategories_id' => 6,
                'label' => 'Espresso',
                'created_by' => 'system'
            ],
            [
                'items_id' => 7,
                'company_id' => 3,
                'menu_categories_id' => 4,
                'menu_subcategories_id' => 7,
                'label' => 'Fresh Orange Juice',
                'created_by' => 'system'
            ]
        ];

        foreach ($items as $item) {
            DB::table('items')->updateOrInsert(
                ['items_id' => $item['items_id']],
                $item
            );

            // Create translations for each item
            DB::table('items_translation')->updateOrInsert(
                [
                    'items_id' => $item['items_id'],
                    'language_code' => 'en'
                ],
                [
                    'items_id' => $item['items_id'],
                    'name' => $item['label'],
                    'description' => 'Delicious ' . $item['label'],
                    'language_code' => 'en'
                ]
            );

            // Link items to menus
            $menus = DB::table('menus')->get();
            foreach ($menus as $menu) {
                DB::table('menus_items')->updateOrInsert(
                    [
                        'menus_id' => $menu->menus_id,
                        'items_id' => $item['items_id']
                    ],
                    [
                        'menus_id' => $menu->menus_id,
                        'items_id' => $item['items_id'],
                        'price' => '10.00',
                        'currencies_id' => 9,
                        'created_by' => 'system'
                    ]
                );
            }
        }
    }
} 