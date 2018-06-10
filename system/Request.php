<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/25/18
 * Time: 8:17 PM
 */

namespace Dor\Util;

class Request {
    const   POST = 'post',
            GET = 'get',
            DELETE = 'delete',
            PUT = 'put';

    public $requestType = 'get';
    public $headers = array();
    public $uri = '/';
    public $host = 'localhost';
    public $inputParams = array();

    public $post = array();
    public $get = array();
    public $file = array();
}