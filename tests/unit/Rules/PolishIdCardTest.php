<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use Respect\Validation\Test\RuleTestCase;

/**
 * @group rule
 * @covers \Respect\Validation\Rules\PolishIdCard
 */
final class PolishIdCardTest extends RuleTestCase
{
    /**
     * @return array<array{PolishIdCard, mixed}>
     */
    public static function providerForValidInput(): array
    {
        $rule = new PolishIdCard();

        return [
            [$rule, 'APH505567'],
            [$rule, 'AYE205410'],
            [$rule, 'AYW036733'],
        ];
    }

    /**
     * @return array<array{PolishIdCard, mixed}>
     */
    public static function providerForInvalidInput(): array
    {
        $rule = new PolishIdCard();

        return [
            [$rule, 'AAAAAAAAA'],
            [$rule, 'APH 505567'],
            [$rule, 'AYE205411'],
            [$rule, 'AYW036731'],
        ];
    }
}
