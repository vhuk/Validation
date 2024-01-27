<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Exceptions;

final class MimetypeException extends ValidationException
{
    /**
     * @var array<string, array<string, string>>
     */
    protected array $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must have {{mimetype}} MIME type',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not have {{mimetype}} MIME type',
        ],
    ];
}
