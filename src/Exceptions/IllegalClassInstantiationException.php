<?php

namespace App\Exceptions;

use Exception;

final class IllegalClassInstantiationException extends Exception
{
    public function __construct(string $className, int $code = 0, Throwable $previous = null) {
        parent::__construct($className." called illegally.", $code, $previous);
    }

    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}