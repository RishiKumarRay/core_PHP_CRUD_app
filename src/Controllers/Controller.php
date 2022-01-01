<?php

namespace App\Controllers;

abstract class Controller {

    public int $statusCode;
    protected string $_viewsDirPath;

    public function __construct(string $viewsDirPath)
    {
        $this->_viewsDirPath = $viewsDirPath;
    }

    public abstract function echoHTML() : void;

}