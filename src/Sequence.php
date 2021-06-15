<?php
declare(strict_types=1);

namespace NGT\Laravel\Sequence;

use DateTimeInterface;
use NGT\Laravel\Sequence\Models\SequencePeriod;

class Sequence
{
    /**
     * The period instance to use on sequence.
     *
     * @var  \NGT\Laravel\Sequence\Models\SequencePeriod
     */
    protected $period;

    /**
     * The date to use on sequence.
     *
     * @var  DateTimeInterface
     */
    protected $date;

    /**
     * The number pattern to use on sequence.
     *
     * @var  string
     */
    protected $pattern;

    /**
     * The sequence constructor.
     *
     * @param  \NGT\Laravel\Sequence\Models\SequencePeriod  $period
     * @param  string                                       $pattern
     * @param  DateTimeInterface                            $date
     */
    public function __construct(SequencePeriod $period, string $pattern, DateTimeInterface $date)
    {
        $this->period  = $period;
        $this->pattern = $pattern;
        $this->date    = $date;
    }

    /**
     * Get the sequence number.
     *
     * @return  string
     */
    public function getNumber(): string
    {
        return strtr($this->getPattern(), [
            '{number}'      => $this->getOrdinalNumber(),
            '{day}'         => $this->date->format('d'),
            '{month}'       => $this->date->format('m'),
            '{year}'        => $this->date->format('Y'),
            '{day_short}'   => $this->date->format('j'),
            '{month_short}' => $this->date->format('n'),
            '{year_short}'  => $this->date->format('y'),
        ]);
    }

    /**
     * Get the ordinal number of sequence.
     *
     * @return  int
     */
    public function getOrdinalNumber(): int
    {
        return $this->period->ordinal_number;
    }

    /**
     * Get the pattern of sequence.
     *
     * @return  string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * Increment ordinal number of period.
     *
     * @return  void
     */
    public function increment(): void
    {
        $this->period->increment('ordinal_number');
    }
}
