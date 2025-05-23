<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('currencies')->updateOrInsert(
            ['currencies_id' => 1],
            [
                'currencies_id' => 1,
                'company_id' => 1,
                'currency_code' => 'USD',
                'name' => 'US Dollar',
                'currency_symbol' => '$',
                'exchange_rate' => 1.000000,
                'active' => 1,
                'created_by' => 'system'
            ]
        );
    }
} 