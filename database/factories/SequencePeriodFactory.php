<?php
declare(strict_types=1);

namespace NGT\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use NGT\Laravel\Sequence\Models\SequencePeriod;

class SequencePeriodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SequencePeriod::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'date'           => $this->faker->dateTimeBetween('-1 year')->format('Y-m-d'),
            'ordinal_number' => $this->faker->numberBetween(1, 100),
        ];
    }
}
