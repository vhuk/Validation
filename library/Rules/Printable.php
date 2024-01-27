<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use function ctype_print;

final class Printable extends AbstractFilterRule
{
    protected function validateFilteredInput(string $input): bool
    {
        return ctype_print($input);
    }
}
