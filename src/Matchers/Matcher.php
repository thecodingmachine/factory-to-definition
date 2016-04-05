<?php


namespace TheCodingMachine\ServiceProvider\Converter\Matchers;

use BetterReflection\Reflection\ReflectionMethod;
use Interop\Container\Definition\DefinitionInterface;
use PhpParser\Node;
use TheCodingMachine\ServiceProvider\Converter\MatchingException;

interface Matcher
{
    /**
     * Tries to convert a method to a definition.
     * Throws a MatchingException if this fails.
     *
     * @param ReflectionMethod $method
     * @return DefinitionInterface
     * @throws MatchingException
     */
    public function toDefinition(ReflectionMethod $method) : DefinitionInterface;
}