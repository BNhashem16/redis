<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
	// php artisan db:seed CustomerSeeder
    public function run(): void
    {
		Customer::factory()->count(1000)->create();
    }
}
