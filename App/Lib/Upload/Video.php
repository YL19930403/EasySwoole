<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/28
 * Time: 下午2:44
 */

namespace App\Lib\Upload;

class Video extends Base
{
    /**
     * fileType
     * @var string
     */
//    public $fileType = 'video';

    public $maxSize = 122;

    public function __construct($request)
    {
//        $this->fileType = 'video';  //方式1
        $this->setFileType('video'); //方式2
        parent::__construct($request);
    }

    /**
     * 文件后缀的mediaType
     * @var array
     */
    public $fileExtTypes = [
        'mp4',
        'x-flv'
    ];
}