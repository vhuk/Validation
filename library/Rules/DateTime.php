<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use DateTimeInterface;
use Respect\Validation\Helpers\CanValidateDateTime;
use Respect\Validation\Message\Template;

use function date;
use function is_scalar;
use function strtotime;

#[Template(
    '{{name}} must be a valid date/time',
    '{{name}} must not be a valid date/time',
    self::TEMPLATE_STANDARD,
)]
#[Template(
    '{{name}} must be a valid date/time in the format {{sample}}',
    '{{name}} must not be a valid date/time in the format {{sample}}',
    self::TEMPLATE_FORMAT,
)]
final class DateTime extends AbstractRule
{
    use CanValidateDateTime;

    public const TEMPLATE_FORMAT = '__format__';

    public function __construct(
        private readonly ?string $format = null
    ) {
    }

    public function validate(mixed $input): bool
    {
        if ($input instanceof DateTimeInterface) {
            return $this->format === null;
        }

        if (!is_scalar($input)) {
            return false;
        }

        if ($this->format === null) {
            return strtotime((string) $input) !== false;
        }

        return $this->isDateTime($this->format, (string) $input);
    }

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array
    {
        return ['sample' => date($this->format ?: 'c', strtotime('2005-12-30 01:02:03'))];
    }

    protected function getStandardTemplate(mixed $input): string
    {
        return $this->format !== null ? self::TEMPLATE_FORMAT : self::TEMPLATE_STANDARD;
    }
}
