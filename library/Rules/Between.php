<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Helpers\CanCompareValues;
use Respect\Validation\Message\Template;
use Respect\Validation\Rules\Core\Envelope;

#[Template(
    '{{name}} must be between {{minValue}} and {{maxValue}}',
    '{{name}} must not be between {{minValue}} and {{maxValue}}',
)]
final class Between extends Envelope
{
    use CanCompareValues;

    public function __construct(mixed $minValue, mixed $maxValue)
    {
        if ($this->toComparable($minValue) >= $this->toComparable($maxValue)) {
            throw new ComponentException('Minimum cannot be less than or equals to maximum');
        }

        parent::__construct(
            new AllOf(
                new GreaterThanOrEqual($minValue),
                new LessThanOrEqual($maxValue)
            ),
            [
                'minValue' => $minValue,
                'maxValue' => $maxValue,
            ]
        );
    }
}
