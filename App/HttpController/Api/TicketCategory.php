<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/3/22
 * Time: 下午6:16
 * Title: 导入的工单分类的数据
 */

namespace App\HttpController\Api;

use App\Model\TicketCategory as TicketCategoryModel;
use EasySwoole\Core\Http\Message\Status;


class TicketCategory extends Base
{
    /**
     * @link: http://wudy.easyswoole.cn:9501/api/TicketCategory/getTicketList
     * @return bool
     */
    public function getTicketList(){

        $cat_name = strval($this->params['cat_name'] ?? '');  //测试
        $cat_level = intval($this->params['cat_level'] ?? 0);  //1

        $condition = [];
        if ($cat_name){
            $condition['cat_name'] = $cat_name;
        }

        if ($cat_level){
            $condition['cat_level'] = $cat_level;
        }

        try{
            $ticketCatModel = new TicketCategoryModel();
            $list = $ticketCatModel->getCategoryList($condition);
        }catch (\Throwable $throwable){
            return $this->writeJson(Status::CODE_BAD_REQUEST, $throwable->getMessage());
        }
        return $this->writeJson(Status::CODE_OK, 'success', $list);
    }
}