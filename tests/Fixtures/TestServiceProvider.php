<?php

namespace TheCodingMachine\ServiceProvider\Converter\Fixtures;

use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;

class TestServiceProvider implements ServiceProvider
{
    /**
     * @return array
     */
    public static function getServices()
    {
        // TODO: Implement getServices() method.
    }

    public static function alias(ContainerInterface $container)
    {
        return $container->get('foo');
    }

    public static function scalar1()
    {
        return 'foo';
    }

    public static function scalar2()
    {
        return 12;
    }

    public static function scalar3()
    {
        return 12.42;
    }

    public static function scalar4(ContainerInterface $container)
    {
        return [
            'foo' => 'bar',
            'baz' => [
                'foo' => 42,
            ],
            'bool' => true,
            'bool2' => false,
            'ref' => $container->get('foo'),
        ];
    }

    public static function notScalar(ContainerInterface $container)
    {
        return __DIR__;
    }

    public static function notScalar2()
    {
        return M_LN2;
    }
}
