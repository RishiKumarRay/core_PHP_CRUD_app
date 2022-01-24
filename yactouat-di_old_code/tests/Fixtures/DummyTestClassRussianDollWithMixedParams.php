<?php

namespace Yactouat\DI\Tests\Fixtures;

use Yactouat\DI\Tests\Fixtures\DummyTestClassWithDynamicParam;

class DummyTestClassRussianDollWithMixedParams {

    public DummyTestClassWithDynamicParam $dmtc;
    public array $a;
    public string $s;

    public function __construct(DummyTestClassWithDynamicParam $dmtc, array $a, string $s)
    {
        $this->dmtc = $dmtc;
        $this->a = $a;
        $this->s = $s;
    }

}