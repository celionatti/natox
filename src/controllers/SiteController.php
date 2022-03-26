<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace Natox\controllers;

use NatoxCore\Controller;

/**
 * Class SiteController
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class SiteController extends Controller
{
    public function home()
    {
        $this->view->name = "The Natox";
        $this->view->age = "2020";
        $this->view->author = "Celio Natti";
        $this->view->render('home');
    }
}
