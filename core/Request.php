<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;

use \Exception as Exception;
use NatoxCore\helpers\CoreHelpers;

/**
 * Class Request
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class Request
{
    /**
     * @var array _request has all the headers information of the request
     * @property array _request has all the headers information of the request
     */
    private $_request;

    /**
     * @var array method fetches the http request method
     * @property array method fetches the http request method
     */
    public $method;

    /**
     * @var string fulluri defines the full uri of the request includng the query parameters
     * @property string fulluri defines the full uri of the request includng the query parameters
     */
    public $fullUri;

    /**
     * @var array uriComponents defines a list of all the elements of the uri
     * @property array uriComponents defines a list of all the elements of the uri
     */
    public $uriComponents;

    /**
     * @var string host reads the host information as taken from the headers of the request
     * @property string host reads the host information as taken from the headers of the request
     */
    public $host;

    /**
     * @var string host reads the host information as taken from the headers of the request
     * @property string host reads the host information as taken from the headers of the request
     */
    public $authorization;

    /**
     * @var array cache has all the headers information of the request
     * @property array cache has all the headers information of the request
     */
    public $cache;

    /**
     * @var array userAgent has all the headers information of the request
     * @property array userAgent has all the headers information of the request
     */
    public $userAgent;

    /**
     * @var array accept has all the headers information of the request
     * @property array accept has all the headers information of the request
     */
    public $accept;

    /**
     * @var array acceptEncoding has all the headers information of the request
     * @property array acceptEncoding has all the headers information of the request
     */
    public $acceptEncoding;

    /**
     * @var array acceptLanguage has all the headers information of the request
     * @property array acceptLanguage has all the headers information of the request
     */
    public $acceptLanguage;

    /**
     * @var array cookie has all the headers information of the request
     * @property array cookie has all the headers information of the request
     */
    public $cookie;

    /**
     * @var array fetches all the request parameters
     * @property array fetches all the request parameters
     */
    public $parameters;

    private array $routeParams = [];

    public function __construct()
    {
        $this->_request = getallheaders();
        $this->method = isset($_REQUEST['_method']) && $_REQUEST['_method'] != '' ? strtoupper($_REQUEST['_method']) : $_SERVER['REQUEST_METHOD'];

        $this->host = $this->_request['host'] ?? ($this->_request['Host'] ?? '');
        $this->authorization = $this->_request['authorization'] ?? ($this->_request['Authorization'] ?? '');
        $this->cache = $this->_request['cache'] ?? ($this->_request['Cache'] ?? '');
        $this->userAgent = $this->_request['User-Agent'] ?? '';
        $this->accept = $this->_request['accept'] ?? ($this->_request['Accept'] ?? '');
        $this->acceptEncoding = $this->_request['accept-encoding'] ?? ($this->_request['Accept-Encoding'] ?? '');
        $this->acceptLanguage = $this->_request['accept-language'] ?? ($this->_request['Accept-Language'] ?? '');
        $this->cookie = $this->_request['cookie'] ?? ($this->_request['Cookie'] ?? '');

        $this->fullUri = $this->getUrl();
        $this->uriParameters = $this->getURIParameters();

        return $this;
    }

    /**
     * Checks if API requests are authorized
     */
    public function authorizeApiRequest()
    {
        $result = false;
        $apiKey = isset($this->authorization) ? $this->authorization : null;
        $matches = array();
        if ($apiKey) {
            preg_match('/Bearer\s(\S+)/', $apiKey, $matches);
            if (!empty($matches) && isset($matches[1])) {
                $result = CoreHelpers::encryptDecrypt('decrypt', $matches[1]) == getenv('APP_KEY') ?? false;
            }
        }
        return $result;
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
     * Fetches the full uri a request
     */
    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    /**
     * Fetches the parameters of a request
     */
    public function parameters()
    {
        $parameters = $this->getURIParameters();
        return $parameters;
    }

    /**
     * Fetches the files uploaded with a request
     */
    public function files($fileAttribute)
    {
        return $this->getFiles($fileAttribute);
    }

    /**
     * Fetches all the parameters of a request depending on the method
     */
    private function getURIParameters()
    {
        $parameters = array();
        switch ($this->method) {
            case 'GET':
                $parameters = $_GET ?? array();
                break;
            case 'POST':
                $parameters = !empty($_POST) ? $_POST : json_decode(file_get_contents("php://input"), true) ?? array();
                $this->checkCSRF();
                break;
            case 'PUT':
                $parameters = !empty($_POST) ? $_POST : json_decode(file_get_contents("php://input"), true) ?? array();
                $this->checkCSRF();
                break;
            case 'DELETE':
                $parameters = !empty($_POST) ? $_POST : json_decode(file_get_contents("php://input"), true) ?? array();
                $this->checkCSRF();
                break;
                break;
        }
        return $parameters;
    }

    /**
     * Fetches all the files in a request
     */
    private function getFiles($fileAttribute)
    {
        return $_FILES[$fileAttribute] ?? array();
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
