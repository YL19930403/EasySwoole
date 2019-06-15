<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/9
 * Time: 上午11:53
 */

namespace App\Lib\AliyunSDK;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class  Sms{

    public static function sendSms($phone_no = '13074491521', $authCodeMT)
    {
        $app_key = \Yaconf::get("aliyunconf.accessKeyId");
        $app_secret = \Yaconf::get("aliyunconf.accessKeySecret");

        AlibabaCloud::accessKeyClient($app_key, $app_secret)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try{
//            $authCodeMT = mt_rand(100000,999999);
            $jsonTemplateParam = json_encode(['code'=>$authCodeMT]);

            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumbers' => $phone_no,
                        'SignName' => \Yaconf::get('aliyunconf.signName'),
                        'TemplateCode' => \Yaconf::get('aliyunconf.templateCode'),
                        'TemplateParam' => $jsonTemplateParam,
                    ],
                ])
                ->request();
//            print_r($result);
            return $result->toArray();
//            return $result;
        }catch(ClientException $e){
            echo $e->getErrorMessage() . PHP_EOL;
        }catch (ServerException $e){
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }
}