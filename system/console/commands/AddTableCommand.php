<?php
/**
 * User: Amir Aslan Aslani
 * Date: 6/4/18
 * Time: 7:16 PM
 */

namespace Dor\Console\Command;


use Dor\Console\ClassCreator;

class AddTableCommand extends ClassCreator
{
    protected function configure()
    {
        $this   ->setFileName("table.template.php")
                ->setClassType("table")
                ->setDirectory("/src/tables");

        parent::configure();
    }
}