<?php

namespace Database\Factories;

use App\Models\Tricycle;
use Illuminate\Database\Eloquent\Factories\Factory;

class TricycleFactory extends Factory
{
    protected $model = Tricycle::class;

    public function definition(): array
    {
        $dateRegistered = $this->faker->dateTimeBetween('-3 years', '-1 month');
        $dateExpired = (clone $dateRegistered)->modify('+1 year');

        return [
            'body_number' => 'BOD-' . $this->faker->unique()->numberBetween(1000, 99999),
            'plate_no' => strtoupper($this->faker->unique()->bothify('??-####')),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'make_kind' => $this->faker->randomElement(['Honda TMX', 'Kawasaki Bajaj', 'Rusi', 'Yamaha', 'Suzuki Raider']),
            'status' => $this->faker->randomElement(['active', 'renewed', 'expired']),
            'engine_motor_no' => strtoupper($this->faker->bothify('ENG-########')),
            'chassis_no' => strtoupper($this->faker->bothify('CHS-########')),
            'date_registered' => $dateRegistered,
            'date_expired' => $dateExpired,
            'toda' => $this->faker->randomElement(array_keys(Tricycle::TODA_OPTIONS)),
            'remarks' => $this->faker->optional()->sentence(),
        ];
    }
}