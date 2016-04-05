<?php


namespace TheCodingMachine\ServiceProvider\Converter;

use BetterReflection\Reflection\ReflectionMethod;
use Interop\Container\Definition\DefinitionInterface;
use PhpParser\Node;
use TheCodingMachine\ServiceProvider\Converter\Matchers\AliasMatcher;
use TheCodingMachine\ServiceProvider\Converter\Matchers\Matcher;

/**
 * Casts service providers into container definitions
 */
class ServiceProviderConverter
{
    /**
     * @var array|Matchers\Matcher[]
     */
    private $matchers;

    /**
     * ServiceProviderConverter constructor.
     * @param Matcher[] $matchers
     */
    public function __construct(array $matchers)
    {
        $this->matchers = $matchers;
    }

    public static function create() : ServiceProviderConverter
    {
        return new self([
            new AliasMatcher()
        ]);
    }

    public function toDefinition(ReflectionMethod $method) : DefinitionInterface
    {
        foreach ($this->matchers as $matcher) {
            try {
                return $matcher->toDefinition($method);
            } catch (MatchingException $e) {
                // continue
            }
        }

        // If no matcher matches... let's cast to a factory
        // TODO
    }



}
