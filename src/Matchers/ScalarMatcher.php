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
 * Note: this also accepts arrays and references in arrays
 *
 */
class ScalarMatcher extends AbstractMatcher
{
    public function toDefinition(ReflectionMethod $method) : DefinitionInterface
    {
        list($containerVariableName, $previousCallbackVariableName) = $this->getParametersVariableNames($method);

        $returnStatement = $this->assertIsReturnStatement($method->getBodyAst());
        $value = $this->assertIsScalar($returnStatement->expr, $containerVariableName);

        return new ParameterDefinition($value);
    }
}
