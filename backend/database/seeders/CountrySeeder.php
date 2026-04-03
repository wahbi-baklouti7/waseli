<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $countries = [
            ['name' => 'Algeria', 'code' => 'DZ'],
            ['name' => 'Bahrain', 'code' => 'BH'],
            ['name' => 'Belgium', 'code' => 'BE'],
            ['name' => 'Canada', 'code' => 'CA'],
            ['name' => 'China', 'code' => 'CN'],
            ['name' => 'Denmark', 'code' => 'DK'],
            ['name' => 'Egypt', 'code' => 'EG'],
            ['name' => 'France', 'code' => 'FR'],
            ['name' => 'Germany', 'code' => 'DE'],
            ['name' => 'Italy', 'code' => 'IT'],
            ['name' => 'Japan', 'code' => 'JP'],
            ['name' => 'Kuwait', 'code' => 'KW'],
            ['name' => 'Libya', 'code' => 'LY'],
            ['name' => 'Mauritania', 'code' => 'MR'],
            ['name' => 'Morocco', 'code' => 'MA'],
            ['name' => 'Netherlands', 'code' => 'NL'],
            ['name' => 'Norway', 'code' => 'NO'],
            ['name' => 'Oman', 'code' => 'OM'],
            ['name' => 'Portugal', 'code' => 'PT'],
            ['name' => 'Qatar', 'code' => 'QA'],
            ['name' => 'Saudi Arabia', 'code' => 'SA'],
            ['name' => 'Spain', 'code' => 'ES'],
            ['name' => 'Sweden', 'code' => 'SE'],
            ['name' => 'Switzerland', 'code' => 'CH'],
            ['name' => 'Tunisia', 'code' => 'TN'],
            ['name' => 'Turkey', 'code' => 'TR'],
            ['name' => 'United Arab Emirates', 'code' => 'AE'],
            ['name' => 'United Kingdom', 'code' => 'GB'],
            ['name' => 'USA', 'code' => 'US'],
        ];

        DB::table('countries')->insert($countries);



    }
}
