<?php
/**
* User: Amir Aslan Aslani
* Date: 7/5/18
* Time: 5:52 PM
*/

namespace Dor\Routing;

class ResultController{
    private $rclass,$rmethod,$ivariables;
    public function __construct($controller_namespace, $controller_address, $input_variables){
        $controller_address = explode('@',$controller_address);

        $className = $this->controllersNamespace . $controller_address[0];
        $this->rclass = new \ReflectionClass($className);
        $this->rmethod = $this->rclass->getMethod($controller_address[1]);
    }
}
