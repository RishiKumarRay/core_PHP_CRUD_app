<?php

namespace App\Controllers;

abstract class Controller {

    protected string $_layoutPath = "layout.php";
    protected string $_pageH1;
    protected string $_pageTitle;
    protected int $_statusCode;
    protected string $_viewPath;
    protected string $_viewsDirPath;

    protected function __construct(string $viewsDirPath)
    {
        $this->_viewsDirPath = $viewsDirPath;
    }

    protected function _setLayoutPath(string $layoutPath) : void {
        $this->_layoutPath = $layoutPath;
    }

    public function outputView(): void
    {
        $pageH1 = $this->_pageH1;
        $pageTitle = $this->_pageTitle;
        $pageContent = file_get_contents($this->_viewsDirPath.DIRECTORY_SEPARATOR.$this->_viewPath);
        http_response_code($this->statusCode);
        include($this->_viewsDirPath.DIRECTORY_SEPARATOR.$this->_layoutPath);
    }


}