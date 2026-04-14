<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Tunisia', 'code' => 'TN'],
            ['name' => 'Morocco', 'code' => 'MA'],
            ['name' => 'France', 'code' => 'FR'],
            ['name' => 'Germany', 'code' => 'DE'],
            ['name' => 'Italy', 'code' => 'IT'],
            ['name' => 'Spain', 'code' => 'ES'],
            ['name' => 'United Kingdom', 'code' => 'GB'],
            ['name' => 'Belgium', 'code' => 'BE'],
            ['name' => 'Netherlands', 'code' => 'NL'],
            ['name' => 'Turkey', 'code' => 'TR'],
            ['name' => 'Saudi Arabia', 'code' => 'SA'],
            ['name' => 'United Arab Emirates', 'code' => 'AE'],
            ['name' => 'Qatar', 'code' => 'QA'],
            ['name' => 'Kuwait', 'code' => 'KW'],
            ['name' => 'Oman', 'code' => 'OM'],
            ['name' => 'Bahrain', 'code' => 'BH'],
            ['name' => 'China', 'code' => 'CN'],
            ['name' => 'United States', 'code' => 'US'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code' => $country['code']], $country);
        }

        \Illuminate\Support\Facades\Cache::forget('locations.all');
    }
}
