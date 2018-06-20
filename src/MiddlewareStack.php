<?php

namespace Dryspell;

use Dryspell\MiddlewareStack\InvalidClassException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Container\ContainerInterface;

/**
 * Manage middlewares
 * May be used by ServerRequestHandler to get the next middleware
 *
 * @package Dryspell
 * @author Björn Tantau <bjoern@bjoern-tantau.de>
 */
class MiddlewareStack implements MiddlewareStackInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $stack = [];

    /**
     * @var int
     */
    private $current = 0;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Add a middleware to the stack
     * Accepts a classname or a MiddlewareInterface object.
     *
     * @param string|MiddlewareInterface $middleware
     * @return MiddlewareStackInterface
     * @throws InvalidClassException Thrown when the given string is not a class implementing MiddlewareInterface
     */
    function add($middleware): MiddlewareStackInterface
    {
        if (!is_string($middleware) && !is_object($middleware)) {
            throw new InvalidClassException('Invalid argument. Expected a class or an object implementing MiddlewareInterface.', InvalidClassException::INVALID_ARGUMENT);
        }
        if (is_string($middleware) && !is_subclass_of($middleware, MiddlewareInterface::class)) {
            throw new InvalidClassException('Invalid classname \'' . $middleware . '\'. Expected a class implementing MiddlewareInterface.', InvalidClassException::INVALID_CLASSNAME);
        }
        if (is_object($middleware) && !($middleware instanceof MiddlewareInterface)) {
            throw new InvalidClassException('Invalid object of type ' . get_class($middleware) . '. Expected an object implementing MiddlewareInterface.', InvalidClassException::INVALID_OBJECT);
        }
        $this->stack[] = $middleware;
        return $this;
    }

    /**
     * Get the next Middleware from the stack
     *
     * @return MiddlewareInterface|null
     */
    function next(): ?MiddlewareInterface
    {
        if (isset($this->stack[$this->current])) {
            $middleware = $this->stack[$this->current];
            if (is_string($middleware)) {
                $middleware = $this->container->get($middleware);
            }
            $this->current++;
            return $middleware;
        }
        return null;
    }
}