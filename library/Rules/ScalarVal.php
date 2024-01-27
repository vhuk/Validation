<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use function is_scalar;

final class ScalarVal extends AbstractRule
{
    public function validate(mixed $input): bool
    {
        return is_scalar($input);
    }
}
