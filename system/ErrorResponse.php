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
        // $debugMode = Kernel::$config['debug_mode'];
        $debugMode = true;
        
        echo Kernel::$twig->render(
            Kernel::$config['app']['error_page'],
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