<?php
/**
 * User: Amir Aslan Aslani
 * Date: 6/4/18
 * Time: 7:18 PM
 */

namespace Dor\Console\Command;

class DownTableCommand extends TableManager
{
    protected function configure()
    {
        $this->method = $this::DOWN;
        parent::configure();
    }
}