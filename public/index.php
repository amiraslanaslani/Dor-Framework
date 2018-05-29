<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/25/18
 * Time: 8:25 PM
 */

const __DOR_ROOT__ = __DIR__ . '/../';

require_once(__DOR_ROOT__ . "vendor/autoload.php");
require_once(__DOR_ROOT__ . "system/Kernel.php");

$kernel = new \Dor\Kernel();
$kernel->sendResponse(
    \Dor\Kernel::createRequestFromCurrent()
);