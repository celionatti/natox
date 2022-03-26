<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace Natox\controllers;

use NatoxCore\Controller;
use NatoxCore\helpers\H;
use NatoxCore\Request;
use NatoxCore\Session;

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

    public function login()
    {
        $this->view->setLayout('auth');
        $this->view->errors = [];
        $this->view->render('auth/login');
    }

    public function contact(Request $request)
    {
        if($request->isPost()) {
            Session::csrfCheck();
            H::dnd($request->get());
        }
        $this->view->setLayout('main');
        $this->view->errors = [];
        $this->view->render('contact');
    }

    public function about(Request $request)
    {
        if ($request->isDelete()) {
            H::dnd($request);
        }
        $this->view->render('about');
    }
}
