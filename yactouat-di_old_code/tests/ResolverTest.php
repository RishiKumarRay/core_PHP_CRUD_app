<?php

use Yactouat\DI\Container;
use Yactouat\DI\Resolver;
use Yactouat\DI\Tests\Fixtures\DummySingleton;
use Yactouat\DI\Tests\Fixtures\DummyTestClass;
use Yactouat\DI\Tests\Fixtures\DummyTestClassWithDynamicParam;
use Yactouat\DI\Tests\Fixtures\DummyTestClassRussianDoll;
use Yactouat\DI\Tests\Fixtures\DummyTestClassRussianDollWithMixedParams;
use Yactouat\DI\Tests\Fixtures\DummyTestClassWithDefaultConstructorParameters;
use Yactouat\DI\Tests\Fixtures\DummyTestClassWithTypeHintedDependencies;
use Yactouat\DI\Tests\Fixtures\DummyTestClassWithoutConstructorParameters;
use Yactouat\Exceptions\DI\ResolverClassResolutionException;

require_once "./tests/Fixtures/DummyFunctions.php";
require_once "./tests/Fixtures/DummyVars.php";

/**
 * @covers \Yactouat\DI\Resolver
 */
class ResolverTest extends PHPUnit\Framework\TestCase {

    protected Resolver $_resolver;

    protected function setUp(): void {
        $this->_resolver = new Resolver();
    }

    protected function tearDown(): void
    {
        unset($this->_resolver);
    }

    public function testResolve_withNonExistingClass_throwsReflectionException() {
        // assert
        $this->expectException(ResolverClassResolutionException::class);
        $this->expectExceptionMessage("Class NonExistingClass cannot be resolved because it doesn't exist.");
        // act
        $this->_resolver->resolve("NonExistingClass");
    }

    public function testResolve_withExistingClass_returnsClassInstance() {
        // arrange
        $expected = DummyTestClass::class;
        // act
        $actual = $this->_resolver->resolve(DummyTestClass::class);
        // assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testResolve_withKnownClassDependenciesWithoutParameters_areResolved() {
        // arrange
        $expected = DummyTestClassWithoutConstructorParameters::class;
        // act
        $actual = $this->_resolver->resolve(DummyTestClassWithoutConstructorParameters::class);
        // assert
        $this->assertInstanceOf($expected, $actual, "known class dependencies can be resolved without parameters");
    } 
    
    public function testResolve_withKnownClassDependenciesWithoutParametersBecauseDefaultParameters_areResolved() {
        // arrange
        $expected = DummyTestClassWithDefaultConstructorParameters::class;
        // act
        $actual = $this->_resolver->resolve(DummyTestClassWithDefaultConstructorParameters::class);
        // assert
        $this->assertInstanceOf($expected, $actual, "known class dependencies can be resolved with their default parameters");
        $this->assertTrue($actual->prop1 === "test");
        $this->assertTrue($actual->prop2 === 2);
    }

    public function testResolve_withKnownClassDependencyResolutionAndNonInitializedClassParameters_works() {
        // arrange
        $expected = DummyTestClassWithTypeHintedDependencies::class;
        // act
        $actual = $this->_resolver->resolve(DummyTestClassWithTypeHintedDependencies::class);
        // assert
        $this->assertInstanceOf($expected, $actual, "known class dependencies can be resolved with type hinted class dependencies");
        $this->assertInstanceOf(DummyTestClass::class, $actual->dmtc, "known class dependencies can be resolved with type hinted class dependency without constructor");
        $this->assertInstanceOf(DummyTestClassWithoutConstructorParameters::class, $actual->dmtcwcp, "known class dependencies can be resolved with type hinted class dependency with constructor but without parameters");
        $this->assertInstanceOf(DummyTestClassWithDefaultConstructorParameters::class, $actual->dmtcwdcp, "known class dependencies can be resolved with type hinted class dependency with default constructor parameters");
    }

    public function testResolve_withDependencyResolvedWithDynamicClassParam_works() {
        // arrange
        $expected = "some dynamic value";
        // act
        $actual = $this->_resolver->resolve(DummyTestClassRussianDoll::class, [new DummyTestClassWithDynamicParam($expected)]);
        // assert
        $this->assertInstanceOf(DummyTestClassRussianDoll::class, $actual);
        $this->assertSame($expected, $actual->dmtc->value);
    }

    public function testResolve_withResolutionInvolvingDynamicMixedParams_works() {
        // act
        $actual = $this->_resolver->resolve(DummyTestClassRussianDollWithMixedParams::class, [new DummyTestClassWithDynamicParam("some dynamic value"),["val1", "val2"], "string param"]);
        // assert
        $this->assertInstanceOf(DummyTestClassRussianDollWithMixedParams::class, $actual);
        $this->assertSame("some dynamic value", $actual->dmtc->value);
        $this->assertSame(["val1", "val2"], $actual->a);
        $this->assertSame("string param", $actual->s);
    }

    public function testKnownClassDependenciesAreResolvedFromContainer() {
        // arrange
        $container = Container::getInstance();
        $expected = new DummyTestClass();
        $container->addEntry(DummyTestClass::class, $expected);
        // act
        $actual = $this->_resolver->resolve(DummyTestClass::class);
        // assert
        $this->assertSame($expected, $actual, "resolved dependency has been taken from container");
        // tear down
        $container->clear();
        unset($container);
    }

    public function testKnownResolvedDependenciesCanBeFunctions() {
        // arrange
        $expected = $this->_resolver->resolve("dummyFunction2");
        // act + assert
        $this->assertSame($expected, "dummy function 2");
    }

    public function testKnownResolvedDependenciesCanBeArrowFunctions() {
        // arrange
        $expected = "dummy function";
        // act
        $actual = $this->_resolver->resolve("dummyFunction");
        // assert
        $this->assertSame($expected, $actual);
    }

    public function testKnownResolvedDependenciesCanBeOfAnyScalarType() {
        $this->assertTrue($this->_resolver->resolve("testBool") === true);
        $this->assertEquals(36, $this->_resolver->resolve("testInt"));
        $this->assertEquals(36.03, $this->_resolver->resolve("testFloat"));
        $this->assertSame("test str", $this->_resolver->resolve("testStr"));
    }

    public function testKnownResolvedDependenciesCanBeArrays() {
        $this->assertSame(["testKey" => "testVal"], $this->_resolver->resolve("testArr"));
    }

    public function testKnownResolvedDependenciesFromContainerCanBeFunctions() {
        // arrange
        $container = Container::getInstance();
        $expected = fn() => true;
        $container->addEntry("expected", $expected);
        // act
        $actual = $this->_resolver->resolve("expected");
        // assert
        $this->assertSame($expected, $actual, "resolved function dependency has been taken from container");
        $this->assertIsCallable($actual);
        $this->assertTrue($actual() === true);
        // tear down
        $container->clear();
        unset($container);
    }

    public function testKnownDependenciesResolvedFromContainerCanBeOfAnyScalarType() {
        // arrange
        $container = Container::getInstance();
        // act
        $container->addEntry("testBoolFromContainer", true);
        $container->addEntry("testIntFromContainer", 36);
        $container->addEntry("testFloatFromContainer", 36.03);
        $container->addEntry("testStrFromContainer", "test str");
        // assert
        $this->assertTrue($this->_resolver->resolve("testBoolFromContainer") === true);
        $this->assertEquals(36, $this->_resolver->resolve("testIntFromContainer"));
        $this->assertEquals(36.03, $this->_resolver->resolve("testFloatFromContainer"));
        $this->assertSame("test str", $this->_resolver->resolve("testStrFromContainer"));
        // tear down
        $container->clear();
        unset($container);
    }

    public function testKnownClassDependenciesCanBeResolvedWithAMixOfExplicitAndDefaultParameters() {
        // arrange
        $expected = DummyTestClassWithTypeHintedDependencies::class;
        // act
        $actual = $this->_resolver->resolve(DummyTestClassWithDefaultConstructorParameters::class, ["overriden string value"]);
        // assert
        $this->assertSame("overriden string value", $actual->prop1);
        $this->assertEquals(2, $actual->prop2);
    } 

    public function testKnownFunctionDependenciesCanBeResolvedWithoutParametersWhenTheyHaveDefaultParameters() {
        // arrange
        $expected = "first val second val";
        // act
        $actual = $this->_resolver->resolve("dummyFunction3");
        // assert
        $this->assertSame($expected, $actual);
    }

    public function testKnownFunctionDependenciesCanBeResolvedWithParameters() {
        // arrange
        $expected = "first val second val";
        // act
        $actual = $this->_resolver->resolve("dummyFunction4", ["first val", "second val"]);
        // assert
        $this->assertSame($expected, $actual);
    } 

    public function testResolutionWithBadParamsThrowsACustomBadParamsError() {
        // assert
        $this->expectError();
        $this->expectErrorMessage("Dependency cannot be resolved due to wrong passed params");
        // act
        $this->_resolver->resolve(DummyTestClassWithDefaultConstructorParameters::class, [[36]]);
    }
    
    public function testKnownFunctionDependenciesCanBeResolvedWithParametersThatOverrideDefaultParameters() {
        // arrange
        $expected = "first custom val second custom val";
        // act
        $actual = $this->_resolver->resolve("dummyFunction3", ["first custom val", "second custom val"]);
        // assert
        $this->assertSame($expected, $actual);
    } 

    public function testKnownFunctionDependenciesCanBeResolvedWithAMixOfExplicitAndDefaultParameters() {
        // arrange
        $expected = "first custom val second val";
        // act
        $actual = $this->_resolver->resolve("dummyFunction3", ["first custom val"]);
        // assert
        $this->assertSame($expected, $actual);
    }

    public function testSingletonsCanBeResolvedFromAutoloader() {
        // arrange 
        $expected = Container::class;
        // act
        $actual = $this->_resolver->resolve("\Yactouat\DI\Container");
        // assert
        $this->assertInstanceOf($expected, $actual);
        // tear down
        $actual->clear();
        unset($actual);
    }

    public function testSingletonsCanBeResolvedFromContainer() {
        // arrange 
        $container = Container::getInstance();
        $expected = DummySingleton::class;
        $container->addEntry(DummySingleton::class, DummySingleton::getInstance());
        // act
        $actual = $this->_resolver->resolve(DummySingleton::class);
        // assert
        $this->assertInstanceOf($expected, $actual);
        // tear down
        $container->clear();
        unset($container);
    }

}