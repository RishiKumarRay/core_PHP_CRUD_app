<?php

namespace Yactouat\DI\Tests\Fixtures;

class DummyTestClassWithDefaultConstructorParameters {
    public string $prop1;
    public int $prop2;
    public function __construct(string $param1="test", int $param2=2) {
        $this->prop1 = $param1;
        $this->prop2 = $param2;
    }
};