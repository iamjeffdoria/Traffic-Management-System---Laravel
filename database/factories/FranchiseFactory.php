<?php

namespace Database\Factories;

use App\Models\Franchise;
use App\Models\Tricycle;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class FranchiseFactory extends Factory
{
    protected $model = Franchise::class;

    public function definition(): array
    {
        static $tricycleIds = null;
        $tricycleIds ??= Tricycle::pluck('id')->all();

        $status = $this->faker->randomElement(['New', 'Renewed', 'Renewed', 'Expired']);
        $year = (int) now()->format('Y');

        // Active franchises were issued this year and expire on Dec 31 (like the real licenses).
        // Expired ones were issued earlier and lapsed on Dec 31 of last year.
        if ($status === 'Expired') {
            $date = $this->faker->dateTimeBetween(($year - 2) . '-01-01', ($year - 1) . '-06-30');
            $validUntil = new DateTime(($year - 1) . '-12-31');
        } else {
            $date = $this->faker->dateTimeBetween($year . '-01-01', 'now');
            $validUntil = new DateTime($year . '-12-31');
        }

        return [
            'tricycle_id' => $this->faker->randomElement($tricycleIds),
            'valid_until' => $validUntil,
            'denomination' => $this->faker->optional()->randomElement(['5', '10', '20']),
            'status' => $status,
            'authorized_no' => 'FR-' . $this->faker->unique()->numberBetween(10000, 99999),
            'authorized_route' => $this->faker->randomElement([
                'Palompon-Sabang-Tabunok',
                'Palompon-Sabang',
                'Sabang-Palompon',
                'Palompon-Tabunok',
                'Palompon-Ipil',
                'Palompon-Guiwan',
            ]),
            'purpose' => $this->faker->optional()->randomElement([
                'For hire',
                'Public transport',
                'Passenger service',
            ]),
            'official_receipt_no' => 'OR-' . $this->faker->unique()->numberBetween(100000, 999999),
            'amount_paid' => $this->faker->randomElement([1000, 1200, 1380, 1500]),
            'date' => $date,
            'municipal_treasurer' => $this->faker->name(),
            'license_issued_date' => $date,
            'license_issued_at' => 'Civil Security Office Palompon, Leyte',
        ];
    }
}