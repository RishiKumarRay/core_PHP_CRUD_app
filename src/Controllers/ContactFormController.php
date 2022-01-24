<?php

namespace App\Controllers;

final class ContactFormController extends Controller {

    public function __construct(string $viewsDirPath)
    {
        parent::__construct($viewsDirPath);
        $this->_pageH1 = "Contact Us";
        $this->_pageTitle = "Contact Us";
        $this->statusCode = 200;
        $this->_viewPath = "contact_form.php";
    }

}