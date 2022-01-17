<?php

namespace Tests\Unit\Fixtures;

class ClassWithDefaultParams
{

    public string $someProp;
    public string $someOtherProp;

    public function __construct($defaultParam = "default", $otherDefaultParam = "other default") {
        $this->someProp = $defaultParam;
        $this->someOtherProp = $otherDefaultParam;
    }

}