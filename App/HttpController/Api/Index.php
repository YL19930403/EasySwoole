<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/25
 * Time: 上午10:36
 */

namespace App\HttpController\Api;

use App\Model\Video;
use EasySwoole\Core\Component\Di;
use App\Lib\Redis\Redis;

class Index extends Base
{
    //子类Index继承自抽象父类Controller, 父类中有抽象方法abstract index() , 那么子类也必须实现该index方法

    //http://wudy.easyswoole.cn:8090/api/index/index
    public function index()
    {
        $this->response()->write(1);
    }

    //http://wudy.easyswoole.cn:8090/api/video?age=0
    //http://wudy.easyswoole.cn:8090/api/index/video
    public function video()
    {
//        $data = [
//            'name' => 'wudy',
//            'age' => $this->request()->getRequestParam('age'),
//            'params' => $this->request()->getRequestParam(),
//
//        ];
//        return $this->writeJson(200, 'ok', $data);

        //查询数据库(MysqliDb方式)
        //在mainServerCreate方法中已经注入过了MYSQL
        $db = Di::getInstance()->get('MYSQL');
        $result = $db->where('id', 15)->getOne('video');
        return $this->writeJson(200, 'ok', $result);


        //查询数据库(TpORM)
//        $vModel = new Video();
//        $result = $vModel->getVideoBy(1);
//        return $this->writeJson(200, 'ok', $result);
    }

    public function getRedis()
    {
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1',6379,5);
//        $redis->set('test', 'this is aaa', 60);
//        $result = $redis->get('test');
//        return $this->writeJson(200, 'ok', $result);
        $data = [
            'name' => 'ioio',
            'age' => 29,
        ];
        //Redis操作方法1：
        //自己封装的Redis类
        Redis::getInstance()->set('test', $data);
        $result = Redis::getInstance()->get('test');

        //Redis操作方法2:
        //在mainServerCreate方法中已经注入过了REDIS
//        $redisDb = Di::getInstance()->get('REDIS');
//        $redisDb->set('test', $data, 180);
//        $result = $redisDb->get('test');
        return $this->writeJson(200, 'ok', $result);

    }
}