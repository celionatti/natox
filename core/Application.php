<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;

use Exception;
use NatoxCore\helpers\H;
use NatoxCore\database\Database;

/**
 * Class Application
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

 class Application
 {
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    protected array $eventListeners = [];

    public static Application $app;
    public static string $ROOT_DIR;
    public string $userClass;
    public string $layout = 'main';
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public Database $db;
    public Session $session;
    public View $view;

    public function __construct($rootDir)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database();
        $this->session = new Session();
        $this->view = new View();

        define('TIMEZONE', Config::get('TIME_ZONE'));
        define('ROOT', '/');
    }

    public function run()
    {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            // H::dnd($e->getMessage(), false);
            echo $this->router->renderError('_error', $e);
        }
    }

    public function triggerEvent($eventName)
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }
}