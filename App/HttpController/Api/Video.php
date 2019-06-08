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
use EasySwoole\Core\Swoole\Task\TaskManager;
use EasySwoole\Core\Utility\Validate\Rule;
use EasySwoole\Core\Utility\Validate\Rules;
use EasySwoole\Core\Http\Message\Status;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Cache\Cache;
use App\Lib\ApiCache\Video as VideoCache;

class Video extends Base
{

    /**
     * 视频详情
     * @return bool
     */
    public function getVideoDetail()
    {
        $id = intval($this->params['id']);
        if(empty($id))
        {
            return $this->writeJson(Status::CODE_BAD_REQUEST, '请求不合法');
        }

        $mysqlDb = Di::getInstance()->get('MYSQL');
        $video = $mysqlDb->where('id', $id)->getOne('video', '*');
        if(empty($video) || $video['status'] != \Yaconf::get('status.normal'))
        {
            return $this->writeJson(Status::CODE_BAD_REQUEST, '该视频不存在');
        }

        $video['video_duration'] = gmstrftime("%H:%M:%S", $video['video_duration']);
        //播放数统计逻辑
        TaskManager::async(function () use ($id){
            Di::getInstance()->get('REDIS')->zinCrBy(\Yaconf::get('redis.video_play_key'), 1, $id);
        });

        return $this->writeJson(Status::CODE_OK, 'OK', $video);
    }

    //http://wudy.easyswoole.cn:8090/api/video/list
    /**
     * 获取视频列表  -- 第一套方案：Mysql
     * @return bool
     */
    public function list()
    {
        $condition = [];
        if(!empty($this->params['cat_id']))
        {
            $condition['cat_id'] = intval($this->params['cat_id']);
        }
        try{
            $videoModel = new VideoModel();
            $data = $videoModel->getVideoList($this->params['page_no'],$this->params['page_size'], $condition);
        }catch (\Exception $e){

            return $this->writeJson(Status::CODE_BAD_REQUEST, $e->getMessage());
        }

        if(!empty($data['list']))
        {
            foreach ($data['list'] as &$list)
            {
                $list['create_time'] = date('Y-m-d h:i:s', $list['create_time']);
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }
        }

        return $this->writeJson(Status::CODE_OK, 'success', $data);
    }

    /**
     * http://wudy.easyswoole.cn:8090/api/video/apilist?cat_id=0&page_no=1&page_size=2
     * 获取视频列表  -- 第二套方案:静态化api
     * @return bool
     */
    public function apiList()
    {
        $catId = empty($this->params['cat_id']) ? 0: intval($this->params['cat_id']); //0:查询所有的cat_id
        $videoFile = EASYSWOOLE_ROOT . '/webroot/json/' . $catId. '.json';
        //读取json文件获取数据
//        $videoData = is_file($videoFile) ? file_get_contents($videoFile):[];
//        $videoData = empty($videoData) ? []: json_decode($videoData, true);

        //读取Swoole Table
//        $videoData = Cache::getInstance()->get('index_video_cat_id_'.$catId);
//        $videoData = !$videoData ? [] : $videoData;

        //读取redis
        $videoCache = (new VideoCache());
        try{
            $videoData = $videoCache->getCacheList($catId);
        }catch (\Exception $ex){
            return $this->writeJson(Status::CODE_BAD_REQUEST, $ex->getMessage());
        }
        $count = count($videoData);
        return $this->writeJson(Status::CODE_OK, 'success', $this->getPagingList($count, $videoData));
    }


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