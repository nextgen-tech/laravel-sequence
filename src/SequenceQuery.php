<?php
declare(strict_types=1);

namespace NGT\Laravel\Sequence;

use DateTimeInterface;
use NGT\Laravel\Sequence\Models\SequencePeriod;
use NGT\Laravel\Sequence\Models\SequenceRule;

class SequenceQuery
{
    /**
     * Find sequence rule for given type.
     *
     * @param   string  $type
     * @return  \NGT\Laravel\Sequence\Models\SequenceRule|null
     */
    public function findSequenceRule(string $type): ?SequenceRule
    {
        return SequenceRule::query()
            ->where('type', $type)
            ->first();
    }

    /**
     * Find sequence period for given sequence rule and date.
     *
     * @param   \NGT\Laravel\Sequence\Models\SequenceRule         $sequenceRule
     * @param   DateTimeInterface                                 $date
     * @return  \NGT\Laravel\Sequence\Models\SequencePeriod|null
     */
    public function findSequencePeriod(SequenceRule $sequenceRule, DateTimeInterface $date): ?SequencePeriod
    {
        return $sequenceRule->periods()
            ->when($sequenceRule->needsYearlyReset(), function ($query) use ($date) {
                $query->whereYear('date', $date->format('Y'));
            })
            ->when($sequenceRule->needsMonthlyReset(), function ($query) use ($date) {
                $query->whereMonth('date', $date->format('m'));
            })
            ->when($sequenceRule->needsDailyReset(), function ($query) use ($date) {
                $query->whereDay('date', $date->format('d'));
            })
            ->first();
    }

    /**
     * Create new sequence period for given sequence rule and date.
     *
     * @param   \NGT\Laravel\Sequence\Models\SequenceRule    $sequenceRule
     * @param   DateTimeInterface                            $date
     * @return  \NGT\Laravel\Sequence\Models\SequencePeriod
     */
    public function createSequencePeriod(SequenceRule $sequenceRule, DateTimeInterface $date): SequencePeriod
    {
        return $sequenceRule->periods()->create([
            'date'           => $date->format('Y-m-d'),
            'ordinal_number' => 1,
        ]);
    }
}
