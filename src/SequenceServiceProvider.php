<?php
declare(strict_types=1);

namespace NGT\Laravel\Sequence;

use Illuminate\Support\ServiceProvider;

class SequenceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @codeCoverageIgnore
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
