<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/29/18
 * Time: 5:05 PM
 */

namespace Dor\Util;


use zpt\anno\Annotations;

class Router
{
    private $request;
    private $controllersPath;
    private $controllersNamespace;

    private $params = array();

    private $isControllerFind = false;
    private $findRoute = array();

    public function __construct(Request $request, string $controllersPath, string $controllerNamespace = '')
    {
        $this->request = $request;
        $this->controllersPath = $controllersPath;
        $this->controllersNamespace = $controllerNamespace;
    }

    public function iterateOverControllers(){
        foreach (glob($this->controllersPath . "/*.php") as $filename) {
            $className = $this->controllersNamespace . basename($filename, '.php');
            $loadedClasses[] = $className;
            include_once($filename);
            $classReflector = new \ReflectionClass($className);

            foreach ($classReflector->getMethods() as $methodReflector) {

                if($this->isControllerFind)
                    break;

                $annotations = new Annotations($methodReflector);

                if( $this->checkRequestMethod($annotations) &&
                    $this->checkRequestedURI($annotations) ){

                    $this->isControllerFind = true;
                    $this->findRoute['class'] = $className;
                    $this->findRoute['method'] = $methodReflector->name;
                }

            }

        }

        return $this->isControllerFind;
    }

    public function getResponse(){
        if($this->isControllerFind){

            // Get response of request and return it to response sender.
            $className = $this->findRoute['class'];
            $methodName = $this->findRoute['method'];
            $controller = new $className();
            return $controller->$methodName(
                $this->params
            );
        }
        return null;
    }

    private function checkRequestMethod(Annotations $annotations){
        if($annotations->isAnnotatedWith('Method')){
            if(strtolower(trim($annotations['Method'])) == $this->request->requestType){
                return true;
            }
            else
                return false;
        }
        else
            return true;
    }

    private function checkRequestedURI(Annotations $annotations){
        if(! $annotations->isAnnotatedWith('Route')) {
            return false;
        }
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
            $this->request->uri,
            $r
        );

        // If this method is correct method to get response for this request
        if ($annotations->isAnnotatedWith("Route") && $isThisRoute) {
            $tmpUri = '~' . $this->request->uri . '~';
            $tmpRoute = '~' . $annotations['Route'] . '~';
            $preg2 = preg_split ("/{(\w*)}/", $tmpRoute);
            $preg3 = '/' . str_replace("/","\/","(" . implode(")|(", $preg2) . ")") . '/';
            $preg4 = preg_split ($preg3 , $tmpUri);

            // Remove first and last element of array
            array_pop($preg4);
            array_shift($preg4);
            $this->params = $preg4;
            return true;
        }
        else{
            return false;
        }
    }
}