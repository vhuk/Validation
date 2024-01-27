<?php

/*
 * Copyright (c) Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Validation;

use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\InvalidClassException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Message\Formatter;
use Respect\Validation\Message\ParameterStringifier;
use Respect\Validation\Message\Stringifier\KeepOriginalStringName;

use function array_merge;
use function lcfirst;
use function sprintf;
use function str_replace;
use function trim;
use function ucfirst;

final class Factory
{
    /**
     * @var string[]
     */
    private array $rulesNamespaces = ['Respect\\Validation\\Rules'];

    /**
     * @var string[]
     */
    private array $exceptionsNamespaces = ['Respect\\Validation\\Exceptions'];

    /**
     * @var callable
     */
    private $translator = 'strval';

    private ParameterStringifier $parameterStringifier;

    private static Factory $defaultInstance;

    public function __construct()
    {
        $this->parameterStringifier = new KeepOriginalStringName();
    }

    public static function getDefaultInstance(): self
    {
        if (!isset(self::$defaultInstance)) {
            self::$defaultInstance = new self();
        }

        return self::$defaultInstance;
    }

    public function withRuleNamespace(string $rulesNamespace): self
    {
        $clone = clone $this;
        $clone->rulesNamespaces[] = trim($rulesNamespace, '\\');

        return $clone;
    }

    public function withExceptionNamespace(string $exceptionsNamespace): self
    {
        $clone = clone $this;
        $clone->exceptionsNamespaces[] = trim($exceptionsNamespace, '\\');

        return $clone;
    }

    public function withTranslator(callable $translator): self
    {
        $clone = clone $this;
        $clone->translator = $translator;

        return $clone;
    }

    public function withParameterStringifier(ParameterStringifier $parameterStringifier): self
    {
        $clone = clone $this;
        $clone->parameterStringifier = $parameterStringifier;

        return $clone;
    }

    /**
     * @param mixed[] $arguments
     *
     * @throws ComponentException
     */
    public function rule(string $ruleName, array $arguments = []): Validatable
    {
        foreach ($this->rulesNamespaces as $namespace) {
            try {
                /** @var class-string<Validatable> $name */
                $name = $namespace . '\\' . ucfirst($ruleName);
                /** @var Validatable $rule */
                $rule = $this
                    ->createReflectionClass($name, Validatable::class)
                    ->newInstanceArgs($arguments);

                return $rule;
            } catch (ReflectionException $exception) {
                continue;
            }
        }

        throw new ComponentException(sprintf('"%s" is not a valid rule name', $ruleName));
    }

    /**
     * @param mixed[] $extraParams
     *
     * @throws ComponentException
     */
    public function exception(Validatable $validatable, mixed $input, array $extraParams = []): ValidationException
    {
        $formatter = new Formatter($this->translator, $this->parameterStringifier);
        $reflection = new ReflectionObject($validatable);
        $ruleName = $reflection->getShortName();
        $params = ['input' => $input] + $extraParams + $this->extractPropertiesValues($validatable, $reflection);
        $id = lcfirst($ruleName);
        if ($validatable->getName() !== null) {
            $id = $params['name'] = $validatable->getName();
        }
        $exceptionNamespace = str_replace('\\Rules', '\\Exceptions', $reflection->getNamespaceName());
        foreach (array_merge([$exceptionNamespace], $this->exceptionsNamespaces) as $namespace) {
            try {
                /** @var class-string<ValidationException> $exceptionName */
                $exceptionName = $namespace . '\\' . $ruleName . 'Exception';

                return $this->createValidationException(
                    $exceptionName,
                    $id,
                    $input,
                    $params,
                    $formatter
                );
            } catch (ReflectionException $exception) {
                continue;
            }
        }

        return new ValidationException($input, $id, $params, $formatter);
    }

    public static function setDefaultInstance(self $defaultInstance): void
    {
        self::$defaultInstance = $defaultInstance;
    }

    /**
     * @param class-string $name
     * @param class-string $parentName
     *
     * @throws InvalidClassException
     * @throws ReflectionException
     *
     * @return ReflectionClass<ValidationException|Validatable|object>
     */
    private function createReflectionClass(string $name, string $parentName): ReflectionClass
    {
        $reflection = new ReflectionClass($name);
        if (!$reflection->isSubclassOf($parentName) && $parentName !== $name) {
            throw new InvalidClassException(sprintf('"%s" must be an instance of "%s"', $name, $parentName));
        }

        if (!$reflection->isInstantiable()) {
            throw new InvalidClassException(sprintf('"%s" must be instantiable', $name));
        }

        return $reflection;
    }

    /**
     * @param class-string<ValidationException> $exceptionName
     *
     * @param mixed[] $params
     *
     * @throws InvalidClassException
     * @throws ReflectionException
     */
    private function createValidationException(
        string $exceptionName,
        string $id,
        mixed $input,
        array $params,
        Formatter $formatter
    ): ValidationException {
        /** @var ValidationException $exception */
        $exception = $this
            ->createReflectionClass($exceptionName, ValidationException::class)
            ->newInstance($input, $id, $params, $formatter);
        if (isset($params['template'])) {
            $exception->updateTemplate($params['template']);
        }

        return $exception;
    }

    /**
     * @param ReflectionObject|ReflectionClass<Validatable> $reflection
     * @return mixed[]
     */
    private function extractPropertiesValues(Validatable $validatable, ReflectionClass $reflection): array
    {
        $values = [];
        foreach ($reflection->getProperties() as $property) {
            if (!$property->isInitialized($validatable)) {
                continue;
            }

            $propertyValue = $property->getValue($validatable);
            if ($propertyValue === null) {
                continue;
            }

            $values[$property->getName()] = $propertyValue;
        }

        $parentReflection = $reflection->getParentClass();
        if ($parentReflection !== false) {
            return $values + $this->extractPropertiesValues($validatable, $parentReflection);
        }

        return $values;
    }
}
