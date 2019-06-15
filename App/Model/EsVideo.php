<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/16
 * Time: 上午12:22
 */

namespace App\Model;

use EasySwoole\Core\Component\Di;

class EsVideo
{
    private $index = 'video';  //索引
    private $type = 'video';

    public function searchByName($name, $type='match')
    {
        $name = trim($name);
        if(empty($name))
        {
            return [];
        }

        $params = [
            "index" => $this->index,
            "type" => $this->type,
            "body" => [
                "query" => [
                    $type => [
                        "name" => $name,
                    ],
                ],
            ],
        ];

        $esClient = Di::getInstance()->get("ES");
        return $esClient->search($params);
    }
}