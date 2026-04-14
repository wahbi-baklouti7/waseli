<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Global Marketplaces',
                'description' => 'Orders from Shein, Temu, AliExpress, Amazon, etc.',
                'icon' => 'shopping_cart'
            ],
            [
                'name' => 'Electronics & Gadgets',
                'description' => 'Phones, Laptops, Cameras and high-value technical equipment.',
                'icon' => 'devices'
            ],
            [
                'name' => 'Apparel & Fashion',
                'description' => 'Clothing, Shoes, Accessories, and personal care products.',
                'icon' => 'apparel'
            ],
            [
                'name' => 'Documents & Books',
                'description' => 'Passports, Legal documents, Books, and small paper parcels.',
                'icon' => 'description'
            ],
            [
                'name' => 'Home & Kitchen',
                'description' => 'Small appliances, Decorations, and household items.',
                'icon' => 'home'
            ],
            [
                'name' => 'Food & Perishables',
                'description' => 'Local delicacies, Sweets, and items with a limited shelf life.',
                'icon' => 'restaurant'
            ],
            [
                'name' => 'Bulky & Heavy Items',
                'description' => 'Tires, Bicycles, Car parts, and large heavy equipment.',
                'icon' => 'conveyor_belt'
            ],
            [
                'name' => 'Health & Beauty',
                'description' => 'Cosmetics, Skincare, and pharmaceutical products.',
                'icon' => 'medical_services'
            ],
            [
                'name' => 'Other / Specialized',
                'description' => 'Customized requests or niche items not listed elsewhere.',
                'icon' => 'category'
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon']
                ]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('categories.all');
    }
}
