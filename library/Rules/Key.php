<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Validatable;

use function array_key_exists;
use function is_array;
use function is_scalar;

final class Key extends AbstractRelated
{
    public function __construct(mixed $reference, ?Validatable $rule = null, bool $mandatory = true)
    {
        if (!is_scalar($reference) || $reference === '') {
            throw new ComponentException('Invalid array key name');
        }

        parent::__construct($reference, $rule, $mandatory);
    }

    public function getReferenceValue(mixed $input): mixed
    {
        return $input[$this->getReference()];
    }

    public function hasReference(mixed $input): bool
    {
        return is_array($input) && array_key_exists($this->getReference(), $input);
    }
}
