<?php

namespace Tests\Unit\IOC;

use App\IOC\Resolver;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{

    public function testResolveClassWithDependencyThatDoesNotExistAndEmptyArgsArrReturnsNull()
    {
        $resolver = new Resolver();
        $actual = $resolver->resolveClass("classThatDoesNotExist", []);
        $this->assertNull($actual);
    }

    public function testResolveClassWithDependencyThatDoesNotExistAndSomeArgsArrReturnsNull()
    {
        $resolver = new Resolver();
        $actual = $resolver->resolveClass("classThatDoesNotExist", ["testArg", true]);
        $this->assertNull($actual);
    }
}
