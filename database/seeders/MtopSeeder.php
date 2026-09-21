<?php

namespace Database\Seeders;

use App\Models\Mtop;
use App\Models\Tricycle;
use Illuminate\Database\Seeder;

class MtopSeeder extends Seeder
{
    public function run(): void
    {
        $tricycles = Tricycle::inRandomOrder()->take(20)->get();

        if ($tricycles->isEmpty()) {
            $this->command->warn('No tricycles found. Run TricycleSeeder first.');

            return;
        }

        // One MTOP per tricycle, so no owner is duplicated in the test data.
        foreach ($tricycles as $tricycle) {
            Mtop::factory()->create(['tricycle_id' => $tricycle->id]);
        }
    }
}