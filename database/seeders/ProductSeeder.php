<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        $categoryIds = Category::pluck('id', 'name');

        $products = [
            [
                'name' => 'ApexBook 14',
                'description' => '14-inch laptop with 16GB RAM and 512GB SSD.',
                'price' => 1299.99,
                'stock' => 25,
                'category' => 'Laptops',
            ],
            [
                'name' => 'Nimbus Pro 15',
                'description' => 'Lightweight 15-inch ultrabook for productivity.',
                'price' => 1099.50,
                'stock' => 18,
                'category' => 'Laptops',
            ],
            [
                'name' => 'Phantom Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with hot-swappable switches.',
                'price' => 129.90,
                'stock' => 60,
                'category' => 'Peripherals',
            ],
            [
                'name' => 'Falcon Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with programmable buttons.',
                'price' => 59.99,
                'stock' => 90,
                'category' => 'Peripherals',
            ],
            [
                'name' => 'Titan NVMe SSD 1TB',
                'description' => 'High-speed PCIe Gen4 NVMe SSD.',
                'price' => 149.00,
                'stock' => 70,
                'category' => 'Storage',
            ],
            [
                'name' => 'Vault External HDD 4TB',
                'description' => 'USB 3.2 external hard drive for backups.',
                'price' => 119.99,
                'stock' => 40,
                'category' => 'Storage',
            ],
            [
                'name' => 'Orbit AX3000 Router',
                'description' => 'Dual-band Wi-Fi 6 router for home and office.',
                'price' => 189.49,
                'stock' => 30,
                'category' => 'Networking',
            ],
            [
                'name' => 'LinkPro 8-Port Switch',
                'description' => 'Gigabit unmanaged switch with metal housing.',
                'price' => 49.99,
                'stock' => 75,
                'category' => 'Networking',
            ],
            [
                'name' => 'Echo Studio Headphones',
                'description' => 'Over-ear headphones with active noise cancellation.',
                'price' => 219.00,
                'stock' => 35,
                'category' => 'Audio',
            ],
            [
                'name' => 'VoiceCast USB Microphone',
                'description' => 'Cardioid USB microphone for streaming and podcasts.',
                'price' => 89.99,
                'stock' => 55,
                'category' => 'Audio',
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['name' => $item['name']],
                [
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'category_id' => $categoryIds[$item['category']] ?? Category::inRandomOrder()->value('id'),
                ]
            );
        }
    }
}
