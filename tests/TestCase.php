<?php
declare(strict_types=1);

namespace Tests;

use DateTimeImmutable;
use Exception;
use NGT\Laravel\Sequence\SequenceServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SequenceServiceProvider::class,
        ];
    }

    protected function makeDate(string $date): DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $date);

        if ($date instanceof DateTimeImmutable) {
            return $date;
        }

        throw new Exception();
    }
}
