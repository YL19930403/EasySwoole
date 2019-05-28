<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/28
 * Time: 下午2:45
 */

namespace App\Lib\Upload;

/**
 * Class Base
 * @package App\Lib\Upload
 * @property  string fileType
 */
class Base
{
    /**
     * 上传文件的 file - key
     * @var string
     */
    private $type = '';

    private $size = 0;

    //private $fileType;

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function setFileType($value) {
        $this->fileType = $value;
    }

    public  function __construct($request)
    {
        $this->request = $request;
        $files = $this->request->getSwooleRequest()->files;
        $this->type = array_keys($files)[0];
        print_r($files);

    }

    public function upload()
    {
        var_dump($this->type);
        var_dump($this->fileType);
        if($this->type != $this->fileType)
        {
            return false;
        }

        $videos = $this->request->getUploadedFile($this->type);
        $this->size = $videos->getSize();
        $this->checkSize();
        $fileName = $videos->getClientFilename();
        $this->clientMediaType = $videos->getClientMediaType();
        $this->checkMediaType();

    }

    public function checkSize()
    {
        if(empty($this->size))
        {
            return false;
        }
    }

    public function checkMediaType()
    {

    }
}