<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/16
 * Time: 上午9:56
 */

namespace App\HttpController\Api;

use App\Model\EsVideo;
use EasySwoole\Core\Http\Message\Status;

class Search extends Base
{
    public function index()
    {
        $keyword = $this->params['keyword'];
        if(empty($keyword))
        {
            return $this->writeJson(Status::CODE_OK, 'OK', $this->getPagingList(0, [], false));
        }

        $esObj = new EsVideo();
        $result = $esObj->searchByName($keyword, $this->params['from'], $this->params['page_size']);

        if(empty($result))
        {
            return $this->writeJson(Status::CODE_OK, 'success', $this->getPagingList(0, [], false));
        }

        $hits = $result['hits']['hits'] ?? [] ;
        $total = $result['hits']['total'];
        $resData = [];
        foreach ($hits as $hit){
            $source = $hit['_source'];
            $resData[] = [
                'id' => $hit['_id'],
                'name' => $source['name'],
                'image' => $source['image'],
                'url' => $source['url'],
                'type' => $source['type'],
                'status' => $source['status'],
                'keywords' => [$keyword],
            ];
        }
        return $this->writeJson(Status::CODE_OK, 'OK', $this->getPagingList($total['value'] ?? 0, $resData, false ));


    }
}