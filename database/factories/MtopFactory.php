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
        $tricycleIds ??= Tricycle::pluck('id')->all();

        return [
            'tricycle_id' => $this->faker->randomElement($tricycleIds),
            'case_no' => 'MTOP-' . $this->faker->unique()->numberBetween(10000, 99999),
            'no_of_units' => $this->faker->numberBetween(1, 5),
            'route_operation' => $this->faker->streetName() . ' to ' . $this->faker->streetName(),
            'date' => $this->faker->dateTimeBetween('-2 years', '-1 month'),
            'municipal_treasurer' => $this->faker->name(),
            'officer_in_charge' => $this->faker->name(),
            'mayor' => $this->faker->name(),
        ];
    }
}