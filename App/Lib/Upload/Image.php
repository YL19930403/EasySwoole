<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/28
 * Time: 下午7:13
 */

namespace App\Lib\Upload;;

class Image extends Base
{
    public $maxSize = 122;

    public function __construct($request)
    {
        $this->fileExtTypes = [
            'png',
            'jpg',
            'jpeg'
        ];

        $this->setFields('image');
        parent::__construct($request);
    }
}