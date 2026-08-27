<?php

namespace Database\Seeders;

use App\Models\Franchise;
use Illuminate\Database\Seeder;

class FranchiseSeeder extends Seeder
{
    public function run(): void
    {
        Franchise::factory()->count(500)->create();
    }
}