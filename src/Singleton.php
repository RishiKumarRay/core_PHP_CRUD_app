<?php

namespace App;

interface Singleton {
    /**
     * @return self
     */
    public static function getSingletonInstance():self;
}