<?php

namespace Database\Factories;

use App\Models\IdCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdCardFactory extends Factory
{
    protected $model = IdCard::class;

    public function definition(): array
    {
        $dateIssued = $this->faker->dateTimeBetween('-2 years', '-1 month');
        $expiryDate = (clone $dateIssued)->modify('+3 years');

        return [
            'full_name' => $this->faker->name(),
            'id_number' => 'ID-' . $this->faker->unique()->numberBetween(10000, 99999),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'address' => $this->faker->address(),
            'height' => $this->faker->randomFloat(2, 150, 190),
            'weight' => $this->faker->randomFloat(2, 45, 95),
            'or_number' => 'OR-' . $this->faker->unique()->numberBetween(100000, 999999),
            'date_issued' => $dateIssued,
            'expiry_date' => $expiryDate,
            'photo_path' => null,
        ];
    }
}