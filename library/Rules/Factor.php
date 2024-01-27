<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use function abs;
use function is_integer;
use function is_numeric;

final class Factor extends AbstractRule
{
    public function __construct(private int $dividend)
    {
    }

    public function validate(mixed $input): bool
    {
        // Every integer is a factor of zero, and zero is the only integer that
        // has zero for a factor.
        if ($this->dividend === 0) {
            return true;
        }

        // Factors must be integers that are not zero.
        if (!is_numeric($input) || (int) $input != $input || $input == 0) {
            return false;
        }

        $input = (int) abs((int) $input);
        $dividend = (int) abs($this->dividend);

        // The dividend divided by the input must be an integer if input is a
        // factor of the dividend.
        return is_integer($dividend / $input);
    }
}
