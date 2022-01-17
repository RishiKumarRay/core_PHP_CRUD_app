<?php

namespace Yactouat\DI\Tests\Fixtures;

class DummyTestClassWithDynamicParam {
    public string $value;
    public function __construct(string $param)
    {
        $this->value = $param;
    }
};