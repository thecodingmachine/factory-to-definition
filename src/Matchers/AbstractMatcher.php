<?php

namespace TheCodingMachine\ServiceProvider\Converter\Matchers;

use Assembly\Reference;
use BetterReflection\Reflection\ReflectionMethod;
use BetterReflection\Reflection\ReflectionParameter;
use PhpParser\Node;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Expr\Array_;
use TheCodingMachine\ServiceProvider\Converter\MatchingException;
use PhpParser\Node\Expr\ConstFetch;

abstract class AbstractMatcher implements Matcher
{
    /**
     * Returns the name of parameters passed to the method.
     * Typically:
     * [ 0=>container variable name, 1=>previous callback variable name ]
     * Value is null if no parameter passed.
     *
     * @param ReflectionMethod $method
     *
     * @return array
     */
    protected function getParametersVariableNames(ReflectionMethod $method) : array
    {
        $array = array_map(function (ReflectionParameter $parameter) {
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
     *
     * @return Return_
     */
    protected function assertIsReturnStatement(array $nodes) : Return_
    {
        $this->assert(count($nodes) === 1);
        $this->assert($nodes[0] instanceof Return_);

        return $nodes[0];
    }

    /**
     * Asserts that a method is a reference of the form $container->get('service_name')
     * Returns the service_name part.
     *
     * @param Node $node
     *
     * @return string
     */
    protected function assertIsReference(Node $node, string $containerVariableName) : string
    {
        $methodCall = $this->assertIsMethodCall($node, 'get');
        $this->assertIsVariable($methodCall->var, $containerVariableName);

        $this->assert(count($methodCall->args) === 1);
        $target = $this->assertIsString($methodCall->args[0]->value);

        return $target;
    }

    protected function isReference(Node $node, string $containerVariableName) : bool
    {
        try {
            $this->assertIsReference($node, $containerVariableName);
        } catch (MatchingException $e) {
            return false;
        }

        return true;
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

    /**
     * Returns a scalar or array value represented by those nodes.
     */
    protected function assertIsScalar(Node $node, $containerVariableName)
    {
        if ($node instanceof String_ || $node instanceof LNumber || $node instanceof DNumber) {
            return $node->value;
        }
        if ($node instanceof ConstFetch) {
            try {
                return $this->assertBoolConstant($node);
            } catch (MatchingException $e) {
                // ignore error and continue
            }
        }
        if ($node instanceof Array_) {
            $arr = [];
            foreach ($node->items as $item) {
                $key = $this->assertIsScalar($item->key, $containerVariableName);
                $value = $this->assertIsScalar($item->value, $containerVariableName);

                $arr[$key] = $value;
            }

            return $arr;
        }
        if ($containerVariableName && $this->isReference($node, $containerVariableName)) {
            $target = $this->assertIsReference($node, $containerVariableName);

            return new Reference($target);
        }
        throw MatchingException::mismatch();
    }

    protected function assertBoolConstant(ConstFetch $const) : bool
    {
        $this->assert($const->name instanceof Node\Name);
        $this->assert(count($const->name->parts) === 1);
        $name = $const->name->parts[0];
        if (strtolower($name) === 'true') {
            return true;
        } elseif (strtolower($name) === 'false') {
            return false;
        }
        throw MatchingException::mismatch();
    }

    protected function assert(bool $condition)
    {
        if (!$condition) {
            throw MatchingException::mismatch();
        }
    }
}
