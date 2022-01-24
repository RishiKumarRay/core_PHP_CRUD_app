<?php

namespace Yactouat\DI\Tests\Fixtures;

class DummyTestClass {
    public function throwAnException() {
        throw new \BadMethodCallException("you should not use that method");
    }
};