<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;

use NatoxCore\helpers\H;

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

    public Request $request;

    public function __construct()
    {
        $this->request = new Request();
        H::dnd($this->request);
    }
}