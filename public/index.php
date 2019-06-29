<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/25/18
 * Time: 8:25 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = __DIR__ . "/../";
require_once($root . "vendor/autoload.php");

$config = new \Dor\Config($root . 'config.php', $root);

$kernel = new \Dor\Kernel($config);
$kernel->sendResponse(
    \Dor\Kernel::createRequestFromCurrent()
);