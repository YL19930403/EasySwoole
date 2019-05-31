<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/29
 * Time: 下午5:36
 */

namespace App\HttpController\Api;

use \App\Model\Video as VideoModel;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Utility\Validate\Rule;
use EasySwoole\Core\Utility\Validate\Rules;
use EasySwoole\Core\Http\Message\Status;

class Video extends Base
{
    public function add()
    {
        $param = $this->request()->getRequestParam();
        Logger::getInstance()->log(json_encode($param));

        //校验规则
        $ruleObj = new Rules();
        $ruleObj->add('name', '视频名称错误')->withRule(Rule::REQUIRED)->withRule(Rule::MIN_LEN,1)->withRule(Rule::MAX_LEN, 20);
        $ruleObj->add('url', '视频地址错误')->withRule(Rule::REQUIRED)->withRule(Rule::MIN_LEN,1)->withRule(Rule::MAX_LEN,100);
        $ruleObj->add('content', '内容描述错误')->withRule(Rule::OPTIONAL);
        $ruleObj->add('cat_id', '分类错误')->withRule(Rule::REQUIRED)->withRule(Rule::INTEGER)->withRule(Rule::MIN,1)->withRule(Rule::MAX, 100);
        $validate = $this->validateParams($ruleObj);
        if($validate->hasError())
        {
//            print_r($validate->getErrorList());
            $errorStr = '';
            $errorList = $validate->getErrorList()->all();
            foreach ($errorList as $key=>$value)
            {
                $vData = $value->toArray();
                $errorStr .= $vData['message'] . 'and';
            }
//            return $this->writeJson(Status::CODE_BAD_REQUEST, $validate->getErrorList()->first()->getMessage());
            return $this->writeJson(Status::CODE_BAD_REQUEST, trim($errorStr, 'and'));
        }

        $data = [
            'name' => $param['name'] ?? '',
            'url' => $param['url'] ?? '',
            'image' => $param['image'] ?? '',
            'content' => $param['content'] ?? '',
            'create_time' => time() ,
            'status' => \Yaconf::get("status.normal") ,
        ];

        //插入
        try{
//            $modelObj = new VideoModel();
//            $videoId = $modelObj->add($data);

        }catch (\Exception $e){
            return $this->writeJson(Status::CODE_BAD_REQUEST, $e->getMessage());
        }

    }
}