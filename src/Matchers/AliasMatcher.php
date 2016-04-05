<?php

namespace TheCodingMachine\ServiceProvider\Converter\Matchers;

use Assembly\Reference;
use BetterReflection\Reflection\ReflectionMethod;
use Interop\Container\Definition\DefinitionInterface;

class AliasMatcher extends AbstractMatcher
{
    public function toDefinition(ReflectionMethod $method) : DefinitionInterface
    {
        list($containerVariableName, $previousCallbackVariableName) = $this->getParametersVariableNames($method);

        $this->assert($containerVariableName !== null);

        $returnStatement = $this->assertIsReturnStatement($method->getBodyAst());
        $target = $this->assertIsReference($returnStatement->expr, $containerVariableName);

        return new Reference($target);
    }
}
