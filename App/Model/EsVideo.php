<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/16
 * Time: 上午12:22
 */

namespace App\Model;

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

}