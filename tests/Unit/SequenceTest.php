<?php
declare(strict_types=1);

namespace Tests\Unit;

use DateTimeImmutable;
use Exception;
use NGT\Laravel\Sequence\Models\SequencePeriod;
use NGT\Laravel\Sequence\Sequence;

class SequenceTest extends \Tests\TestCase
{
    public function test_it_returns_pattern(): void
    {
        $sequence = $this->makeSequence(1, 'example_pattern', '2021-01-01');

        $this->assertSame('example_pattern', $sequence->getPattern());
    }

    public function test_it_returns_ordinal_number(): void
    {
        $sequence = $this->makeSequence(1234, 'example_pattern', '2021-01-01');

        $this->assertSame(1234, $sequence->getOrdinalNumber());
    }

    public function test_it_resolves_every_element_of_pattern(): void
    {
        $sequence = $this->makeSequence(
            10,
            '{number}/{day}/{month}/{year}/{day_short}/{month_short}/{year_short}',
            '2021-03-09'
        );

        $this->assertSame('10/09/03/2021/9/3/21', $sequence->getNumber());
    }

    public function test_it_leaves_static_pattern_elements(): void
    {
        $sequence = $this->makeSequence(15, '{number}/TEST/{year}/K', '2020-01-01');

        $this->assertSame('15/TEST/2020/K', $sequence->getNumber());
    }

    private function makeSequence(int $ordinalNumber, string $pattern, string $date): Sequence
    {
        $period = $this->makePeriod($ordinalNumber);
        $date   = $this->makeDate($date);

        return new Sequence($period, $pattern, $date);
    }

    private function makePeriod(int $ordinalNumber): SequencePeriod
    {
        return SequencePeriod::make([
            'date'           => '2021-01-01',
            'ordinal_number' => $ordinalNumber,
        ]);
    }
}
