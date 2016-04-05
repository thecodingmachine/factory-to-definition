<?php


namespace TheCodingMachine\ServiceProvider\Converter\Matchers;

use Assembly\ParameterDefinition;
use BetterReflection\Reflection\ReflectionMethod;
use Interop\Container\Definition\DefinitionInterface;
use PhpParser\Node;

/**
 * Maps "pure" parameter factories.
 *
 * For instance:
 *
 * function() {
 *  return "foo";
 * }
 *
 */
class ScalarMatcher extends AbstractMatcher
{
    public function toDefinition(ReflectionMethod $method) : DefinitionInterface
    {
        $returnStatement = $this->assertIsReturnStatement($method->getBodyAst());
        $value = $this->assertIsScalar($returnStatement->expr);

        return new ParameterDefinition($value);
    }
}
