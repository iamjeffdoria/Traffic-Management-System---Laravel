<?php

namespace Database\Seeders;

use App\Models\TricycleMayorsPermit;
use Illuminate\Database\Seeder;

class TricycleMayorsPermitSeeder extends Seeder
{
    public function run(): void
    {
        TricycleMayorsPermit::factory()->count(500)->create();
    }
}