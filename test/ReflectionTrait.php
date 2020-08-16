<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest;

use ReflectionClass;
use ReflectionException;

trait ReflectionTrait
{

    /**
     * @param object $classToReflect
     * @param array $properties
     * @throws ReflectionException
     */
    public function reflectProperties(
        object $classToReflect,
        array $properties
    ): void {
        $reflection = new ReflectionClass($classToReflect);

        foreach ($properties as $property => $value) {
            $reflectionProperty = $reflection->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($classToReflect, $value);
        }
    }


    /**
     * @param object $classToReflect
     * @param array $methodNames
     * @throws ReflectionException
     */
    public function reflectMethods(
        object $classToReflect,
        array $methodNames
    ): void {
        $reflection = new ReflectionClass($classToReflect);

        foreach ($methodNames as $methodName) {
            $method = $reflection->getMethod($methodName);
            $method->setAccessible(true);
        }
    }
}
