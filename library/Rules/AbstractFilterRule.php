<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use function implode;
use function is_scalar;
use function str_replace;
use function str_split;

abstract class AbstractFilterRule extends AbstractRule
{
    private string $additionalChars;

    abstract protected function validateFilteredInput(string $input): bool;

    public function __construct(string ...$additionalChars)
    {
        $this->additionalChars = implode($additionalChars);
    }

    public function validate(mixed $input): bool
    {
        if (!is_scalar($input)) {
            return false;
        }

        $stringInput = (string) $input;
        if ($stringInput === '') {
            return false;
        }

        $filteredInput = $this->filter($stringInput);

        return $filteredInput === '' || $this->validateFilteredInput($filteredInput);
    }

    private function filter(string $input): string
    {
        return str_replace(str_split($this->additionalChars), '', $input);
    }
}
