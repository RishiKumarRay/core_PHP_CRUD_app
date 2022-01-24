<?php

namespace Yactouat\DI\Tests\Fixtures;

use Yactouat\DI\Tests\Fixtures\DummyTestClassWithDynamicParam;

class DummyTestClassRussianDoll {

    public DummyTestClassWithDynamicParam $dmtc;

    public function __construct(DummyTestClassWithDynamicParam $dmtc)
    {
        $this->dmtc = $dmtc;
    }

}