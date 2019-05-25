<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/25
 * Time: 上午10:36
 */

namespace App\HttpController\Api;

class Index extends Base
{
    //子类Index继承自抽象父类Controller, 父类中有抽象方法abstract index() , 那么子类也必须实现该index方法

    //http://wudy.easyswoole.cn:8090/api/index
//    public function index()
//    {
//    }

    //http://wudy.easyswoole.cn:8090/api/video?age=0
    public function video()
    {
        $data = [
            'name' => 'wudy',
            'age' => $this->request()->getRequestParam('age'),
            'params' => $this->request()->getRequestParam(),

        ];
        return $this->writeJson(200, 'ok', $data);
    }
}