<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/4/19
 * Time: 下午11:30
 */

namespace App\Model;

use EasySwoole\Core\Component\Di;

class News extends EsBase
{
    public function __construct()
    {
        $this->index = 'wudy_test';
        $this->type = 'wudy_test';
        parent::__construct();
    }

    public function SearchList(array $searchParams, $type='filter', $participleType = 'match'){ //match , term

        $params = [
            "index" => $this->index,
            "type" => $this->type,
            "client" => ['ignore' => [400,403,404, 405,408,409, 500], 'minimum_should_match' => 1],
//            "analyzer" => "ik_max_word", //默认是standard， 改为ik_max_word后 搜索"北京"，就能实现精确搜索
//            "body" => ['analyzer' => 'ik_max_word']
        ];

        foreach ($searchParams as $key => $value){
            $params['body']['query']['bool'][$type][] = [
                $participleType => [$key => strval($value)]
            ] ;
        }

        print_r($params);  //别删除

        return Di::getInstance()->get('ES')->search($params);
    }

}