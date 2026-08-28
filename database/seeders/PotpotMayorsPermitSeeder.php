<?php

namespace Database\Seeders;

use App\Models\PotpotMayorsPermit;
use Illuminate\Database\Seeder;

class PotpotMayorsPermitSeeder extends Seeder
{
    public function run(): void
    {
        PotpotMayorsPermit::factory()->count(500)->create();
    }
}