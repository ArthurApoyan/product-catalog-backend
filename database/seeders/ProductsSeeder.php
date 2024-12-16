<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = DB::table('categories')->get();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 5; $i++) {
                DB::table('products')->insert([
                    'name' => "{$category->name} Product {$i}",
                    'description' => "Description for {$category->name} Product {$i}",
                    'price' => rand(100, 1000) / 10,
                    'image' => 'https://cosirkenya.org/wp-content/uploads/2022/06/product-image-placeholder.jpeg',
                    'category_id' => $category->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
