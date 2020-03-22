<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/16
 * Time: 上午9:27
 */

namespace App\Model;

use EasySwoole\Core\Component\Di;

/**
 * Class EsBase
 * @package App\Model
 * @property string index
 * @property string type
 */
class EsBase
{
    private $esClient = null;

    public function __construct()
    {
        $this->esClient = Di::getInstance()->get('ES');
    }

    /**
     * 根据名字全文模糊搜索
     * @param $name
     * @param int $from
     * @param int $page_size
     * @param string $type
     * @return array
     */
    public function searchByName($name, int $from = 0, int $page_size = 10, $type='match')
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
                'from' => $from,
                'size' => $page_size,
            ],
        ];

        return $this->esClient->search($params);
    }
}