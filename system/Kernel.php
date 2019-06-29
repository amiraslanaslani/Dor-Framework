<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/25/18
 * Time: 8:10 PM
 */

namespace Dor;

require_once(__DIR__ . '/AbstractController.php');
require_once(__DIR__ . '/InputCheck.php');

use Dor\Util\{
    ErrorResponse
};

use Dor\Http\{
    Response, Request
};

use Dor\Router\{
    Router, RouterResponseParameter
};

use Illuminate\Database\Capsule\Manager as CapsuleManager;

class Kernel
{
    public static $twig;
    public static $config;
    public static $capsule;

    private $response;
    private $isOnDebugMode = false;

    public function __construct(){

        // Load config file.
        Kernel::$config = include(__DOR_ROOT__ . 'config.php');

        // Setup Twig template engine.
        $loader = new \Twig_Loader_Filesystem(__DOR_ROOT__ . Kernel::$config['system']['directories']['view'] . '/');
        Kernel::$twig = new \Twig_Environment(
            $loader,
            [
                'debug' => Kernel::$config['debug_mode']
            ]
        );
        require_once __DOR_ROOT__ . Kernel::$config['system']['directories']['configs'] . '/twig.php';

        // Check and set debug mode.
        if(Kernel::$config['debug_mode']){
            $this->enableDebugMode();
        }
        else{
            $this->disableDebugMode();
        }

        //Setup Illuminate database if there is database config.
        if(Kernel::$config['database'] !== false) {
            $capsule = new CapsuleManager();
            $capsule->addConnection(Kernel::$config['database']);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
            Kernel::$capsule = $capsule;
        }
        require_once __DOR_ROOT__ . Kernel::$config['system']['directories']['configs'] . '/illuminate.php';

        // Load models
        require_once(__DOR_ROOT__ . 'system/AbstractModel.php');
        foreach (glob(__DOR_ROOT__ . Kernel::$config['system']['directories']['model'] . "/*.php") as $filename) {
            include_once($filename);
        }
    }

    private function enableDebugMode(){
        $this->isOnDebugMode = true;

        // Whoops error handler added
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        // Show all errors and warnings
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        Kernel::$twig->addExtension(new \Twig_Extension_Debug());
    }

    private function disableDebugMode(){
        $this->isOnDebugMode = false;

        // Hide all errors and warnings
        error_reporting(0);
        ini_set('display_errors', 0);
    }

    public static function createRequestFromCurrent():Request{
        $request = new Request();
        $request->headers = getallheaders();
        $request->host = $_SERVER['HTTP_HOST'];
        $request->uri = explode('?',$_SERVER['REQUEST_URI'])[0];
        $request->requestType = strtolower($_SERVER['REQUEST_METHOD']);
        $request->get = $_GET;
        $request->post = $_POST;
        $request->file = $_FILES;
        return $request;
    }

    public static function getResponse(Request $req):Response{

        $rrp = new RouterResponseParameter();
        $rrp->add('Dor\Http\Request', $req);
        $rrp->add('Illuminate\Database\Capsule\Manager', Kernel::$capsule);

        $router = new Router(
            $req,
            __DOR_ROOT__ . Kernel::$config['system']['directories']['controller'],
            __DOR_ROOT__ . Kernel::$config['system']['directories']['routes'],
            '\\Dor\\Controller\\',
            $rrp
        );

        if($router->iterateOverRoutes())
            return Kernel::responsize($router->getResponse());

        // There is no controller for this URI!
        $noAnyControllerResponse = new Response();
        $noAnyControllerResponse->setStatus(Response::STATUS[404]);
        $noAnyControllerResponse->body = Kernel::$twig->render(
            Kernel::$config['app']['404_page'],
            array()
        );
        return $noAnyControllerResponse;
    }

    public function sendResponse(Request $request){
        if($this->isOnDebugMode){
            $this->setInnerResponse($request);
        }
        else{
            try {
                $this->setInnerResponse($request);
            }
            catch (\Exception $exception){
                include(__DIR__ . '/ErrorResponse.php');
                $this->response = new ErrorResponse($exception);
            }
        }

        foreach ($this->response->headers as $header){
            header($header);
        }

        echo $this->response->body;
    }

    private function setInnerResponse(Request $request){
        $this->response = $this::getResponse(
            $request
        );
    }

    private static function responsize($data):Response{
        if($data instanceof Response)
            return $data;
        else if(is_array($data)){

        }
        
    }
}
