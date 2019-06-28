<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/25/18
 * Time: 10:53 PM
 */

namespace Dor\Util;

use Dor\Kernel;
use Dor\Http\Response;

abstract class AbstractController
{
    const   CT_HTML = 'text/html',
            CT_JSON = 'application/json';

    public function render($file,$params = array()){
        return Kernel::$twig->render($file,$params);
    }

    public function get404RenderResponse($file,$params = array()):Response{
        $response = $this->getRenderResponse($file,$params);
        $response->setStatus(Response::STATUS[404]);
        return $response;
    }

    public function getResponse($render = '',$content_type = self::CT_HTML):Response{
        $response = new Response();
        $response->body = $render;
        $response->addHeader('Content-Type: ' . $content_type);
        return $response;
    }

    public function getJsonResponse($data):Response{
        return $this->getResponse(
            json_encode($data),
            self::CT_JSON
        );
    }

    public function getRenderResponse($file,$params = array()):Response{
        return $this->getResponse(
            $this->render(
                $file,
                $params
            )
        );
    }
}
