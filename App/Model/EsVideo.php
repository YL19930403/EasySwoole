<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/16
 * Time: 上午12:22
 */

namespace App\Model;

use EasySwoole\Core\Component\Di;

class EsVideo extends EsBase
{
//    public $index = 'video';  //索引
//    public $type = 'video';

    public function __construct()
    {
        $this->index = 'video';
        $this->type = 'video';
        parent::__construct();
    }

    /**
     * @param array $data
     * @return bool|mixed
     * @link:http://wudy.easyswoole.cn:9501/api/index/addToEs
     */
    public function add(array $data = []){
        if (empty($data)){
            return false;
        }
        $esBase = new EsBase();
        return $esBase->addOne($data);
    }

    /**
     * 更新
     * @param $id
     * @param array $data
     * @return bool|mixed
     */
    public function updateOne($id, array $data = []){
        if (empty($data)){
            return false;
        }

        $esBase = new EsBase();
        return $esBase->update($id, $data);
    }

}