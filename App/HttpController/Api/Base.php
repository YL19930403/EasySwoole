<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/25
 * Time: 上午11:17
 */

namespace App\HttpController\Api;

use EasySwoole\Core\Http\AbstractInterface\Controller;

class Base extends Controller
{
    /**
     * Api模块下的基础类库
     * 子类Index继承自抽象父类Controller, 父类中有抽象方法abstract index() , 那么子类也必须实现该index方法
     */
    public function index()
    {

    }

    /**
     * 可作为拦截器使用
     * @param $action
     * @return bool|null
     */
    public function onRequest($action): ?bool
    {
        return parent::onRequest($action);
    }

    public function onException(\Throwable $throwable, $actionName): void
    {
        parent::onException($throwable, $actionName);
//          $this->writeJson(400, '请求不合法');
    }

}