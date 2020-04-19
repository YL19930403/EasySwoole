<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/4/19
 * Time: 下午11:56
 */

namespace App\HttpController\Api;

use App\Model\news as NewsModel;
use EasySwoole\Core\Http\Message\Status;

class News extends Base
{
    /**
     * http://wudy.easyswoole.cn:9501/api/news/getNewsList
     * @param: name => 蛋
     * @param: name => 死
     * @return bool
     */
    public function getNewsList(){
        $params = $this->request()->getRequestParam();

        print_r($params);
        $newsModel = new NewsModel();
        $result = $newsModel->SearchList($params, 'should', 'match');
        return $this->writeJson(Status::CODE_OK, 'success', $result);
    }
}