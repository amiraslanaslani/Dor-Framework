<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/25/18
 * Time: 8:10 PM
 */

namespace Dor;

require_once(__DIR__ . '/request.php');
require_once(__DIR__ . '/response.php');
require_once(__DIR__ . '/AbstractController.php');

use Dor\Util\{
    ErrorResponse, Response, Request
};
use zpt\anno\Annotations;

class Kernel
{
    private $response;
    public static $twig;
    public static $config;

    public function __construct(){

        // Load config file.
        Kernel::$config = include(__DOR_ROOT__ . 'config.php');

        // Check and set debug mode
        if(Kernel::$config['debug_mode']){
            $this->enableDebugMode();
        }
        else{
            $this->disableDebugMode();
        }

        // Setup Twig template engine.
        $loader = new \Twig_Loader_Filesystem(__DOR_ROOT__ . 'src/view/');
        Kernel::$twig = new \Twig_Environment($loader);
    }

    private function enableDebugMode(){

        // Show all errors and warnings
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    private function disableDebugMode(){

        // Hide all errors and warnings
        error_reporting(0);
        ini_set('display_errors', 0);
    }

    public static function createRequestFromCurrent():Request{
        $request = new Request();
        $request->headers = getallheaders();
        $request->host = $_SERVER['HTTP_HOST'];
        $request->uri = $_SERVER['REQUEST_URI'];
        return $request;
    }

    public static function getResponse(Request $req):Response{

        // Finding correct controller for request.
        foreach (glob(__DOR_ROOT__ . "src/controllers/*.php") as $filename) {
            $className = '\\Dor\\Controller\\' . basename($filename, '.php');
            $loadedClasses[] = $className;
            include_once($filename);

            $classReflector = new \ReflectionClass($className);

            foreach ($classReflector->getMethods() as $methodReflector) {
                $annotations = new Annotations($methodReflector);

                $preg1 = str_replace(
                    "/",
                    "\/",
                    preg_replace(
                        "/{(\w*)}/",
                        "\w*",
                        $annotations['Route']
                    )
                );

                $isThisRoute = preg_match(
                    '/^' . $preg1 . '$/',
                    $req->uri,
                    $r
                );

                // If this method is correct method to get response for this request
                if ($annotations->hasAnnotation("Route") && $isThisRoute) {
                    $tmpUri = '~' . $req->uri . '~';
                    $tmpRoute = '~' . $annotations['Route'] . '~';

                    $preg2 = preg_split ("/{(\w*)}/", $tmpRoute);
                    $preg3 = '/' . str_replace("/","\/","(" . implode(")|(", $preg2) . ")") . '/';
                    $preg4 = preg_split ($preg3 , $tmpUri);

                    // Remove first and last element of array
                    array_pop($preg4);
                    array_shift($preg4);

                    // Get response of request and return it to response sender.
                    $controller = new $className();
                    $methodName = $methodReflector->name;
                    return $controller->$methodName(
                        $preg4
                    );
                }
            }

        }

        // There is no controller for this URI!
        $noAnyControllerResponse = new Response();
        $noAnyControllerResponse->setStatus(Response::STATUS[404]);
        $noAnyControllerResponse->body = Kernel::$twig->render(
            Kernel::$config['app']['404_page'],
            array()
        );
    }

    public function sendResponse(Request $request){
        try {
            $this->response = $this::getResponse(
                $request
            );
        }
        catch (\Exception $exception){
            include(__DIR__ . '/ErrorResponse.php');
            $this->response = new ErrorResponse($exception);
        }

        foreach ($this->response->headers as $header){
            header($header);
        }

        echo $this->response->body;
    }
}