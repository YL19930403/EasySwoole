<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/28
 * Time: 下午2:45
 */

namespace App\Lib\Upload;

use App\Lib;

/**
 * Class Base
 * @package App\Lib\Upload
 * @property  string fileType
 * @property array $fileExtTypes
 */
class Base
{
    /**
     * 上传文件的 file - key
     * @var string
     */
    private $type = '';

    private $size = 0;

    private $file = '';

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function setFields($value) {
        $this->fileType = $value;
    }

    public  function __construct($request, $type=null)
    {
        if(empty($type))
        {
            $this->request = $request;
            $files = $this->request->getSwooleRequest()->files;
            $this->type = array_keys($files)[0];
        }else{
            $this->type = $type;
        }
    }

    public function upload()
    {
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
        $file = $this->getFile($fileName);
        $flag = $videos->moveTo($file);
        if(!empty($flag))
        {
            return $this->file;
        }
        return false;
    }

    public function getFile($filenmae)
    {
        $pathInfo = pathinfo($filenmae);
        $extension = $pathInfo['extension'] ?? '';
        $dirname =  '/' . $this->type . '/' . date('Y') . '/' . date('m');
        $dir = EASYSWOOLE_ROOT . '/webroot/' . $dirname;
        if(!is_dir($dir))
        {
            mkdir($dir, 0777, true);
        }
        $baseName =  '/' . Lib\Utils::getFileKey($filenmae) . '.' . $extension;
        $this->file = $dirname . $baseName;
        return $dir . $baseName;
    }


    /**
     * checkSize description
     * @return bool
     */
    public function checkSize()
    {
        if(empty($this->size))
        {
            return false;
        }
        return true;
    }

    /**
     * checkMediaType description
     * @return bool
     * @throws \Exception
     */
    public function checkMediaType()
    {
        $clientMediaType = explode('/', $this->clientMediaType);
        $clientMediaType = $clientMediaType[1] ?? '';
        if(empty($clientMediaType))
        {
            throw new \Exception("上传{$this->type}文件不合法");
        }
        if(!in_array($clientMediaType, $this->fileExtTypes))
        {
            throw new \Exception("上传{$this->type}文件不合法");
        }
        return true;
    }
}