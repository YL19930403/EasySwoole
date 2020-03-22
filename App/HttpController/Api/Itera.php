<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/27
 * Time: 下午3:48
 */

use EasySwoole\Core\Component\Logger;

class MyIterator implements Iterator
{
    private $var = [];
    public function __construct(array $array = array())
    {
        if(is_array($array)){
            $this->var = $array;
        }
    }

    //重置索引游标的指向第一个元素
    public function rewind(){
        echo "rewinding\n";
        reset($this->var);
    }
    //返回当前索引游标指向的元素
    public function current()
    {
        $var = current($this->var);
        echo "current: $var\n";
        return $var;
    }
    //返回当前索引游标指向的元素的键名
    public function key(){
        $var = key($this->var);
        echo "key: $var\n";
        return $var;
    }

    //移动当前索引游标指向下一元素
    public function next() {
        $var = next($this->var);
        echo "next: $var\n";
        return $var;
    }
    public function valid() {  //如果执行valid返回false，则循环就此结束
        $var = $this->current() !== false;
        echo "valid: {$var}\n";
        return $var;
    }

}

/**
 * Class Itera
 * @package App\HttpController\Api
 */
class Itera
{
    public function index()
    {
        $values = ['php'=>'wudy','java'=>'timo','python'=>'peter'];
        $it = new MyIterator($values);

        foreach ($it as $key => $value) {
//            print_r("$key: $value\n");
            $param[$key] = $value;
            Logger::getInstance()->log(json_encode($param));
        }
    }
}



