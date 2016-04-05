<?php


namespace TheCodingMachine\ServiceProvider\Converter;


use BetterReflection\Reflection\ReflectionClass;
use BetterReflection\Reflector\ClassReflector;
use BetterReflection\SourceLocator\Type\ComposerSourceLocator;
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
}
