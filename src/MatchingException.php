<?php


namespace TheCodingMachine\ServiceProvider\Converter;


class MatchingException extends \RuntimeException
{
    public static function mismatch() : MatchingException
    {
        return new MatchingException('Mismatch between expected AST and received AST.');
    }
}