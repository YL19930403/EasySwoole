<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/26
 * Time: 下午9:50
 */

namespace App\Lib\Redis;

use EasySwoole\Config;
use EasySwoole\Core\AbstractInterface\Singleton;

class Redis
{
    use Singleton;  //trait

    public $redis = '';

    const DEFAULT_TTL = 3600;

    private function __construct()
    {

        //判断redis扩展是否安装
        if(!extension_loaded('redis'))
        {
            throw new \Exception('redis.so文件不存在');
        }

        try {
//            $redis_config = Config::getInstance()->getConf('REDIS');  //读取的是根目录下的Config文件
//            $redis_config = Config::getInstance()->getConf('redis.REDIS'); //读取的是根目录下Config目录的redis文件

            $redis_config = \Yaconf::get('redis');  //读取Yaconf配置, ini/redis.ini文件
            $this->redis = new \Redis();
            $result = $this->redis->connect($redis_config['host'], $redis_config['port'], $redis_config['time_out']);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        if($result === false )
        {
            throw new \Exception('连接redis失败');
        }
    }


    /**
     * 在类内部调用本类当中的一个不可访问(如果是本类中，那就只能是不存在才不可访问，
     * 如果是在本类外不可访问还可能是没有访问权限)的方法时，不管是对象方式，还是静态方式，都只能触发__call()方法
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        return $this->redis->$name(...$arguments);
    }

    /**
     * @param string $key
     * @return bool|string
     */
    public function get(string $key='')
    {
        if(empty($key))
        {
            return '';
        }
        return $this->redis->get($key);
    }

    /**
     * @param string $key
     * @param $value
     * @param int $timeout
     * @return bool
     */
    public function set(string $key='', $value,$timeout=self::DEFAULT_TTL )
    {
        if(empty($key))
        {
            return false;
        }
        if(is_array($value))
        {
            $value = json_encode($value,JSON_UNESCAPED_UNICODE);
        }
        return $this->redis->set($key, $value, $timeout);
    }

    /**
     * @param string $key
     * @return string
     */
    public function lPop(string $key='')
    {
        if(empty($key))
        {
            return '';
        }
        return $this->redis->lPop($key);
    }

    /**
     * @param string $key
     * @param $value
     * @return int|string
     */
    public function lPush(string $key='', $value)
    {
        if(empty($key))
        {
            return '';
        }
        return $this->redis->lPush($key, $value);
    }

    /**
     * @param string $key
     * @return string
     */
    public function rPop(string $key='')
    {
        if(empty($key))
        {
            return '';
        }
        return $this->redis->rPop($key);
    }

    /**
     * @param string $key
     * @param $value
     * @return int|string
     */
    public function rPush(string $key='', $value)
    {
        if(empty($key))
        {
            return '';
        }
        return $this->redis->rPush($key, $value);
    }

    /**
     * 有序集合中对指定成员的分数加上增量 increment
     * @param $key
     * @param $increment
     * @param $member
     * @return bool|float
     */
    public function zinCrBy($key, $increment, $member)
    {
        if(empty($key) || empty($member))
        {
            return false;
        }
        return $this->redis->zincrby($key, $increment, $member);
    }

    /**
     * 返回有序集合中指定成员的排名，有序集成员按分数值递减(从大到小)排序
     * @param $key
     * @param int $start
     * @param int $stop
     * @param bool $type
     * @return array|bool
     */
    public function zRevRange111($key, $start=0, $stop=-1, $type=true)
    {
        if(empty($key))
        {
            return false;
        }
        return $this->redis->zrevrange($key, $start, $stop, $type);
    }

    /**
     * 计算给定的一个或多个有序集的并集,并将该并集(结果集)储存到$Output
     * @param $Output
     * @param array $ZSetKeys
     * @param array $Weights
     * @param string $aggregateFunction
     * @return bool|int
     */
    public function zUnionStore222($Output, array $ZSetKeys=[], array $Weights = [], $aggregateFunction='SUM')
    {
       if(empty($Output) || empty($ZSetKeys) )
       {
            return false;
       }

       // ZSetKeys 与  Weights必须保持一致
       if(count($ZSetKeys) !== count($Weights))
       {
            return false;
       }

       return $this->redis->zUnion($Output, $ZSetKeys, $Weights, $aggregateFunction);
    }

}