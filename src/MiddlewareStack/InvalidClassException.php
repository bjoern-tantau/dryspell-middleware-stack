<?php

namespace Dryspell\MiddlewareStack;

/**
 * InvalidClassException
 * Thrown when a given classname is not of the expected type.
 *
 * @package Dryspell\MiddlewareStack
 * @author Björn Tantau <bjoern@bjoern-tantau.de>
 */
class InvalidClassException extends \InvalidArgumentException
{
    const INVALID_ARGUMENT = 0;
    const INVALID_CLASSNAME = 1;
    const INVALID_OBJECT = 2;
}