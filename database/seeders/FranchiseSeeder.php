<?php

namespace Database\Seeders;

use App\Models\Franchise;
use App\Models\Tricycle;
use Illuminate\Database\Seeder;

class FranchiseSeeder extends Seeder
{
    public function run(): void
    {
        $tricycles = Tricycle::inRandomOrder()->take(20)->get();

        if ($tricycles->isEmpty()) {
            $this->command->warn('No tricycles found. Run TricycleSeeder first.');

            return;
        }

        // One franchise per tricycle, so no owner gets duplicated in the test data.
        foreach ($tricycles as $tricycle) {
            Franchise::factory()->create(['tricycle_id' => $tricycle->id]);
        }
    }
}