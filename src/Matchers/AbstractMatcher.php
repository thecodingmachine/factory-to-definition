<?php


namespace TheCodingMachine\ServiceProvider\Converter\Matchers;

use BetterReflection\Reflection\ReflectionMethod;
use BetterReflection\Reflection\ReflectionParameter;
use PhpParser\Node;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;


abstract class AbstractMatcher implements Matcher
{
    /**
     * Returns the name of parameters passed to the method.
     * Typically:
     * [ 0=>container variable name, 1=>previous callback variable name ]
     * Value is null if no parameter passed.
     *
     * @param ReflectionMethod $method
     * @return array
     */
    protected function getParametersVariableNames(ReflectionMethod $method) : array
    {
        $array = array_map(function(ReflectionParameter $parameter) {
            return $parameter->getName();
        }, $method->getParameters());

        while (count($array) < 2) {
            $array[] = null;
        }
        return $array;
    }

    /**
     * Assert the array of nodes contains only a return statement.
     * Returns that statement.
     *
     * @param array $nodes
     * @return Return_
     */
    protected function assertIsReturnStatement(array $nodes) : Return_
    {
        $this->assert(count($nodes) === 1);
        $this->assert($nodes[0] instanceof Return_);
        return $nodes[0];
    }

    protected function assertIsMethodCall(Node $node, $expectedMethodName = null) : MethodCall
    {
        $this->assert($node instanceof MethodCall);
        /* @var node MethodCall */
        $this->assert($expectedMethodName === null || $expectedMethodName === $node->name);
        return $node;
    }

    protected function assertIsVariable(Node $node, $expectedVariableName = null) : Variable
    {
        $this->assert($node instanceof Variable);
        /* @var node Variable */
        $this->assert($expectedVariableName === null || $expectedVariableName === $node->name);
        return $node;
    }

    protected function assertIsString(Node $node) : string
    {
        $this->assert($node instanceof String_);
        /* @var node String_ */
        return $node->value;
    }

    protected function assert(bool $condition)
    {
        if (!$condition) {
            throw MatchingException::mismatch();
        }
    }
}