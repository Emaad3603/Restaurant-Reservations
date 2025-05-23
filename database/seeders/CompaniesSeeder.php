<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('companies')->updateOrInsert(
            ['company_id' => 1],
            [
                'company_id' => 1,
                'company_name' => 'Test Hotel Group',
                'currency_id' => 1,
                'company_uuid' => '6825c61d5f9dc',
                'logo_url' => 'https://cdn.pixabay.com/photo/2018/05/05/19/28/hot...'
            ]
        );
    }
} 