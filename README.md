Warning, experimental project!
------------------------------

What is it?
===========

This project is a crazy attempt to create a bridge between [container-interop's service providers](http://github.com/container-interop/service-provider)
and [container-interop's definition interfaces](http://github.com/container-interop/definition-interop/).

But why?
========

It should be pretty easy to take container definitions and compile them into a service provider class (this is a 
standard compilation pass). What I'm trying to do here is the opposite. Take a service provider (i.e. a set of 
factory methods in pure PHP code), and cast those into service definitions.

Let's be clear, this is not obviously not always possible. Still, I'm trying to do it for very simple use cases.

Why?

- Because it's fun
- Because it might bring very slight improvements to performances for compiled container (they can optimize container definition, but they cannot optimize factory methods).

What can it do so far
=====================

Not much, really!

It can:

- detect aliases and transform those into `Interop\Container\Definition\ReferenceDefinitionInterface` :
  ```php
  public static function alias(ContainerInterface $container)
  {
      return $container->get('foo');
  }
  ```
  will map to a `ReferenceDefinition` object pointing to the `foo` container entry.
  
And that's it for now!
