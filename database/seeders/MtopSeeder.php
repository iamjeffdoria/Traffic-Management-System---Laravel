<?php

namespace Database\Seeders;

use App\Models\Mtop;
use Illuminate\Database\Seeder;

class MtopSeeder extends Seeder
{
    public function run(): void
    {
        Mtop::factory()->count(500)->create();
    }
}