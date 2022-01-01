<?php

namespace App\Controllers;

class ContactFormController extends Controller {

    public function __construct(string $viewsDirPath)
    {
        parent::__construct($viewsDirPath);
        $this->_pageTitle = "Contact Form";
        $this->statusCode = 200;
        $this->_viewPath = "contact_form.php";
    }

}