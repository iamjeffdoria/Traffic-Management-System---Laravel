<?php

namespace Database\Factories;

use App\Models\Mtop;
use App\Models\Tricycle;
use Illuminate\Database\Eloquent\Factories\Factory;

class MtopFactory extends Factory
{
    protected $model = Mtop::class;

    public function definition(): array
    {
        static $tricycleIds = null;
        static $treasurer = null;
        static $officer = null;

        $tricycleIds ??= Tricycle::pluck('id')->all();

        // Same officials on every record, like a real office would have in one term.
        $treasurer ??= $this->faker->name();
        $officer ??= $this->faker->name();

        return [
            'tricycle_id' => $this->faker->randomElement($tricycleIds),
            'case_no' => 'MTOP-' . $this->faker->unique()->numberBetween(10000, 99999),
            'no_of_units' => $this->faker->randomElement([1, 1, 1, 1, 2]),
            'route_operation' => $this->faker->randomElement([
                'Palompon-Sabang-Tabunok',
                'Palompon-Sabang',
                'Sabang-Palompon',
                'Palompon-Tabunok',
                'Palompon-Ipil',
                'Palompon-Guiwan',
            ]),
            'date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'municipal_treasurer' => $treasurer,
            'officer_in_charge' => $officer,
            'mayor' => 'MARY DOMINIQUE OÑATE',
        ];
    }
}