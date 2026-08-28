<?php

namespace Database\Factories;

use App\Models\PotpotMayorsPermit;
use Illuminate\Database\Eloquent\Factories\Factory;

class PotpotMayorsPermitFactory extends Factory
{
    protected $model = PotpotMayorsPermit::class;

    public function definition(): array
    {
        $issueDate = $this->faker->dateTimeBetween('-2 years', '-1 month');
        $expiryDate = (clone $issueDate)->modify('+1 year');

        return [
            'control_no' => 'PMP-' . $this->faker->unique()->numberBetween(10000, 99999),
            'status' => $this->faker->randomElement(['active', 'expired']),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'business_name' => $this->faker->optional()->company(),
            'motorized_operation' => $this->faker->randomElement(['Single Motorcycle', 'Habal-Habal', 'Motorized Pedicab']),
            'or_no' => 'OR-' . $this->faker->unique()->numberBetween(100000, 999999),
            'amount_paid' => $this->faker->randomFloat(2, 100, 2000),
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
            'issued_at' => $this->faker->city(),
            'mayor' => $this->faker->name(),
            'quarter' => $this->faker->randomElement(['First Quarter', 'Second Quarter', 'Third Quarter', 'Fourth Quarter']),
        ];
    }
}