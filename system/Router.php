<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/29/18
 * Time: 5:05 PM
 */

namespace Dor\Util;


use Dor\Kernel;
use Dor\AnnotationParser\Annotation;
use Dor\AnnotationParser\MethodAnnotation;

class Router
{
    private $request;
    private $controllersPath;
    private $controllersNamespace;

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
            $classAnnotation = new Annotation($className);

            foreach ($classReflector->getMethods() as $methodReflector) {

                if($this->isControllerFind)
                    break;

                $methodAnnotation = $classAnnotation->getMethod($methodReflector->name);
                
                if( $methodAnnotation->hasAnnotation("Route") &&
                    $this->checkRequestMethod($methodAnnotation) &&
                    $this->checkRequestedURI($methodAnnotation) ){

                    $this->isControllerFind = true;
                    $this->findRoute['class'] = $classReflector;
                    $this->findRoute['method'] = $methodReflector;
                }

            }

        }

        return $this->isControllerFind;
    }

    public function getResponse(){
        if($this->isControllerFind){

            $parameters = array();
            $methodReflection = $this->findRoute['method'];

            foreach ($methodReflection->getParameters() as $parameter){
                if($parameter->getClass() != null) {
                    $param = $this->getResponseParametersValue($parameter->getType());
                    $parameters[] = $param;
                }
                else{
                    $parameters[] = null;
                }
            }

            // Get response of request and return it to response sender.
            $controllerObject = $this->findRoute['class']->newInstance();
            return $methodReflection->invokeArgs($controllerObject, $parameters);
        }
        return null;
    }

    private function getResponseParametersValue($dataType){
        switch ($dataType){
            case 'Dor\Util\Request':
                return $this->request;
                break;
            case 'Illuminate\Database\Capsule\Manager':
                return Kernel::$capsule;
                break;
            default:
                return null;
        }
    }

    private function checkRequestMethod(MethodAnnotation $annotations){
        if($annotations->hasAnnotation('Method')){
            $annotation = $annotations->getAnnotation('Method');
            if(is_array($annotation)){
                foreach ($annotation as $method){
                    if(strtolower(trim($method)) == $method){
                        return true;
                    }
                }
                return false;
            }
            elseif(strtolower(trim($annotation)) == $this->request->requestType){
                return true;
            }
            else
                return false;
        }
        else
            return true;
    }

    private function checkRequestedURI(MethodAnnotation $annotations){
        if(! $annotations->hasAnnotation('Route')) {
            return false;
        }
        $annotation = $annotations->getAnnotation('Route');
        if(is_array($annotation)){
            foreach ($annotation as $route){
                if($this->checkRoute($route))
                    return true;
            }
            return false;
        }
        else{
            return $this->checkRoute($annotation);
        }
    }

    private function checkRoute($route){

        // var_dump($route);
        // var_dump($this->request);

        $preg1 = str_replace(
            "/",
            "\/",
            preg_replace(
                "/{(\w*)}/",
                "(\w|-)*",
                $route
            )
        );
        $isThisRoute = preg_match(
            '/^' . $preg1 . '$/',
            $this->request->uri,
            $r
        );

        // If this method is correct method to get response for this request
        if ($isThisRoute) {
            preg_match_all("/{(\w*)}/",$route,$params_key);

            $tmpUri = '~' . $this->request->uri . '~';
            $tmpRoute = '~' . $route . '~';
            $preg2 = preg_split ("/{(\w*)}/", $tmpRoute);
            $preg3 = '/' . str_replace("/","\/","(" . implode(")|(", $preg2) . ")") . '/';
            $preg4 = preg_split ($preg3 , $tmpUri);

            // Remove first and last element of array
            array_pop($preg4);
            array_shift($preg4);
            $this->request->inputParams = array_combine($params_key[1], $preg4);
            return true;
        }
        else{
            return false;
        }
    }
}