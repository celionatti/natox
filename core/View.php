<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;

use Exception;

/**
 * Class View
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class View
{
    private $_siteTitle = '', $_content = [], $_currentContent, $_buffer, $_layout;
    private $_defaultViewPath;

    public function __construct($path = '')
    {
        $this->_defaultViewPath = $path;
        $this->_siteTitle = Config::get('default_site_title');
    }

    public function setLayout($layout)
    {
        $this->_layout = $layout;
    }

    public function setSiteTitle($title)
    {
        $this->_siteTitle = $title;
    }

    public function getSiteTitle()
    {
        return $this->_siteTitle;
    }

    public function render($path = '')
    {
        if (empty($path)) {
            $path = $this->_defaultViewPath;
        }
        $layoutPath = Application::$ROOT_DIR . "/src/views/layouts/$this->_layout.php";
        $fullPath = Application::$ROOT_DIR . "/src/views/$path.php";
        if (!file_exists($fullPath)) {
            throw new Exception(Errors::get('5001'), 5001);
        }
        if (!file_exists($layoutPath)) {
            throw new Exception(Errors::get('5002'), 5002);
        }
        include($fullPath);
        include($layoutPath);
    }

    public function start($key)
    {
        if (empty($key)) {
            throw new Exception(Errors::get('5003'), 5003);
        }
        $this->_buffer = $key;
        ob_start();
    }

    public function end()
    {
        if (empty($this->_buffer)) {
            throw new Exception(Errors::get('5004'), 5004);
        }
        $this->_content[$this->_buffer] = ob_get_clean();
        $this->_buffer = null;
    }

    public function content($key)
    {
        if (array_key_exists($key, $this->_content)) {
            echo $this->_content[$key];
        } else {
            echo '';
        }
    }

    public function partial($path)
    {
        $fullPath = Application::$ROOT_DIR . "/src/views/components/$path.php";
        if (file_exists($fullPath)) {
            include($fullPath);
        }
    }
    
}
