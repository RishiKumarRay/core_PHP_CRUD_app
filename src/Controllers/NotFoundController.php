<?php

namespace App\Controllers;

class NotFoundController extends Controller {

    public function __construct(string $viewsDirPath)
    {
        parent::__construct($viewsDirPath);
        $this->statusCode = 404;
    }

    public function echoHTML(): void
    {
        $pageTitle = "Not Found";
        $pageContent = file_get_contents($this->_viewsDirPath.DIRECTORY_SEPARATOR."not_found.php");
        include($this->_viewsDirPath.DIRECTORY_SEPARATOR."layout.php");
    }

}