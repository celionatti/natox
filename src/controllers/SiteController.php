<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace Natox\controllers;

use NatoxCore\Request;
use NatoxCore\Session;
use Natox\models\Users;
use NatoxCore\helpers\H;
use NatoxCore\Controller;
use Natox\models\Articles;
use NatoxCore\Application;

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
        // $params = [
        //     'columns' => "articles.*, users.fname, users.lname, categories.name as category, categories.id as category_id",
        //     'conditions' => "articles.status = :status",
        //     'bind' => ['status' => 'public'],
        //     'joins' => [
        //         ['users', 'articles.user_id = users.id'],
        //         ['categories', 'articles.category_id = categories.id', 'categories', 'LEFT']
        //     ],
        //     'order' => 'articles.id DESC'
        // ];
        $params = ['order' => 'id'];

        $users = Users::find($params);

        $this->view->users = $users;
        $this->view->articles = Articles::find($params);
        $this->view->render('about');
    }
}
