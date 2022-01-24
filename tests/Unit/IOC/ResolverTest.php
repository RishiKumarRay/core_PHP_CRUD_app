<?php

namespace Tests\Unit\IOC;

use App\IOC\Resolver;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Fixtures\ClassWithDefaultParams;
use Tests\Unit\Fixtures\EmptyClass;

class ResolverTest extends TestCase
{

    public function testResolveClassWithDependencyThatDoesNotExistAndEmptyArgsArrReturnsNull()
    {
        $resolver = new Resolver();
        $actual = $resolver->resolveClass("classThatDoesNotExist");
        $this->assertNull($actual);
    }

    public function testResolveClassWithDependencyThatDoesNotExistAndSomeArgsArrReturnsNull()
    {
        $resolver = new Resolver();
        $actual = $resolver->resolveClass("classThatDoesNotExist", ["testArg", true]);
        $this->assertNull($actual);
    }

    public function testResolveClassWithDependencyThatDoesNotHaveAConstructorReturnsTheClassInstance()
    {
        $expected = EmptyClass::class;
        $resolver = new Resolver();
        $actual = $resolver->resolveClass(EmptyClass::class);
        $this->assertNotNull($actual);
        $this->assertInstanceOf($expected, $actual);
    }

    public function testResolveClassWithDependencyThatHasAConstructorWithDefaultParamsAndNoParamsProvidedUsesTheDefaultParams()
    {
        $resolver = new Resolver();
        $actual = $resolver->resolveClass(ClassWithDefaultParams::class);
        $this->assertEquals("default", $actual->someProp);
        $this->assertEquals("other default", $actual->someOtherProp);
    }

    public function testResolveClassWithOverridenDefaultParamsWorks()
    {
        $resolver = new Resolver();
        $actual = $resolver->resolveClass(ClassWithDefaultParams::class, ["override one", "override two"]);
        $this->assertEquals("override one", $actual->someProp);
        $this->assertEquals("override two", $actual->someOtherProp);
    }

    public function testResolveClassWithParamsWhenClassDoesNotExpectAnyParamsThrowsAnIllegalClassInstantiationException()
    {
        // TODO
    }

    public function testResolveClassResolvesSingletonsThatDontRequireParams()
    {
        // TODO
    }

    public function testResolveClassResolvesSingletonsThatRequireParams()
    {
        // TODO
    }
}
