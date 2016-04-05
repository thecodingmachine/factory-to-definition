<?php


namespace TheCodingMachine\ServiceProvider\Converter;


use Assembly\Reference;
use BetterReflection\Reflection\ReflectionClass;
use BetterReflection\Reflector\ClassReflector;
use BetterReflection\SourceLocator\Type\ComposerSourceLocator;
use Interop\Container\Definition\ParameterDefinitionInterface;
use Interop\Container\Definition\ReferenceDefinitionInterface;
use TheCodingMachine\ServiceProvider\Converter\Fixtures\TestServiceProvider;

class ServiceProviderConverterTest extends \PHPUnit_Framework_TestCase
{
    protected $reflector;

    protected function setUp()
    {
        $classLoader = require __DIR__.'/../vendor/autoload.php';
        $this->reflector = new ClassReflector(new ComposerSourceLocator($classLoader));
    }

    public function testAlias()
    {
        $converter = ServiceProviderConverter::create();

        $myClass = $this->reflector->reflect(TestServiceProvider::class);
        $aliasMethod = $myClass->getMethod('alias');

        $definition = $converter->toDefinition($aliasMethod);

        $this->assertInstanceOf(ReferenceDefinitionInterface::class, $definition);
        $this->assertEquals('foo', $definition->getTarget());
    }

    /**
     * @dataProvider scalarProvider
     */
    public function testScalar($methodName, $expectedValue)
    {
        $converter = ServiceProviderConverter::create();

        $myClass = $this->reflector->reflect(TestServiceProvider::class);

        $scalarMethod = $myClass->getMethod($methodName);
        $definition = $converter->toDefinition($scalarMethod);
        $this->assertInstanceOf(ParameterDefinitionInterface::class, $definition);
        $this->assertEquals($expectedValue, $definition->getValue());
    }

    public function scalarProvider()
    {
        return array(
            array('scalar1', 'foo'),
            array('scalar2', 12),
            array('scalar3', 12.42),
            array('scalar4', [
                'foo' => 'bar',
                'baz' => [
                    'foo' => 42
                ],
                'bool' => true,
                'ref' => new Reference('foo')
            ])
        );
    }

    public function testNotScalar()
    {
        $converter = ServiceProviderConverter::create();

        $myClass = $this->reflector->reflect(TestServiceProvider::class);

        $scalarMethod = $myClass->getMethod('notScalar');
        $definition = $converter->toDefinition($scalarMethod);
        $this->assertNotInstanceOf(ParameterDefinitionInterface::class, $definition);
    }
}
