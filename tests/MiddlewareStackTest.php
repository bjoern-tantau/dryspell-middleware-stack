<?php

namespace Dryspell\Tests;

use Dryspell\MiddlewareStack;
use Psr\Http\Server\MiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Tests for MiddlewareStack
 * @package Dryspell\Tests
 * @author Björn Tantau <bjoern@bjoern-tantau.de>
 */
class MiddlewareStackTest extends TestCase
{

    /**
     * Tests that a middleware can be added by classname and that an object is returned by next
     *
     * @test
     */
    public function testCanAddAndGetMiddlewareByClassnameAndGetNextOneEmpty()
    {
        $middlewareMock = $this->getMiddlewareMock();
        $containerMock = $this->getContainerMock();
        $containerMock->expects($this->once())
            ->method('get')
            ->with(get_class($middlewareMock))
            ->willReturn($middlewareMock);
        $stack = new MiddlewareStack($containerMock);
        $actual = $stack->add(get_class($middlewareMock));
        $this->assertEquals($stack, $actual);

        $actual = $stack->next();
        $this->assertEquals($middlewareMock, $actual);

        $actual = $stack->next();
        $this->assertNull($actual);
    }

    /**
     * Get a Mock for MiddlewareInterface
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|MiddlewareInterface
     */
    private function getMiddlewareMock()
    {
        $mock = $this->getMockBuilder(MiddlewareInterface::class)
            ->getMock();
        return $mock;
    }

    /**
     * Get a Mock for ContainerInterface
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|ContainerInterface
     */
    private function getContainerMock()
    {
        $mock = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();
        return $mock;
    }

    /**
     * Tests that a middleware can be added by object
     *
     * @test
     */
    public function testCanAddAndGetMiddlewareByObjectAndGetNextOneEmpty()
    {
        $middlewareMock = $this->getMiddlewareMock();
        $containerMock = $this->getContainerMock();
        $stack = new MiddlewareStack($containerMock);
        $actual = $stack->add($middlewareMock);
        $this->assertEquals($stack, $actual);

        $actual = $stack->next();
        $this->assertEquals($middlewareMock, $actual);

        $actual = $stack->next();
        $this->assertNull($actual);
    }

    /**
     * @test
     * @expectedException \Dryspell\MiddlewareStack\InvalidClassException
     * @expectedExceptionCode 0
     * @expectedExceptionMessage Invalid argument. Expected a class or an object implementing MiddlewareInterface.
     */
    public function testIsInvalidClassExceptionThrownOnInvalidArgument()
    {
        $containerMock = $this->getContainerMock();
        $stack = new MiddlewareStack($containerMock);
        $stack->add(null);
    }

    /**
     * @test
     * @expectedException \Dryspell\MiddlewareStack\InvalidClassException
     * @expectedExceptionCode 1
     * @expectedExceptionMessage Invalid classname 'foo'. Expected a class implementing MiddlewareInterface.
     */
    public function testIsInvalidClassExceptionThrownOnClassname()
    {
        $containerMock = $this->getContainerMock();
        $stack = new MiddlewareStack($containerMock);
        $stack->add('foo');
    }

    /**
     * @test
     * @expectedException \Dryspell\MiddlewareStack\InvalidClassException
     * @expectedExceptionCode 2
     * @expectedExceptionMessage Invalid object of type Dryspell\MiddlewareStack. Expected an object implementing MiddlewareInterface.
     */
    public function testIsInvalidClassExceptionThrownOnObject()
    {
        $containerMock = $this->getContainerMock();
        $stack = new MiddlewareStack($containerMock);
        $stack->add($stack);
    }
}