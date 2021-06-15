<?php
declare(strict_types=1);

namespace NGT\Laravel\Sequence\Models;

use NGT\Laravel\Sequence\Enums\ResetFrequency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property  string  $pattern
 * @property  string  $reset_frequency
 */
class SequenceRule extends Model
{
    public $table = 'sequence_rules';

    /**
     * @var  array<string>
     */
    protected $fillable = [
        'type',
        'pattern',
        'reset_frequency',
    ];

    /**
     * Get the related periods.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function periods(): HasMany
    {
        return $this->hasMany(SequencePeriod::class, 'rule_id');
    }

    /**
     * Decide whether ordinal number needs to be reset yearly.
     *
     * @return  bool
     */
    public function needsYearlyReset(): bool
    {
        return ResetFrequency::fromValue($this->reset_frequency)->in([
            ResetFrequency::YEARLY,
            ResetFrequency::MONTHLY,
            ResetFrequency::DAILY,
        ]);
    }

    /**
     * Decide whether ordinal number needs to be reset monthly.
     *
     * @return  bool
     */
    public function needsMonthlyReset(): bool
    {
        return ResetFrequency::fromValue($this->reset_frequency)->in([
            ResetFrequency::MONTHLY,
            ResetFrequency::DAILY,
        ]);
    }

    /**
     * Decide whether ordinal number needs to be reset daily.
     *
     * @return  bool
     */
    public function needsDailyReset(): bool
    {
        return ResetFrequency::fromValue($this->reset_frequency)->is(ResetFrequency::DAILY);
    }
}
