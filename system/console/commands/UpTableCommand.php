<?php
/**
 * User: Amir Aslan Aslani
 * Date: 6/4/18
 * Time: 7:18 PM
 */

namespace Dor\Console\Command;

class UpTableCommand extends TableManager
{
    protected function configure()
    {
        $this->method = $this::UP;
        parent::configure();
    }
}