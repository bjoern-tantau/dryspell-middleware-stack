<?php

namespace Dryspell;


use Dryspell\MiddlewareStack\InvalidClassException;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Manage middlewares
 * May be used by ServerRequestHandler to get the next middleware
 *
 * @package Dryspell
 * @author Björn Tantau <bjoern@bjoern-tantau.de>
 */
interface MiddlewareStackInterface
{
    /**
     * Add a middleware to the stack
     * Accepts a classname or a MiddlewareInterface object.
     *
     * @param string|MiddlewareInterface $middleware
     * @return MiddlewareStackInterface
     * @throws InvalidClassException Thrown when the given string is not a class implementing MiddlewareInterface
     */
    function add($middleware): self;

    /**
     * Get the next Middleware from the stack
     *
     * @return MiddlewareInterface|null
     */
    function next(): ?MiddlewareInterface;
}