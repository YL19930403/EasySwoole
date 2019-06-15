<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/29
 * Time: 下午4:58
 */

namespace App\Lib;


/**
 * 反射有关的处理
 * Class ClassReflection
 * @package App\Lib
 */
class ClassReflection
{

    /**
     * @param $type
     * @param $supportedClass
     * @param array $param : 对应到反射类的构造方法接收的参数（例如：Upload\Base 的__construct($request) ）
     * @param bool $needInstance : 静态的类不需要实例化
     * @return bool
     */
    public function initClass($type, $supportedClass, $param = [], $needInstance = true)
    {
        if(!array_key_exists($type, $supportedClass))
        {
            return false;
        }

        $className = $supportedClass[$type];
        print_r($className);
        //如果$needInstance = false， 则表示静态类访问
        return ($needInstance ? (new \ReflectionClass($className))->newInstanceArgs($param) : $className);

    }

    public function uploadClassReflection()
    {
        return [
            'image' => "\App\Lib\Upload\Image",
            'video' => "\App\Lib\Upload\Video",
        ];
    }
}