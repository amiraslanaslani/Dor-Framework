#!/usr/bin/env php
<?php

require_once("vendor/autoload.php");

use Symfony\Component\Console\Application;

require_once("system/console/ClassCreator.php");
require_once("system/console/commands/AddControllerCommand.php");
require_once("system/console/commands/AddModelCommand.php");

const __BASE_ROOT__ = __DIR__;
$application = new Application("d'Or Framework Console");
$application->add(new \Dor\Console\Command\AddControllerCommand());
$application->add(new \Dor\Console\Command\AddModelCommand());
$application->run();