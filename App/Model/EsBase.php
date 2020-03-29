<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/16
 * Time: 上午9:27
 */

namespace App\Model;

use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Logger;
use Elasticsearch\Common\Exceptions\MaxRetriesException;
use Elasticsearch\Common\Exceptions\TransportException;

/**
 * Class EsBase
 * @package App\Model
// * @property string $index
// * @property string $type
 */
class EsBase
{
    public $index = 'video';
    public $type = 'video';
    private $esClient = null;

    public function __construct()
    {
        $this->esClient = Di::getInstance()->get('ES');
    }

    public function __set($name, $value)
    {
        $this->$name = $value;

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

    /**
     * 更新一条数据
     * @param $id
     * @param $params
     * @return mixed
     */
    public function update($id, $params){
        $data = [
            'index' => $this->index,
            'type' => $this->type,
            'id' => $id,
            'body' => [
                'doc' => $params
            ]
        ];
        return $this->esClient->update($data);
    }

    /**
     * 获取单条详情
     * @param $id
     * @return mixed
     */
    public function getOneInfo($id){
        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'id' => $id
        ];

        try {
            $res = $this->esClient->get($params);
        } catch (\Exception $ex){
            Logger::getInstance()->log($ex->getMessage());
            $res = [];
        }catch (TransportException $ex){ //ES相关的异常
            $previous = $ex->getPrevious();
            if ($previous instanceof MaxRetriesException){
//                echo "Max retries!";
                Logger::getInstance()->log('尝试了最大次数后失败');
            }
            Logger::getInstance()->log($ex->getMessage());
            $res = [];
        }

        return $res;
    }

    public function deleteOne($id){
        $params = [
            'id' => $id,
        ];
        return $this->esClient->index();
    }

    /**
     * 新增一条
     * @param array $data
     * @return mixed
     */
    public function addOne(array $data = []){
        $params = [
            "index" => $this->index,
            "type" => $this->type,
            "body" => $data
        ];
        return $this->esClient->index($params);
    }

    public function addBatch(array $data = []){
        if (empty($data)){
            return false;
        }

        $params = [
            "index" => $this->index,
            "type" => $this->type,
        ];

        for ($i=15 ;$i < 18 ; $i++){
            $params['body'][] = [
                "name" => "来啊， 快活啊打击报复简单吗减肥不能忍",
                "content" => "他于ID疯狂过v 不能方面的开始为空的父母呢为你的父母都看文件让烦恼吗",
                "_score" => 2,
                "cat_id" => 3,
                "image" => "www.baudu.com",
                "url" => "www.ifeng.cn",
                "type" => 2,
                "status" => 1,
                "school_name" => "湖南科技学院",
            ];
        }


    }
}