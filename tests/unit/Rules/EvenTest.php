<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use Respect\Validation\Test\RuleTestCase;

use const INF;

/**
 * @group rule
 * @covers \Respect\Validation\Rules\Even
 */
final class EvenTest extends RuleTestCase
{
    /**
     * @return array<array{Even, mixed}>
     */
    public static function providerForValidInput(): array
    {
        return [
            [new Even(), 2],
            [new Even(), -2],
            [new Even(), 0],
            [new Even(), 32],
        ];
    }

    /**
     * @return array<array{Even, mixed}>
     */
    public static function providerForInvalidInput(): array
    {
        return [
            [new Even(), ''],
            [new Even(), INF],
            [new Even(), 2.2],
            [new Even(), -5],
            [new Even(), -1],
            [new Even(), 1],
            [new Even(), 13],
        ];
    }
}
