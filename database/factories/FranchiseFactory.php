<?php

namespace Database\Factories;

use App\Models\Franchise;
use App\Models\Tricycle;
use Illuminate\Database\Eloquent\Factories\Factory;

class FranchiseFactory extends Factory
{
    protected $model = Franchise::class;

    public function definition(): array
    {
        static $tricycleIds = null;
        $tricycleIds ??= Tricycle::pluck('id')->all();

        $date = $this->faker->dateTimeBetween('-2 years', '-1 month');
        $validUntil = (clone $date)->modify('+1 year');

        return [
            'tricycle_id' => $this->faker->randomElement($tricycleIds),
            'valid_until' => $validUntil,
            'denomination' => $this->faker->optional()->randomElement(['5', '10', '20']),
            'status' => $this->faker->randomElement(['New', 'Renewed', 'Expired']),
            'authorized_no' => 'FR-' . $this->faker->unique()->numberBetween(10000, 99999),
            'authorized_route' => $this->faker->streetName() . ' to ' . $this->faker->streetName(),
            'purpose' => $this->faker->optional()->sentence(),
            'official_receipt_no' => 'OR-' . $this->faker->unique()->numberBetween(100000, 999999),
            'amount_paid' => $this->faker->randomFloat(2, 100, 2000),
            'date' => $date,
            'municipal_treasurer' => $this->faker->name(),
        ];
    }
}