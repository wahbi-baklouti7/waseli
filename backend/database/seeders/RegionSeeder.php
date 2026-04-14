<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'TN' => [
                'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès', 'Gafsa', 'Jendouba',
                'Kairouan', 'Kasserine', 'Kébili', 'Kef', 'Mahdia', 'Manouba', 'Médenine',
                'Monastir', 'Nabeul', 'Sfax', 'Sidi Bouzid', 'Siliana', 'Sousse',
                'Tataouine', 'Tozeur', 'Tunis', 'Zaghouan'
            ],
            'MA' => [
                'Tanger-Tétouan-Al Hoceïma', 'L\'Oriental', 'Fès-Meknès', 'Rabat-Salé-Kénitra',
                'Béni Mellal-Khénifra', 'Casablanca-Settat', 'Marrakech-Safi', 'Drâa-Tafilalet',
                'Souss-Massa', 'Guelmim-Oued Noun', 'Laâyoune-Sakia El Hamra', 'Dakhla-Oued Ed-Dahab'
            ],
            'FR' => [
                'Auvergne-Rhône-Alpes', 'Bourgogne-Franche-Comté', 'Bretagne', 'Centre-Val de Loire',
                'Corse', 'Grand Est', 'Hauts-de-France', 'Île-de-France', 'Normandie',
                'Nouvelle-Aquitaine', 'Occitanie', 'Pays de la Loire', 'Provence-Alpes-Côte d\'Azur',
                'Paris', 'Lyon', 'Marseille', 'Nice', 'Toulouse', 'Bordeaux', 'Lille', 'Strasbourg', 'Nantes', 'Montpellier'
            ],
            'DE' => [
                'Baden-Württemberg', 'Bavaria', 'Berlin', 'Brandenburg', 'Bremen', 'Hamburg',
                'Hesse', 'Lower Saxony', 'Mecklenburg-Vorpommern', 'North Rhine-Westphalia',
                'Rhineland-Palatinate', 'Saarland', 'Saxony', 'Saxony-Anhalt', 'Schleswig-Holstein', 'Thuringia',
                'Munich', 'Frankfurt', 'Cologne', 'Stuttgart', 'Düsseldorf', 'Dortmund', 'Leipzig'
            ],
            'IT' => [
                'Abruzzo', 'Basilicata', 'Calabria', 'Campania', 'Emilia-Romagna', 'Friuli-Venezia Giulia',
                'Lazio', 'Liguria', 'Lombardy', 'Marche', 'Molise', 'Piedmont', 'Puglia', 'Sardinia',
                'Sicily', 'Tuscany', 'Trentino-South Tyrol', 'Umbria', 'Aosta Valley', 'Veneto'
            ],
            'ES' => [
                'Andalusia', 'Aragon', 'Asturias', 'Balearic Islands', 'Basque Country', 'Canary Islands',
                'Cantabria', 'Castile and León', 'Castile-La Mancha', 'Catalonia', 'Extremadura',
                'Galicia', 'Madrid', 'Murcia', 'Navarre', 'La Rioja', 'Valencia'
            ],
            'GB' => [
                'England', 'Scotland', 'Wales', 'Northern Ireland'
            ],
            'SA' => [
                'Riyadh', 'Makkah', 'Madinah', 'Al-Qassim', 'Eastern Province', 'Asir', 'Tabuk',
                'Ha\'il', 'Northern Borders', 'Jazan', 'Najran', 'Al-Bahah', 'Al-Jouf'
            ],
            'AE' => [
                'Abu Dhabi', 'Dubai', 'Sharjah', 'Ajman', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'
            ],
            'TR' => [
                'Istanbul', 'Ankara', 'Izmir', 'Bursa', 'Antalya', 'Adana', 'Konya', 'Gaziantep', 'Mersin', 'Diyarbakir'
            ],
            'US' => [
                'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware',
                'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky',
                'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi',
                'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico',
                'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania',
                'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
                'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming',
                'New York City', 'Los Angeles', 'Chicago', 'Houston', 'Miami', 'San Francisco'
            ],
            'CN' => [
                'Guangdong', 'Henan', 'Shandong', 'Sichuan', 'Jiangsu', 'Hebei', 'Hunan', 'Anhui', 'Hubei',
                'Zhejiang', 'Guangxi', 'Yunnan', 'Jiangxi', 'Liaoning', 'Fujian', 'Shaanxi', 'Heilongjiang',
                'Shanxi', 'Guizhou', 'Gansu', 'Hainan', 'Qinghai', 'Taiwan', 'Beijing', 'Tianjin', 'Shanghai', 'Chongqing',
                'Guangzhou', 'Shenzhen', 'Dongguan', 'Ningbo', 'Xiamen'
            ],
            'QA' => [
                'Al Shamal', 'Al Khor', 'Al-Shahaniya', 'Umm Salal', 'Al Daayen', 'Doha', 'Al Rayyan', 'Al Wakrah'
            ],
            'KW' => [
                'Al Asimah', 'Hawalli', 'Farwaniya', 'Ahmadi', 'Jahra', 'Mubarak Al-Kabeer'
            ],
            'OM' => [
                'Muscat', 'Musandam', 'Dhofar', 'Al Buraimi', 'Ad Dakhiliyah', 'Ad Dhahirah',
                'Al Batinah North', 'Al Batinah South', 'Ash Sharqiyah North', 'Ash Sharqiyah South', 'Al Wusta'
            ],
            'BH' => [
                'Capital', 'Muharraq', 'Northern', 'Southern'
            ],
            'NL' => [
                'Drenthe', 'Flevoland', 'Friesland', 'Gelderland', 'Groningen', 'Limburg',
                'North Brabant', 'North Holland', 'Overijssel', 'Utrecht', 'Zeeland', 'South Holland'
            ],
            'BE' => [
                'Flanders', 'Wallonia', 'Brussels-Capital'
            ],
        ];

        foreach ($data as $countryCode => $regions) {
            $country = Country::where('code', $countryCode)->first();

            if ($country) {
                foreach ($regions as $name) {
                    Region::updateOrCreate(
                        ['country_id' => $country->id, 'name' => $name],
                        ['name' => $name]
                    );
                }
            }
        }
    }
}
