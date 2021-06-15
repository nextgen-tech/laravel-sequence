<?php
declare(strict_types=1);

namespace NGT\Laravel\Sequence\Enums;

use BenSampo\Enum\Enum;

final class ResetFrequency extends Enum
{
    public const YEARLY  = 'yearly';
    public const MONTHLY = 'monthly';
    public const DAILY   = 'daily';
}
