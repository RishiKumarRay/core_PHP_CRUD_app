<?php

namespace Yactouat\DI\Tests\Fixtures;

use Yactouat\DI\Tests\Fixtures\DummyTestClass;
use Yactouat\DI\Tests\Fixtures\DummyTestClassWithDefaultConstructorParameters;
use Yactouat\DI\Tests\Fixtures\DummyTestClassWithoutConstructorParameters;

class DummyTestClassWithTypeHintedDependencies {

    public DummyTestClass $dmtc;
    public DummyTestClassWithoutConstructorParameters $dmtcwcp;
    public DummyTestClassWithDefaultConstructorParameters $dmtcwdcp;

    public function __construct(
        DummyTestClass $dmtc, 
        DummyTestClassWithoutConstructorParameters $dmtcwcp,
        DummyTestClassWithDefaultConstructorParameters $dmtcwdcp
    )
    {
        $this->dmtc = $dmtc;
        $this->dmtcwcp = $dmtcwcp;
        $this->dmtcwdcp = $dmtcwdcp;
    }

}