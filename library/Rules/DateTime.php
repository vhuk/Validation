<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use DateTimeInterface;
use Respect\Validation\Helpers\CanValidateDateTime;

use function date;
use function is_scalar;
use function strtotime;

final class DateTime extends AbstractRule
{
    use CanValidateDateTime;

    private string $sample;

    public function __construct(private ?string $format = null)
    {
        $this->sample = date($format ?: 'c', strtotime('2005-12-30 01:02:03'));
    }

    public function validate(mixed $input): bool
    {
        if ($input instanceof DateTimeInterface) {
            return $this->format === null;
        }

        if (!is_scalar($input)) {
            return false;
        }

        if ($this->format === null) {
            return strtotime((string) $input) !== false;
        }

        return $this->isDateTime($this->format, (string) $input);
    }
}
