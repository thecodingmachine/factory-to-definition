Warning, experimental project!
------------------------------

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/factory-to-definition/badges/quality-score.png?b=1.0)](https://scrutinizer-ci.com/g/thecodingmachine/factory-to-definition/?branch=1.0)
[![Build Status](https://travis-ci.org/thecodingmachine/factory-to-definition.svg?branch=1.0)](https://travis-ci.org/thecodingmachine/factory-to-definition)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/factory-to-definition/badge.svg?branch=1.0&service=github)](https://coveralls.io/github/thecodingmachine/factory-to-definition?branch=1.0)


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

What can it do so far?
======================

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
- detect static values / parameters and transform those into `Interop\Container\Definition\ParameterDefinitionInterface` :

  ```php
  public static function scalar()
  {
      return 'my_value';
  }
  ```
  
  will map to a `ParameterDefinition` object containing `my_value` as a value.
  
And that's it for now!
