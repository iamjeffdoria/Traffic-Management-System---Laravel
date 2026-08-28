<?php

namespace Database\Seeders;

use App\Models\IdCard;
use Illuminate\Database\Seeder;

class IdCardSeeder extends Seeder
{
    public function run(): void
    {
        IdCard::factory()->count(500)->create();
    }
}