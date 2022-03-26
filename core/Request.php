<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;

use \Exception as Exception;
use NatoxCore\helpers\CoreHelpers;
use NatoxCore\helpers\H;

/**
 * Class Request
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class Request
{
    private array $routeParams = [];

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }


    /**
     * Checks if request needs a Json as a way to recognize API calls
     */
    public function wantsJson()
    {
        $result = false;
        $acceptance = explode(',', strtolower(preg_replace('/\s+/', '', $this->accept)));
        if (!empty($acceptance)) {
            //$result = !in_array('text/html', $acceptance); // set to true if client is not asking for html
            if (!in_array('text/html', $acceptance)) {
                $result = in_array('application/json', $acceptance);
            }
        }
        return $result;
    }

    /**
     * Checks for CSRF if it's an application form. If it's an api then it does not check it
     */
    private function checkCSRF()
    {
        // if it's a form and not an API then check for CSRF
        if (!$this->wantsJson()) {
            if (!isset($_POST['_token']) || (isset($_POST['_token']) && !hash_equals($_POST['_token'], $_SESSION['token']))) {
                throw new Exception(Errors::get('3000'), 3000);
                exit;
            }
        }
        return;
    }

    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    public function isDelete()
    {
        return $this->getMethod() === 'delete';
    }

    public function isPatch()
    {
        return $this->getMethod() === 'patch';
    }

    public function isPut()
    {
        return $this->getMethod() === 'put';
    }

    public function get($input = false)
    {
        if (!$input) {
            $data = [];
            foreach ($_REQUEST as $field => $value) {
                $data[$field] = self::sanitize($value);
            }
            return $data;
        }
        return array_key_exists($input, $_REQUEST) ? self::sanitize($_REQUEST[$input]) : false;
    }

    public static function sanitize($dirty)
    {
        return htmlentities(trim($dirty), ENT_QUOTES, "UTF-8");
    }

    /**
     * @param $params
     * @return self
     */
    public function setRouteParams($params)
    {
        $this->routeParams = $params;
        return $this;
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    public function getRouteParam($param, $default = null)
    {
        return $this->routeParams[$param] ?? $default;
    }
}
