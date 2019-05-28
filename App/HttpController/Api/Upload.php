<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/28
 * Time: 下午1:43
 */

namespace App\HttpController\Api;

class Upload extends Base
{
    //POST: http://wudy.easyswoole.cn:8090/api/upload/file
    public function file()
    {
        $request = $this->request();
        $uploadFile = $request->getUploadedFile('file');  //文件key
        $upload_config = \Yaconf::get('upload');
        $url = $upload_config['url'] . $uploadFile->getClientFilename();
        $flag = $uploadFile->moveTo($url);
        if(!$flag)
        {
            return $this->writeJson(1, 'upload fail!');
        }
        $data = [
            'url' => $url
        ];
        return $this->writeJson(200, 'success', $data);
    }
}