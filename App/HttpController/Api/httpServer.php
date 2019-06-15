<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/26
 * Time: 下午1:42
 */

$http = new swoole_http_server('127.0.0.1', 9501);

$http->set([
    'work_num' => 16,
    'enable_static_handler' => true,
    'document_root' => '/Users/yuliang/EasySwoole/data',
]);


$http->on('request', function ($request, $response){
   $response->end(1);
});

$http->start();