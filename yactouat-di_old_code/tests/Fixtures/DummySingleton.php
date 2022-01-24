<?php

namespace Yactouat\DI\Tests\Fixtures;

class DummySingleton {

    private array $_entries = [];
    private static $_instance;

    private function __construct(){}

    public static function getInstance(): self {
        if (is_null(self::$_instance))
            self::$_instance = new DummySingleton();
        return self::$_instance;
    }
    
};