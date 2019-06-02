<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/25
 * Time: 上午11:17
 */

namespace App\HttpController\Api;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;

/**
 * Class Base
 * @package App\HttpController\Api
 * @property array $params
 */
class Base extends Controller
{

    public $params = [];

    /**
     * Api模块下的基础类库
     * 子类Index继承自抽象父类Controller, 父类中有抽象方法abstract index() , 那么子类也必须实现该index方法
     */
    public function index()
    {

    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * 可作为拦截器使用
     * @param $action
     * @return bool|null
     */
    public function onRequest($action): ?bool
    {
        $this->getParams();
        return parent::onRequest($action);
    }

    /**
     * 获取请求参数
     */
    private function getParams()
    {
        $params = $this->request()->getRequestParam();
        $params['page_no'] = empty($params['page_no']) ? \Yaconf::get("page.page_no") : intval($params['page_no']);
        $params['page_size'] = empty($params['page_size']) ? \Yaconf::get("page.page_size") : intval($params['page_size']);
        $params['from'] = ( $params['page_no'] - 1 ) * $params['page_size'];  //切片json数据时需要
        $this->params = $params;
    }

    /**
     * 获取分页
     * @param int $count
     * @param array $data
     * @return array
     */
    public function getPagingList($count=0, array $data=[])
    {
        $totalPage = ceil($count / $this->params['page_size']);

        $videoSpliceList = array_splice($data, $this->params['from'], $this->params['page_size']);
        return [
            'total_page' => $totalPage,
            'page_size' =>  $this->params['page_size'],
            'count' => $count,
            'lists' => $videoSpliceList
        ];
    }

    public function onException(\Throwable $throwable, $actionName): void
    {
        parent::onException($throwable, $actionName);
//          $this->writeJson(400, '请求不合法');
    }

}