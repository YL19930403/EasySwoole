<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/4/18
 * Time: 上午12:01
 * Title: 注册全局注册处理异常方法
 */

namespace App\Exception;

use EasySwoole\Core\Http\Message\Status;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;

class ExceptionHandler
{
    public static function handle(\Throwable $exception, Request $request, Response $response){
        $data = [];
        if ($exception instanceof ApiException){
            $code = $exception->getCode();
            $msg = $exception->getMessage();
        } else {
            $code = $exception->getCode();
            if(!isset($code)|| $code < Status::RET_SUCCESS){
                $code = Status::RET_ERROR;
            }

            $msg = $exception->getMessage() ?? 'unknow error';
        }

        $data['code']=$code;
        $data['msg']=$msg;
        $result = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $response->writeHeader("Content-Type","application/json;charset=UTF-8")
            ->withHeader("Access-Control-Allow-Origin","*")
            ->write($result);
    }
}