<?php

namespace App\Controllers;

final class NotFoundController extends Controller {

    public function __construct(string $viewsDirPath)
    {
        parent::__construct($viewsDirPath);
        $this->_pageH1 = "No Content Found";
        $this->_pageTitle = "Not Found";
        $this->statusCode = 404;
        $this->_viewPath = "not_found.php";
    }

}