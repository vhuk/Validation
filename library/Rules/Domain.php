<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use Respect\Validation\Exceptions\DomainException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;

use function array_merge;
use function array_pop;
use function count;
use function explode;
use function iterator_to_array;
use function mb_substr_count;

final class Domain extends AbstractRule
{
    private Validatable $genericRule;

    private Validatable $tldRule;

    private Validatable $partsRule;

    public function __construct(bool $tldCheck = true)
    {
        $this->genericRule = $this->createGenericRule();
        $this->tldRule = $this->createTldRule($tldCheck);
        $this->partsRule = $this->createPartsRule();
    }

    public function assert(mixed $input): void
    {
        $exceptions = [];

        $this->collectAssertException($exceptions, $this->genericRule, $input);
        $this->throwExceptions($exceptions, $input);

        $parts = explode('.', (string) $input);
        if (count($parts) >= 2) {
            $this->collectAssertException($exceptions, $this->tldRule, array_pop($parts));
        }

        foreach ($parts as $part) {
            $this->collectAssertException($exceptions, $this->partsRule, $part);
        }

        $this->throwExceptions($exceptions, $input);
    }

    public function validate(mixed $input): bool
    {
        try {
            $this->assert($input);
        } catch (ValidationException $exception) {
            return false;
        }

        return true;
    }

    public function check(mixed $input): void
    {
        try {
            $this->assert($input);
        } catch (NestedValidationException $exception) {
            /** @var ValidationException $childException */
            foreach ($exception as $childException) {
                throw $childException;
            }

            throw $exception;
        }
    }

    /**
     * @param ValidationException[] $exceptions
     */
    private function collectAssertException(array &$exceptions, Validatable $validator, mixed $input): void
    {
        try {
            $validator->assert($input);
        } catch (NestedValidationException $nestedValidationException) {
            $exceptions = array_merge(
                $exceptions,
                iterator_to_array($nestedValidationException)
            );
        } catch (ValidationException $validationException) {
            $exceptions[] = $validationException;
        }
    }

    private function createGenericRule(): Validatable
    {
        return new AllOf(
            new StringType(),
            new NoWhitespace(),
            new Contains('.'),
            new Length(3)
        );
    }

    private function createTldRule(bool $realTldCheck): Validatable
    {
        if ($realTldCheck) {
            return new Tld();
        }

        return new AllOf(
            new Not(new StartsWith('-')),
            new NoWhitespace(),
            new Length(2)
        );
    }

    private function createPartsRule(): Validatable
    {
        return new AllOf(
            new Alnum('-'),
            new Not(new StartsWith('-')),
            new AnyOf(
                new Not(new Contains('--')),
                new Callback(static function ($str) {
                    return mb_substr_count($str, '--') == 1;
                })
            ),
            new Not(new EndsWith('-'))
        );
    }

    /**
     * @param ValidationException[] $exceptions
     */
    private function throwExceptions(array $exceptions, mixed $input): void
    {
        if (count($exceptions)) {
            /** @var DomainException $domainException */
            $domainException = $this->reportError($input);
            $domainException->addChildren($exceptions);

            throw $domainException;
        }
    }
}
