<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Exceptions;

final class CallException extends NestedValidationException
{
    /**
     * @var array<string, array<string, string>>
     */
    protected array $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{input}} must be valid when executed with {{callable}}',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{input}} must not be valid when executed with {{callable}}',
        ],
    ];
}
