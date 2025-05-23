<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'menus_id' => 1,
                'company_id' => 3,
                'label' => 'Breakfast Menu',
                'created_by' => 'system'
            ],
            [
                'menus_id' => 2,
                'company_id' => 3,
                'label' => 'Lunch Menu',
                'created_by' => 'system'
            ],
            [
                'menus_id' => 3,
                'company_id' => 3,
                'label' => 'Dinner Menu',
                'created_by' => 'system'
            ]
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->updateOrInsert(
                ['menus_id' => $menu['menus_id']],
                $menu
            );

            // Link items to menus based on meal type
            $items = DB::table('items')->get();
            foreach ($items as $item) {
                DB::table('menus_items')->updateOrInsert(
                    [
                        'menus_id' => $menu['menus_id'],
                        'items_id' => $item->items_id
                    ],
                    [
                        'menus_id' => $menu['menus_id'],
                        'items_id' => $item->items_id,
                        'currencies_id' => 9,
                        'created_by' => 'system'
                    ]
                );
            }
        }
    }
} 