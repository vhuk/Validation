<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use function is_string;
use function preg_match;

/**
 * @see http://semver.org/
 */
final class Version extends AbstractRule
{
    public function validate(mixed $input): bool
    {
        if (!is_string($input)) {
            return false;
        }

        return preg_match('/^[0-9]+\.[0-9]+\.[0-9]+([+-][^+-][0-9A-Za-z-.]*)?$/', $input) > 0;
    }
}
