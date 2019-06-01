<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/1
 * Time: 上午10:15
 */

namespace App\Lib\AliyunSDK;

require_once EASYSWOOLE_ROOT . '/App/Lib/AliyunSDK/aliyun-php-sdk-core/Config.php';
require_once EASYSWOOLE_ROOT . '/App/Lib/AliyunSDK/aliyun-oss-php-sdk/autoload.php';

use vod\Request\V20170321 as vod;
use OSS\OssClient;
use OSS\Core\OssException;

class AliVod
{
    private $regionId = "cn-shanghai";
    private $client;


    public function __construct()
    {
        $profile = \DefaultProfile::getProfile($this->regionId, \Yaconf::get("aliyun.accessKeyId"), \Yaconf::get("aliyun.accessKeySecret"));
        $this->client = new \DefaultAcsClient($profile);
    }

    /**
     * 获取视频上传地址和凭证
     * @param $title
     * @param $fileName
     * @param array $data  其他参数
     * @return CreateUploadVideoResponse 获取视频上传地址和凭证响应数据
     */
    function createUploadVideo($title, $fileName, array $data=[]) {
        $request = new vod\CreateUploadVideoRequest();
        $request->setTitle($title);
        $request->setFileName($fileName);
        if(!empty($data['description']))
        {
            $request->setDescription($data['description']);  //视频文件描述
        }
        $request->setCoverURL("http://img.alicdn.com/tps/TB1qnJ1PVXXXXXCXXXXXXXXXXXX-700-700.png");
//        $request->setTags("tag1,tag2");
        $request->setAcceptFormat('JSON');
        return $this->client->getAcsResponse($request);
    }
}