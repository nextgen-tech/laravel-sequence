<?php
declare(strict_types=1);

namespace NGT\Laravel\Sequence\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  string  $date
 * @property  int     $ordinal_number
 */
class SequencePeriod extends Model
{
    public $table = 'sequence_periods';

    /**
     * @var  array<string>
     */
    protected $fillable = [
        'date',
        'ordinal_number',
    ];

    /**
     * @var  array<string, string>
     */
    protected $casts = [
        'ordinal_number' => 'integer',
    ];
}
