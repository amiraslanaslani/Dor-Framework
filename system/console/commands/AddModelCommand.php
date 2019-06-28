<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/29/18
 * Time: 3:37 AM
 */

namespace Dor\Console\Command;

use Dor\Console\ClassCreator;

class AddModelCommand extends ClassCreator
{
    protected function configure()
    {
        $this   ->setFileName("model.template.php")
                ->setClassType("model")
                ->setDirectory("/src/models");

        parent::configure();
    }
}