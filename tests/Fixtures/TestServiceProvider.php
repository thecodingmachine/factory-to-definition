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
}
