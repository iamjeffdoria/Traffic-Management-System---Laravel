<?php

namespace Database\Seeders;

use App\Models\Tricycle;
use Illuminate\Database\Seeder;

class TricycleSeeder extends Seeder
{
    public function run(): void
    {
        Tricycle::factory()->count(500)->create();
    }
}