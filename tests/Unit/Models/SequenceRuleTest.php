<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use NGT\Laravel\Sequence\Enums\ResetFrequency;
use NGT\Laravel\Sequence\Models\SequenceRule;

class SequenceRuleTest extends \Tests\TestCase
{
    public function test_it_correctly_needs_yearly_reset(): void
    {
        $yearlyRule  = $this->makeYearlySequenceRule();
        $monthlyRule = $this->makeMonthlySequenceRule();
        $dailyRule   = $this->makeDailySequenceRule();

        $this->assertTrue($yearlyRule->needsYearlyReset());
        $this->assertTrue($monthlyRule->needsYearlyReset());
        $this->assertTrue($dailyRule->needsYearlyReset());
    }

    public function test_it_correctly_needs_monthly_reset(): void
    {
        $yearlyRule  = $this->makeYearlySequenceRule();
        $monthlyRule = $this->makeMonthlySequenceRule();
        $dailyRule   = $this->makeDailySequenceRule();

        $this->assertFalse($yearlyRule->needsMonthlyReset());
        $this->assertTrue($monthlyRule->needsMonthlyReset());
        $this->assertTrue($dailyRule->needsMonthlyReset());
    }

    public function test_it_correctly_needs_daily_reset(): void
    {
        $yearlyRule  = $this->makeYearlySequenceRule();
        $monthlyRule = $this->makeMonthlySequenceRule();
        $dailyRule   = $this->makeDailySequenceRule();

        $this->assertFalse($yearlyRule->needsDailyReset());
        $this->assertFalse($monthlyRule->needsDailyReset());
        $this->assertTrue($dailyRule->needsDailyReset());
    }

    private function makeYearlySequenceRule(): SequenceRule
    {
        return $this->makeSequenceRule(ResetFrequency::fromValue(ResetFrequency::YEARLY));
    }
    private function makeMonthlySequenceRule(): SequenceRule
    {
        return $this->makeSequenceRule(ResetFrequency::fromValue(ResetFrequency::MONTHLY));
    }
    private function makeDailySequenceRule(): SequenceRule
    {
        return $this->makeSequenceRule(ResetFrequency::fromValue(ResetFrequency::DAILY));
    }

    private function makeSequenceRule(ResetFrequency $resetFrequency): SequenceRule
    {
        return SequenceRule::make([
            'type'            => 'test_type',
            'pattern'         => 'test_pattern',
            'reset_frequency' => $resetFrequency->value,
        ]);
    }
}
