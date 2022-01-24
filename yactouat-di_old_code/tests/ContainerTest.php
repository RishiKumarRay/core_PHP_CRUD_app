<?php

namespace Yactouat\DI\Tests;

use Psr\Container\ContainerExceptionInterface;
use Yactouat\DI\Container;
use Yactouat\DI\Tests\Fixtures\DummyTestClass;
use Yactouat\DI\Tests\Fixtures\DummyTestClassWithDynamicParam;
use Yactouat\Exceptions\DI\DependencyNotFoundException;
use Yactouat\Exceptions\DI\EmptyEntryIdException;
use Yactouat\Exceptions\DI\InvalidMassAddingEntriesException;
use Yactouat\Interfaces\SingletonInterface;

/**
 * @covers \Yactouat\DI\Container
 * 
 * @description the following tests make sure we implement the specs stated in https://www.php-fig.org/psr/psr-11/
 */
class ContainerTest extends \PHPUnit\Framework\TestCase {

    protected Container $_container;

    protected function setUp(): void {
        $this->_container = Container::getInstance();
    }

    protected function tearDown(): void
    {
        $this->_container->clear();
        unset($this->_container);
    }

    public function testAddEntry_withEmptyStrEntryId_throwsContainerException() {
        // assert
        $this->expectException(EmptyEntryIdException::class);
        $this->expectExceptionMessage("Cannot set a container entry with an empty entry ID.");
        // act
        $actual = ['', null];
        $this->_container->addEntry(...$actual);
    }

    public function testGetEntryReturnsTheCorrectEntry() {
        // arrange
        $expected = new DummyTestClass();
        // act
        $this->_container->addEntry("testId", $expected);
        $actual = $this->_container->get("testId");
        // assert
        $this->assertSame($expected, $actual);
    }

    public function testGet_withNonExistingEntry_throwsDependencyNotFoundException() {
        // arrange
        $actual = "NonExistingEntry";
        // assert
        $this->expectException(DependencyNotFoundException::class);
        $this->expectExceptionMessage("Entry with id ".$actual." not found.");
        // act
        $this->_container->get($actual);
    }

    public function test2SuccessiveCallsToTheSameEntryIdShouldReturnTheSameInstanceOfAnObject() {
        // arrange
        $this->_container->addEntry("testId", new DummyTestClass());
        $expected = $this->_container->get("testId");
        // act
        $actual = $this->_container->get("testId");
        // assert
        $this->assertSame($expected, $actual);
    }

    public function testHasMethodReturnsTrueWithExistingEntryId() {
        // arrange
        $this->_container->addEntry("testId", new DummyTestClass());
        // act + assert 
        $this->assertTrue($this->_container->has("testId"));
    }

    public function testHasMethodReturnsFalseWithNonExistingEntryId() {
        // act + assert 
        $this->assertFalse($this->_container->has("testNonExistingId"));
    }

    public function testEmptyEntryIdExceptionImplementsContainerExceptionInterface() {
        // arrange 
        $expected = ContainerExceptionInterface::class;
        // act
        $actual = new EmptyEntryIdException();
        // assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testContainerImplementsSingletonInterface() {
        // arrange 
        $expected = SingletonInterface::class;
        // assert
        $this->assertInstanceOf($expected, $this->_container);
    }

    public function testContainerGetInstanceAlwaysReturnsTheSameInstanceWhenNotCleared() {
        // act
        $actual = Container::getInstance();
        // assert
        $this->assertSame($this->_container,$actual);
        // tear down
        unset($actual);
    }

    public function testConstruct_withDirectCall_triggersError() {
        // assert
        $this->expectError();
        $this->expectErrorMessage("Call to private Yactouat\DI\Container::__construct() from scope Yactouat\DI\Tests\ContainerTest");
        // arrange + act
        $container = new Container();
    }

    public function testValuesStoredInContainerAreRetrievableFromMultipleContainerObjects() {
        // arrange
        $this->_container->addEntry("testEntry", new DummyTestClass());
        $expected = $this->_container->get("testEntry");
        $container2 = Container::getInstance();
        // act
        $actual = $container2->get("testEntry");
        // assert
        $this->assertSame($expected,$actual);
        // tear down
        unset($container2);
    }

    public function testInvokingContainerClearActuallyDestroysStoredValuesInContainer() {
        // arrange
        $this->_container->addEntry("testEntry", new DummyTestClass());
        if ($this->_container->has("testEntry")) {
            // act
            $this->_container->clear();
            // assert
            $this->assertFalse($this->_container->has("testEntry"));
        } else
            $this->fail(FAILED_TEST_MSG);
    }

    public function testContainerGetInstanceDoesntReturnTheSameInstanceWhenCleared() {
        // act
        $this->_container->clear();
        $actual = Container::getInstance();
        // assert
        $this->assertNotSame($this->_container,$actual);
        // tear down
        $actual->clear();
        unset($actual);
    }

    public function testAutoResolvedDependenciesOnGet() {
        // arrange
        $expected = DummyTestClass::class;
        // act
        $actual = $this->_container->get(DummyTestClass::class);
        // assert
        $this->assertInstanceOf($expected, $actual);
    }


    public function autoCreateEntryKeyOnAutoResolution() {
        // arrange
        $this->_container->get(DummyTestClass::class);
        // act + assert
        $this->assertTrue($this->_container->has(DummyTestClass::class));
        $this->assertFalse($this->_container->has("jibberish"));
    }

    public function testEntriesAreAddedInBulk() {
        // arrange
        $entry1 = new DummyTestClass();
        $entry2 = new DummyTestClassWithDynamicParam("test dynamic param");
        // act
        $this->_container->addEntries([
            "entry1" => $entry1,
            "entry2" => $entry2
        ]);
        $entry2FromContainer = $this->_container->get("entry2");
        // assert
        $this->assertInstanceOf(DummyTestClass::class, $this->_container->get("entry1"));
        $this->assertInstanceOf(DummyTestClassWithDynamicParam::class, $entry2FromContainer);
        $this->assertSame("test dynamic param", $entry2FromContainer->value);
    }


    public function testAddEntries_withListArray_throwsInvalidMassAddingEntriesException() {
        // assert
        $this->expectException(InvalidMassAddingEntriesException::class);
        $this->expectExceptionMessage("Entries added in bulk should be formatted in associative array.");
        // act
        $actual = [new DummyTestClass(), new DummyTestClassWithDynamicParam("test")];
        $this->_container->addEntries($actual);
    }

}