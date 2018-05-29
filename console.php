#!/usr/bin/env php
<?php

const __BASE_ROOT__ = __DIR__ . '/';
const __PUBLIC_PATH__ = __BASE_ROOT__ . 'public/';

require_once(__BASE_ROOT__ . "vendor/autoload.php");

use Symfony\Component\Console\Application;

require_once(__BASE_ROOT__ . "system/console/ClassCreator.php");
require_once(__BASE_ROOT__ . "system/console/commands/AddControllerCommand.php");
require_once(__BASE_ROOT__ . "system/console/commands/AddModelCommand.php");


$application = new Application("d'Or Framework Console");

$application->add(new \Dor\Console\Command\AddControllerCommand());
$application->add(new \Dor\Console\Command\AddModelCommand());

if(file_exists(__BASE_ROOT__ . 'vendor/symfony/web-server-bundle/Command/ServerStartCommand.php')) {
    require_once(__BASE_ROOT__ . 'vendor/symfony/web-server-bundle/Command/ServerStartCommand.php');
    $application->add(new \Symfony\Bundle\WebServerBundle\Command\ServerStartCommand(__PUBLIC_PATH__,'dev'));
}

if(file_exists(__BASE_ROOT__ . 'vendor/symfony/web-server-bundle/Command/ServerRunCommand.php')) {
    require_once(__BASE_ROOT__ . 'vendor/symfony/web-server-bundle/Command/ServerRunCommand.php');
    $application->add(new \Symfony\Bundle\WebServerBundle\Command\ServerRunCommand(__PUBLIC_PATH__,'dev'));
}

if(file_exists(__BASE_ROOT__ . 'vendor/symfony/web-server-bundle/Command/ServerStatusCommand.php')) {
    require_once(__BASE_ROOT__ . 'vendor/symfony/web-server-bundle/Command/ServerStatusCommand.php');
    $application->add(new \Symfony\Bundle\WebServerBundle\Command\ServerStatusCommand());
}

if(file_exists(__BASE_ROOT__ . 'vendor/symfony/web-server-bundle/Command/ServerStopCommand.php')) {
    require_once(__BASE_ROOT__ . 'vendor/symfony/web-server-bundle/Command/ServerStopCommand.php');
    $application->add(new \Symfony\Bundle\WebServerBundle\Command\ServerStopCommand());
}

$application->run();