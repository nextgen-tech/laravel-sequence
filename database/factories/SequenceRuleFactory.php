<?php
declare(strict_types=1);

namespace NGT\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;
use NGT\Laravel\Sequence\Enums\ResetFrequency;
use NGT\Laravel\Sequence\Models\SequenceRule;

class SequenceRuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SequenceRule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type'            => $this->faker->unique()->word(),
            'reset_frequency' => $this->faker->randomElement(ResetFrequency::getValues()),
            'pattern'         => function (array $attributes) {
                return $this->faker->randomElement(
                    $this->examplePatterns($attributes['reset_frequency'])
                );
            },
        ];
    }

    /**
     * Get example patterns for given reset frequency
     *
     * @param   string  $resetFrequency
     * @return  array<string>
     */
    private function examplePatterns(string $resetFrequency): array
    {
        switch ($resetFrequency) {
            case ResetFrequency::DAILY:
                return ['{number}/{day}/{month}/{year}', '{month}/{day}/{year}/{number}', '{number}/{day_short}{month_short}{year_short}'];

            case ResetFrequency::MONTHLY:
                return ['{number}/COMPANY/{month}/{year}', '{month_short}/{year}/{number}', '{number}/{month}-{year_short}'];

            case ResetFrequency::YEARLY:
                return ['{number}/EXAMPLE/{year}', '{year}-{number}', '{number}/{year_short}'];
        }

        throw new InvalidArgumentException('Invalid reset frequency value.');
    }

    /**
     * Indicate that sequence should resets daily.
     *
     * @return  \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function resetsDaily(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'reset_frequency' => ResetFrequency::DAILY,
            ];
        });
    }

    /**
     * Indicate that sequence should resets monthly.
     *
     * @return  \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function resetsMonthly(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'reset_frequency' => ResetFrequency::MONTHLY,
            ];
        });
    }

    /**
     * Indicate that sequence should resets yearly.
     *
     * @return  \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function resetsYearly(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'reset_frequency' => ResetFrequency::YEARLY,
            ];
        });
    }
}
