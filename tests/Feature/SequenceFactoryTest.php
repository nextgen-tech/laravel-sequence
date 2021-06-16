<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use NGT\Laravel\Sequence\Enums\ResetFrequency;
use NGT\Laravel\Sequence\Models\SequenceRule;
use NGT\Laravel\Sequence\Sequence;
use NGT\Laravel\Sequence\SequenceFactory;

class SequenceFactoryTest extends \Tests\TestCase
{
    use RefreshDatabase;

    /**
     * The sequence factory instance.
     *
     * @var  \NGT\Laravel\Sequence\SequenceFactory
     */
    private $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->factory = app(SequenceFactory::class);
    }

    public function test_it_throws_exception_when_missing_rule(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot find sequence rule for "unknown_type" type.');

        $this->factory->create('unknown_type', $this->makeDate('2021-01-01'));
    }

    public function test_it_create_period_for_rule_if_missing(): void
    {
        $rule = SequenceRule::create([
            'type'            => 'test_type',
            'pattern'         => 'test_pattern',
            'reset_frequency' => ResetFrequency::YEARLY,
        ]);

        $this->assertCount(0, $rule->periods()->get());

        $this->factory->create('test_type', $this->makeDate('2021-06-06'));

        $this->assertCount(1, $rule->periods()->get());

        /** @var \NGT\Laravel\Sequence\Models\SequencePeriod */
        $period = $rule->periods()->first();

        $this->assertSame('2021-06-06', $period->date);
        $this->assertSame(1, $period->ordinal_number);
    }

    public function test_it_uses_correct_period_for_yearly_reset_frequency(): void
    {
        $rule = SequenceRule::create([
            'type'            => 'test_type',
            'pattern'         => 'test_pattern',
            'reset_frequency' => ResetFrequency::YEARLY,
        ]);
        $rule->periods()->createMany([
            ['ordinal_number' => 10, 'date' => '2020-01-01'],
            ['ordinal_number' => 15,'date' => '2021-01-01'],
        ]);

        $sequence = $this->factory->create('test_type', $this->makeDate('2020-12-31'));
        // use period with date 2020-01-01
        $this->assertSame(10, $sequence->getOrdinalNumber());

        $sequence = $this->factory->create('test_type', $this->makeDate('2021-01-01'));
        // use period with date 2021-01-01
        $this->assertSame(15, $sequence->getOrdinalNumber());

        $sequence = $this->factory->create('test_type', $this->makeDate('2022-01-01'));
        // create new period
        $this->assertSame(1, $sequence->getOrdinalNumber());
    }

    public function test_it_uses_correct_period_for_monthly_reset_frequency(): void
    {
        $rule = SequenceRule::create([
            'type'            => 'test_type',
            'pattern'         => 'test_pattern',
            'reset_frequency' => ResetFrequency::MONTHLY,
        ]);
        $rule->periods()->createMany([
            ['ordinal_number' => 20, 'date' => '2021-01-01'],
            ['ordinal_number' => 25,'date' => '2021-02-01'],
        ]);

        $sequence = $this->factory->create('test_type', $this->makeDate('2021-01-31'));
        // use period with date 2021-01-01
        $this->assertSame(20, $sequence->getOrdinalNumber());

        $sequence = $this->factory->create('test_type', $this->makeDate('2021-02-01'));
        // use period with date 2021-02-01
        $this->assertSame(25, $sequence->getOrdinalNumber());

        $sequence = $this->factory->create('test_type', $this->makeDate('2021-03-01'));
        // create new period
        $this->assertSame(1, $sequence->getOrdinalNumber());
    }

    public function test_it_uses_correct_period_for_daily_reset_frequency(): void
    {
        $rule = SequenceRule::create([
            'type'            => 'test_type',
            'pattern'         => 'test_pattern',
            'reset_frequency' => ResetFrequency::DAILY,
        ]);
        $rule->periods()->createMany([
            ['ordinal_number' => 30, 'date' => '2021-01-01'],
            ['ordinal_number' => 35,'date' => '2021-01-02'],
        ]);

        $sequence = $this->factory->create('test_type', $this->makeDate('2021-01-01'));
        // use period with date 2021-01-01
        $this->assertSame(30, $sequence->getOrdinalNumber());

        $sequence = $this->factory->create('test_type', $this->makeDate('2021-01-02'));
        // use period with date 2021-01-02
        $this->assertSame(35, $sequence->getOrdinalNumber());

        $sequence = $this->factory->create('test_type', $this->makeDate('2021-01-03'));
        // create new period
        $this->assertSame(1, $sequence->getOrdinalNumber());
    }

    public function test_it_increases_ordinal_number(): void
    {
        $rule = SequenceRule::create([
            'type'            => 'test_type',
            'pattern'         => 'test_pattern',
            'reset_frequency' => ResetFrequency::YEARLY,
        ]);

        $sequence = $this->factory->create('test_type', $this->makeDate('2021-01-01'));

        $this->assertSame(1, $sequence->getOrdinalNumber());
        $this->assertSame(1, $rule->periods()->first()->ordinal_number);

        $sequence->increment();

        $this->assertSame(2, $sequence->getOrdinalNumber());
        $this->assertSame(2, $rule->periods()->first()->ordinal_number);
    }
}
