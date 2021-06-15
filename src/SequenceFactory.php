<?php
declare(strict_types=1);

namespace NGT\Laravel\Sequence;

use DateTimeInterface;
use InvalidArgumentException;

class SequenceFactory
{
    /**
     * The instance of sequence query helper.
     *
     * @var  \NGT\Laravel\Sequence\SequenceQuery
     */
    protected $query;

    /**
     * The sequence factory constructor.
     *
     * @param  \NGT\Laravel\Sequence\SequenceQuery  $query
     */
    public function __construct(SequenceQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Create instance of sequence.
     *
     * @param   string             $type
     * @param   DateTimeInterface  $date
     * @return  \NGT\Laravel\Sequence\Sequence
     */
    public function create(string $type, DateTimeInterface $date): Sequence
    {
        $sequenceRule = $this->query->findSequenceRule($type);

        if ($sequenceRule === null) {
            throw new InvalidArgumentException("Cannot find sequence rule for \"{$type}\" type.");
        }

        $sequencePeriod = $this->query->findSequencePeriod($sequenceRule, $date);

        if ($sequencePeriod === null) {
            $sequencePeriod = $this->query->createSequencePeriod($sequenceRule, $date);
        }

        return new Sequence(
            $sequencePeriod,
            $sequenceRule->pattern,
            $date
        );
    }
}
