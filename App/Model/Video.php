<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/26
 * Time: 上午8:53
 */

namespace App\Model;

use EasySwoole\Mysqli\TpORM;
use EasySwoole\Config;


class Video  extends Base //extends TpORM
{

    public function __construct()
    {
        $this->tableName = 'video';
        parent::__construct();
    }

    public function getVideoBy($id)
    {
        return $this->where(['id' => 1])->field('*')->find();
    }


}