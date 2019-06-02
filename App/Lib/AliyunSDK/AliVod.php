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
    /**
     * @var string
     */
    private $regionId = "cn-shanghai";

    /**
     * @var \DefaultAcsClient
     */
    private $client;

    /**
     * @var OssClient
     */
    private $ossClient;

    public function __construct()
    {
        $profile = \DefaultProfile::getProfile($this->regionId, \Yaconf::get("aliyunconf.accessKeyId"), \Yaconf::get("aliyunconf.accessKeySecret"));
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
        $result =  $this->client->getAcsResponse($request);
        if(empty($result) || empty($result->VideoId))
        {
            throw new \Exception('获取上传凭证不合法');
        }
        return $result;
    }

    /**
     * 使用上传凭证和地址初始化OSS客户端
     * @param $uploadAuth
     * @param $uploadAddress
     * @return OssClient
     * @throws OssException
     */
    public function initOssClient($uploadAuth, $uploadAddress)
    {
        $this->ossClient = new OssClient($uploadAuth['AccessKeyId'], $uploadAuth['AccessKeySecret'], $uploadAddress['Endpoint'], false , $uploadAuth['SecurityToken']);
        $this->ossClient->setTimeout(86400*7);
        $this->ossClient->setConnectTimeout(10);
    }

    /**
     * 上传文件
     * @param $uploadAddress
     * @param $localFile
     * @return null
     * @throws OssException
     */
    public function uploadLocalFile($uploadAddress, $localFile)
    {
        return $this->ossClient->uploadFile($uploadAddress['Bucket'], $uploadAddress['FileName'], $localFile);
    }

    /**
     * 获取视频信息
     * @param int $videoId
     * @return array|mixed|\SimpleXMLElement
     * @throws \ClientException
     * @throws \ServerException
     */
    public function getPlayInfo($videoId=0)
    {
        if(empty($videoId))
        {
            return [];
        }
        $request = new vod\GetPlayInfoRequest();
        $request->setVideoId($videoId);
        $request->setAcceptFormat("JSON");
        return $this->client->getAcsResponse($request);
    }


}