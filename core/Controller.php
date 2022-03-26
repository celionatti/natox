<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;

/**
 * Class Controller
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class Controller
{
    public string $layout = 'main';
    public string $action = '';
    public $view, $request;

    public function __construct()
    {
        $this->view = new View();
        $this->view->setLayout(Config::get('DEFAULT_LAYOUT'));
        // $this->request = new Request();
        $this->onConstruct();
    }

    /**
     * onConstruct Function
     *
     *This function is used to add additional method to the constructor
     *
     * @return void
     */
    public function onConstruct()
    {
    }
}
