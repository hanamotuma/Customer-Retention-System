<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Generate 1,000 random customers
        for ($i = 0; $i < 1000; $i++) {
            DB::table('customers')->insert([
                'name' => $faker->name,
                'recency' => rand(1, 365), // Days since last purchase (1 to 365)
                'frequency' => rand(1, 50), // Total purchases (1 to 50)
                'monetary' => rand(10, 5000), // Total spent ($10 to $5000)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}