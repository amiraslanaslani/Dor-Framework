<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/25/18
 * Time: 10:34 PM
 */

namespace Dor\Util;

use Dor\Kernel;

class ErrorResponse extends Response
{
    public function __construct(\Exception $exception)
    {
        $debugMode = Kernel::$config['debug_mode'];

        echo Kernel::$twig->render(
            'error.html.php',
            array(
                'message' => $exception->getMessage(),
                'file' => $debugMode ? $exception->getFile() : '',
                'code' => $debugMode ? $exception->getCode() : '',
                'line' => $debugMode ? $exception->getLine() : '',
                'trace'=> $debugMode ? $exception->getTraceAsString(): ''
            )
        );
    }
}