<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/4/19
 * Time: 下午11:30
 */

namespace App\Model;

use EasySwoole\Core\Component\Di;
use Elasticsearch\ClientBuilder;

class News extends EsBase
{
    protected $PAGE_SIZE = 3;
    protected $PAGE_NO = 1;

    protected $scroll = "15s";

    public function __construct()
    {
        $this->index = 'wudy_test';
        $this->type = 'wudy_test';
        parent::__construct();
    }

    /**
     * @var array
     */
    protected $exceptParams = ['page_size', 'page_no', 'scroll_id', 'scroll'];

    /**
     * ES搜索
     * @param array $searchParams
     * @param string $type
     * @param string $participleType
     * @return mixed
     */
    public function SearchList(array $searchParams, $type = 'filter', $participleType = 'match'){ //match , term
        $page_no = intval($searchParams['page_no'] ?? $this->PAGE_NO);
        $page_size = intval($searchParams['page_size'] ?? $this->PAGE_SIZE);

        $reqParams = [
            "index" => $this->index,
            "type" => $this->type,
            "scroll" => $this->scroll, // 设置游标查询过期时间，不应该太长， 5m 表示设置scroll_id保留5分钟可用
            "from" => 0, // 使用scroll必须要将from设置为0
            "size" => $page_size,// 返回多少数量的文档，作用于单个分片
            "client" => ['ignore' => [400,403,404, 405,408,409, 500], 'minimum_should_match' => 1],
        ];

//        if (!empty($searchParams['scroll'])){
//            $reqParams['scroll'] = $searchParams['scroll']; //15s  设置游标查询过期时间，不应该太长， 5m 表示设置scroll_id保留5分钟可用
//            $reqParams["from"] = 0; // 使用scroll必须要将from设置为0
//            $reqParams["size"] = $searchParams['page_size'] ?? $this->PAGE_SIZE;  // 返回多少数量的文档，作用于单个分片
//        }

        foreach ($searchParams as $key => $value){
            if (in_array($key, $this->exceptParams)){
                break;
            }
            $reqParams['body']['query']['bool'][$type][] = [
                $participleType => [$key => strval($value)]
            ];

            $reqParams['body']['highlight']['fields'][$key] = [
                'pre_tags' => ["<span style='color: red'>"],
                'post_tags' => ["</span>"],
            ];
        }

//        print_r($reqParams);  //别删除

        $response = Di::getInstance()->get('ES')->search($reqParams);


        // 1. scroll适用一次性捞取较大的数据量
        /*
        $hits = $response['hits']['hits'] ?? [];
        while (count($hits) > 0) {
            foreach ($hits as $key => $value) {
                $scroll_id = $response['_scroll_id'];
                $response = Di::getInstance()->get('ES')->scroll([
                        'scroll_id' => $scroll_id,
                        'scroll' => $this->scroll,
                    ]
                );

            }
        }

         return $response;
        */

        //2. scroll分页

        if ($page_no <= $this->PAGE_NO){
            return $response;
        }

        $scroll_id = $response['_scroll_id'];

        $index = 1;
        while ($index < $page_no) {
            $response = Di::getInstance()->get('ES')->scroll([  // scoll方法没有返回_scroll_id
                    'scroll_id' => $scroll_id,
                    'scroll' => $this->scroll,
                ]
            );

            if (count($response['hits']['hits']) > 0){
                $scroll_id = $response['_scroll_id'];
            } else {
                break;
            }

            $index++;
        }

        return $response;

    }

    public function Search(array $searchParams, $type = 'filter', $participleType = 'match'){ //match , term
        $page_no = intval($searchParams['page_no'] ?? $this->PAGE_NO);
        $page_size = intval($searchParams['page_size'] ?? $this->PAGE_SIZE);

        $reqParams = [
            "index" => $this->index,
            "type" => $this->type,
            "scroll" => $this->scroll, // 设置游标查询过期时间，不应该太长， 5m 表示设置scroll_id保留5分钟可用
            "from" => 0, // 使用scroll必须要将from设置为0
            "size" => $page_size,// 返回多少数量的文档，作用于单个分片
            "client" => ['ignore' => [400,403,404, 405,408,409, 500], 'minimum_should_match' => 1],
        ];

        foreach ($searchParams as $key => $value){
            if (in_array($key, $this->exceptParams)){
                break;
            }
            $reqParams['body']['query']['bool'][$type][] = [
                $participleType => [$key => strval($value)]
            ];

            $reqParams['body']['highlight']['fields'][$key] = [
                'pre_tags' => ["<span style='color: red'>"],
                'post_tags' => ["</span>"],
            ];
        }

        $client = ClientBuilder::create()->build();
        $response = $client->search($reqParams);
        print_r($response);

        while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {
            $scroll_id = $response['_scroll_id'];
            $response = $client->scroll([
                "scroll_id" => $scroll_id,  //...using our previously obtained _scroll_id
                "scroll" => "30s"
            ]);
        }

        return $response;
    }

}
