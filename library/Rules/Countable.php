<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use Countable as CountableInterface;
use Respect\Validation\Message\Template;
use Respect\Validation\Rules\Core\Simple;

use function is_array;

#[Template(
    '{{name}} must be countable',
    '{{name}} must not be countable',
)]
final class Countable extends Simple
{
    public function validate(mixed $input): bool
    {
        return is_array($input) || $input instanceof CountableInterface;
    }
}
