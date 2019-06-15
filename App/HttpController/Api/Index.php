<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/25
 * Time: 上午10:36
 */

namespace App\HttpController\Api;

use App\Model\Video;
use EasySwoole\Core\Component\Di;
use App\Lib\Redis\Redis;
use EasySwoole\Core\Http\Message\Status;
use App\Lib\AliyunSDK\AliVod;
use Elasticsearch\ClientBuilder;
use EasySwoole\Core\Component\Trigger;

class Index extends Base
{
    //子类Index继承自抽象父类Controller, 父类中有抽象方法abstract index() , 那么子类也必须实现该index方法

    //http://wudy.easyswoole.cn:8090/api/index/index
    public function index()
    {
        $this->response()->write(1);
    }

    //http://wudy.easyswoole.cn:8090/api/video?age=0
    //http://wudy.easyswoole.cn:8090/api/index/video
    //http://127.0.0.1:9501/api/index/video
    //在nginx中配置了反向代理，默认访问webroot/index.html, 如果找不到要访问的文件，那么就代理到http://127.0.0.1:9501
    public function video()
    {
//        $data = [
//            'name' => 'wudy',
//            'age' => $this->request()->getRequestParam('age'),
//            'params' => $this->request()->getRequestParam(),
//
//        ];
//        return $this->writeJson(200, 'ok', $data);

        //查询数据库(MysqliDb方式)
        //在mainServerCreate方法中已经注入过了MYSQL
        $db = Di::getInstance()->get('MYSQL');
        $result = $db->where('id', 15)->getOne('video');
        return $this->writeJson(200, 'ok', $result);

        //查询数据库(TpORM)
//        $vModel = new Video();
//        $result = $vModel->getVideoBy(1);
//        return $this->writeJson(200, 'ok', $result);
    }

    public function getRedis()
    {
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1',6379,5);
//        $redis->set('test', 'this is aaa', 60);
//        $result = $redis->get('test');
//        return $this->writeJson(200, 'ok', $result);
        $data = [
            'name' => 'timo',
            'age' => 30,
        ];
        //Redis操作方法1：
        //自己封装的Redis类
        Redis::getInstance()->set('test', $data);
        $result = Redis::getInstance()->get('test');

        //Redis操作方法2:
        //在mainServerCreate方法中已经注入过了REDIS
//        $redisDb = Di::getInstance()->get('REDIS');
//        $redisDb->set('test', $data, 180);
//        $result = $redisDb->get('test');
        return $this->writeJson(200, 'ok', $result);

    }

    //http://wudy.easyswoole.cn:8090/api/index/yaConf
    public function yaConf()
    {
        $result = \Yaconf::get('redis');
        return $this->writeJson(200, 'ok', $result);

    }

    //http://wudy.easyswoole.cn:8090/api/index/pub?age=12
    public function pub()
    {
        $params = $this->request()->getRequestParam();
        Redis::getInstance()->rPush('redis_test', $params['age']);
    }

    //测试elasticsearch
    public function testEs()
    {

        $host = \Yaconf::get('es.host');
        $params = [
            "index" => "video",
            "type" => "video",
//            "id" => 1
            "body" => [
                "query" => [
                    "match" => [
                        "name" => "刘德华",
                    ],
                ],
            ],
        ];
        $builder = ClientBuilder::create();
        $client = $builder->setHosts([$host[1]])->build();
//        $result = $client->get($params);
        $result = $client->search($params);
        print_r(array_column($result, '_source'));

        return $this->writeJson(Status::CODE_OK, 'success', $result);
    }

    /*
     * 测试阿里云视频上传
     */
    public function testAliyun()
    {
        $vodObj = new AliVod();
        $title = 'test_aliyun_upload';
        $videoName = '1.mp4';
        $result = $vodObj->createUploadVideo($title, $videoName);

//        [VideoId] => 09f05e4911784dab9c9dbf3fc5bc13cb
//        [RequestId] => 29D9886A-B7BB-4152-B482-2DEC5BCB9B60
//        [UploadAddress] => base_64
//        [UploadAuth] => base_64

        $uploadAddress = json_decode(base64_decode($result->UploadAddress), true);
        $uploadPath = json_decode(base64_decode($result->UploadAuth), true);
//        print_r($uploadAddress);
//        Array
//        (
//            [Endpoint] => https://oss-cn-shanghai.aliyuncs.com
//            [Bucket] => outin-feab835d83ab11e9993d00163e00b174
//            [FileName] => sv/42058ff9-16b11f67415/42058ff9-16b11f67415.mp4
//        )

//        print_r($uploadPath);
//        Array
//        (
//            [SecurityToken] => CAIS0AR1q6Ft5B2yfSjIr4j4O8LEiJ1CgpWCTxPc3UU9VOdIvYfMtTz2IH9IdHVoAO8fvvU0m2tY7PsZlrUqFMUcHRObNJchsckKqF/6JpfZv8u84YADi5CjQbo+1ucimZ28Wf7waf+AUBXGCTmd5MMYo9bTcTGlQCZuW//toJV7b9MRcxClZD5dfrl/LRdjr8lo1xGzUPG2KUzSn3b3BkhlsRYe72Rk8vaHxdaAzRDcgVbmqJcSvJ+jC4C8Ys9gG519XtypvopxbbGT8CNZ5z9A9qp9kM49/izc7P6QH35b4RiNL8/Z7tQNXwhiffobHa9YrfHgmNhlvvDSj43t1ytVOeZcX0akQ5u7ku7ZHP+oLt8jaYvjP3PE3rLpMYLu4T48ZXUSODtDYcZDUHhrEk4RUjXdI6Of8UrWSQC7Wsr217otg7Fyyk3s8MaHAkWLX7SB2DwEB4c4aEokVW4RxnezW6UBaRBpbld7Bq6cV5lOdBRZoK+KzQrJTX9Ez2pLmuD6e/LOs7oDVJ37WZtKyuh4Y49d4U8rVEjPQqiykT0pFgpfTK1RzbPmNLKm9baB25/zW+PdDe0dsVgoIFKOpiGWG3RLNn+ztJ9xbkeE+sKUwqHF+cM7TQd+vdlVVFiIIIc89FA+u/LstBnLqOW6DSzt5XR/uPugptgRuRo8I6372bTJ42WG5Ub9O/dpxJ3lP0R0WgmydnBDx/Sfu2kKvRhpkRvvZEpPtwzIij/gLZZEiazRmyhefo5XmPXFTQmn8l5pAMmy/60xXudvbE2R0EQDY9YCGoABUwTIMi9PjccPZ6DPyv8nhgAHiSxa+BlFjUxMwWy43I/eMtW+slsadyY/nh6zTIZi1bu3bUcok6v05sJTez8bJEZG9rQohd1bDXOQoOaaDEUVxdoUAGqvWMuT9KpValNlv/bbO9Xdsp7O0YbP8zQktkD+MJETJIkQuLEVwMW70wk=
//            [AccessKeyId] => STS.NKMpxpeBc5WiM5m9EhXhdRBgW
//            [ExpireUTCTime] => 2019-06-01T08:34:52Z
//            [AccessKeySecret] => EbnxpncHCw3cSSdgv1fbxnDZe47HejNznyfAR1SJvKnK
//            [Expiration] => 3600
//            [Region] => cn-shanghai
//        )
        $videoFile = '/Users/yuliang/EasySwoole/webroot/video/2019/06/e69c56cc24d25a1c.mp4';
        $vodObj->initOssClient($uploadPath, $uploadAddress);
        $res = $vodObj->uploadLocalFile($uploadAddress, $videoFile);
        print_r($res);
    }


}