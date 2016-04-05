<?php


namespace TheCodingMachine\ServiceProvider\Converter\Matchers;

use Assembly\Reference;
use BetterReflection\Reflection\ReflectionMethod;
use Interop\Container\Definition\DefinitionInterface;
use PhpParser\Node;


class AliasMatcher extends AbstractMatcher
{
    public function toDefinition(ReflectionMethod $method) : DefinitionInterface
    {
        list($containerVariableName, $previousCallbackVariableName) = $this->getParametersVariableNames($method);

        $returnStatement = $this->assertIsReturnStatement($method->getBodyAst());
        $methodCall = $this->assertIsMethodCall($returnStatement->expr, 'get');
        $this->assertIsVariable($methodCall->var, $containerVariableName);

        $this->assert(count($methodCall->args) === 1);
        $target = $this->assertIsString($methodCall->args[0]->value);

        return new Reference($target);
    }
}
