<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/29/18
 * Time: 3:29 PM
 */

namespace Dor\Routing;

class Routing{
    protected $routes = [];
    protected $controllers_namespace;

    public function __construct($controllers_namespace){
        $this->controllers_namespace = $controllers_namespace;
    }

    public function new(){
        $route = new Route();
        $this->router[] = &$route;
        return &$route;
    }

    public getController($uri,$method){
        foreach($routes as $route){
            $controllerIfIsMatches = getControllerIfThisMatches($this->controllers_namespace);
            if($controllerIfIsMatches !== false){
                return $controllerIfIsMatches;
            }
        }
        return false;
    }
}
