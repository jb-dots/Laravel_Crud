<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Product 1',
            'description' => 'Description for Product 1',
            'price' => 10.99,
            'stock' => 100,
            'image' => 'product1.jpg',
        ]);
    
        Product::create([
            'name' => 'Product 2',
            'description' => 'Description for Product 2',
            'price' => 19.99,
            'stock' => 50,
            'image' => 'product2.jpg',
        ]);
    }
}
