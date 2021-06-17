# Laravel Sequence

Generate sequential numbers with pattern (e.g. for invoice numbers)

## Features

* Easy integration
* Multiple pattern placeholders
* Support for three most common reset frequencies
* Automatically creating new ordinal number based on reset frequency

## Installation

```sh
composer require nextgen-tech/laravel-sequence
```

## Usage

```php
use Carbon\Carbon;
use NGT\Laravel\Sequence\Enums\ResetFrequency;
use NGT\Laravel\Sequence\Models\SequenceRule;
use NGT\Laravel\Sequence\SequenceFactory;

/**
 * Create new sequence rule. It needs to be done only once.
 */
SequenceRule::create([
    'type'            => 'invoice',
    'pattern'         => '{number}/COMPANY/{year}',
    'reset_frequency' => ResetFrequency::YEARLY,
]);

/**
 * Make sequence factory via container or DI.
 */
$factory = app(SequenceFactory::class);

/**
 * Create sequence by passing sequence type and date (e.g. issue date of invoice).
 */
$sequence = $factory->create(
    'invoice',
    Carbon::createFromFormat('Y-m-d', '2021-06-01')
);

/**
 * Public methods of sequence.
 */
$ordinal = $sequence->getOrdinalNumber(); // e.g. 21
$number  = $sequence->getNumber();        // e.g. 21/COMPANY/2021
$pattern = $sequence->getPattern();       // e.g. {number}/COMPANY/{year}

/**
 * After use of generated number, manual increment of ordinal number is required.
 */
$sequence->increment();
```

## Reset Frequencies

Sequences supports three most commonly used reset frequencies. `\NGT\Laravel\Sequence\Enums\ResetFrequency` class should be used when creating new sequence rule.

* `ResetFrequency::YEARLY` - resets ordinal number at the beginning of new year
* `ResetFrequency::MONTHLY` - resets ordinal number at the beginning of new month
* `ResetFrequency::DAILY` - resets ordinal number at the beginning of new day

## Pattern Placeholders

| Placeholder     | Description                               | Example |
| --------------- | ----------------------------------------- | ------- |
| `{number}`      | generated, ordinal number                 |         |
| `{day}`         | day of passed date with leading zero      | 05      |
| `{month}`       | month of passed date with leading zero    | 03      |
| `{year}`        | full year of passed date                  | 2021    |
| `{day_short}`   | day of passed date without leading zero   | 5       |
| `{month_short}` | month of passed date without leading zero | 3       |
| `{year_short}`  | short year of passed date                 | 21      |