<?php
/**
* User: Amir Aslan Aslani
* Date: 5/29/18
* Time: 3:36 PM
*/

namespace Dor\Routing;

class Route{
    public $name,
        $uri = [],
        $method = Method::ANY,
        $controller = null;

    // Setters
    public function method($method){
        $this->method = $method;
        return &$this;
    }

    public function uri($method, $inputs = []){
        $this->uri[] = [$uri, $input];
        return &$this;
    }

    public function name(string $name){
        $this->name = $name;
        return &$this;
    }

    public function controller(string $controller){
        $this->controller = $controller;
        return &$this;
    }

    // Inner functionalities
    public function getControllerIfThisMatches($controller_namespace){
        if(! in_array($method, $this->method))
            return false;

        $uriMatches = $this->isUriMatchesRoutes($uri);
        if(! $uriMatches)
            return false;

        return new ResultController($controller_namespace, $this->controller, $uriMatches);
    }

    public function isUriMatchesRoutes($uri){
        foreach($uri as $uri_param){
            $uriMatched = isUriMatchesRoute($uri_param[0],$uri,$uri_param[1]);
            if($uriMatched !== false){
                return $uriMatched;
            }
        }
        return false;
    }

    protected function isUriMatchesRoute($route,$uri,$variables){
        preg_match_all("/{(\w*)}/",$route,$params_key);
        // $params_key[0] -> Variables Name
        // $params_key[1] -> Replace Strings

        $replace = [];
        $with = [];
        foreach($params_key[1] as $key => $param){
            $replace[] = "/$param/";
            $withTmp = '';
            if(isset($variables[$params_key[0][$key]])){
                $withTmp = $variables[$params_key[0][$key]];
            }
            else{
                $withTmp = '.+';
            }
            $with[] = "(?<$param>$withTmp)";
        }

        $pattern = str_replace(
            "/",
            "\/",
            preg_replace(
                $replace,
                $with,
                $route
            )
        );

        $isThisRoute = preg_match(
            '/^' . $pattern . '$/',
            $uri,
            $matches
        );

        return $isThisRoute ? $matches : false;
    }
}
