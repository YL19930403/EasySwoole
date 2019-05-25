<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/24
 * Time: 下午5:45
 */

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface;
use FastRoute\RouteCollector;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;

class Router
{
    function initialize(RouteCollector $routeCollector)
    {
        $routeCollector->get('/user', 'index.html');
        $routeCollector->get('rpc', '/Rpc/index');

        $routeCollector->get('/', function (Request $request, Response $response){
            $response->write('this router index');
        });
    }
}