<?php

namespace Database\Factories;

use App\Models\TricycleMayorsPermit;
use App\Models\Tricycle;
use Illuminate\Database\Eloquent\Factories\Factory;

class TricycleMayorsPermitFactory extends Factory
{
    protected $model = TricycleMayorsPermit::class;

    public function definition(): array
    {
        static $tricycleIds = null;
        $tricycleIds ??= Tricycle::pluck('id')->all();

        $issueDate = $this->faker->dateTimeBetween('-2 years', '-1 month');
        $expiryDate = (clone $issueDate)->modify('+1 year');

        return [
            'tricycle_id' => $this->faker->randomElement($tricycleIds),
            'control_no' => 'MP-' . $this->faker->unique()->numberBetween(10000, 99999),
            'status' => $this->faker->randomElement(['active', 'expired']),
            'business_name' => $this->faker->optional()->company(),
            'motorized_operation' => $this->faker->randomElement(['Tricycle for Hire', 'Single Motorcycle', 'Habal-Habal']),
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