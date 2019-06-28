<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/29/18
 * Time: 3:37 AM
 */

namespace Dor\Console\Command;

use Dor\Console\ClassCreator;

class AddControllerCommand extends ClassCreator
{
    protected function configure()
    {
        $this   ->setFileName("controller.template.php")
                ->setClassType("controller")
                ->setDirectory("/src/controllers");

        parent::configure();
    }
}