<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptops',
                'description' => 'Portable computers for work, study, and gaming.',
            ],
            [
                'name' => 'Peripherals',
                'description' => 'Accessories like keyboards, mice, and webcams.',
            ],
            [
                'name' => 'Storage',
                'description' => 'SSDs, HDDs, and external storage solutions.',
            ],
            [
                'name' => 'Networking',
                'description' => 'Routers, switches, and connectivity devices.',
            ],
            [
                'name' => 'Audio',
                'description' => 'Headphones, microphones, and speakers.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
