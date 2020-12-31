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
     * SQL 对比  ES
     * match_all  等价于  SELECT * FROM rooms  （查询简单的匹配所有文档）
     * match      等价于  类似于 SQL 的 LIKE 查询
     * MULTI_MATCH  （查询可以在多个字段上执行相同的 match）
     * RANGE  等价于  SQL 的 BETWEEN  （检索出那些落在指定区间内的文档） gt、gte、lt、lte
     * TERM   等价于  SELECT * FROM rooms WHERE houseId = 100 (用于精确值匹配，可能是数字、时间、布尔)
     * TERMS  等价于  SQL 的 IN 操作 ， SELECT * FROM rooms WHERE houseId IN (1087599828743, 1087817932342)
     * BOOL   等价于 SQL 的 AND 和 OR ：
     *      must  === AND 关系，必须 匹配这些条件才能检索出来
     *      must_not  ===  NOT 关系，必须不 匹配这些条件才能检索出来
     *      should  ===  OR 关系，至少匹配一条 条件才能检索出来
     *      filter  ===  必须 匹配，不参与评分
     */

    /**
     * http://wudy.easyswoole.cn:9501/api/news/getNewsList
     * @param: name => 蛋
     * @param: content => 死
     * @return bool
     */
    public function getNewsList(){
        $params = $this->request()->getRequestParam();

        print_r($params);
        $newsModel = new NewsModel();
        $result = $newsModel->SearchList($params, 'should', 'match');   //  === or
//        $result = $newsModel->SearchList($params, 'filter', 'term');
        return $this->writeJson(Status::CODE_OK, 'success', $result);
    }

    public function getNews(){
        $params = $this->request()->getRequestParam();

        print_r($params);
        $newsModel = new NewsModel();
        $result = $newsModel->Search($params, 'should', 'match');   //  === or
//        $result = $newsModel->SearchList($params, 'filter', 'term');
        return $this->writeJson(Status::CODE_OK, 'success', $result);
    }
}