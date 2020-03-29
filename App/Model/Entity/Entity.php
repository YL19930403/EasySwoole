<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/3/29
 * Time: 下午8:58
 */

namespace App\Model\Entity;

class Entity{
    /**
     * @return array
     */
    public function toArray()
    {
        $pros = get_object_vars($this);
        return $pros;
    }
}