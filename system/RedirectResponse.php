<?php
/**
 * User: Amir Aslan Aslani
 * Date: 6/4/18
 * Time: 3:48 AM
 */

namespace Dor\Util;

use Dor\Kernel;

class RedirectResponse extends Response
{
    public function __construct($to,$fromBase = true)
    {
        $this->setStatus($this::STATUS[301]);
        $this->addHeader('Location: ' . ($fromBase ? Kernel::$config['app']['base_url'] : '') . $to);
    }
}