<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/2
 * Time: 下午6:24
 */

namespace App\Lib\ApiCache;

use App\Model\Video as VideoModel;
use EasySwoole\Core\Component\Cache\Cache;

class Video
{
    //开启easyswoole每分钟定时自动写入数据(mainServerCreate)
    public function setIndexVideo()
    {
        $cats = array_keys(\Yaconf::get("category.cats"));
        array_unshift($cats, 0);
        $videoModel = new VideoModel();
        foreach ($cats as $catId){
            $condition = [];
            if(!empty($catId))
            {
                $condition['cat_id'] = $catId;
            }
            try{
                $data = $videoModel->getCacheVideoList(\Yaconf::get('page.cache_page_size'), $condition);
            }catch (\Exception $e){
                //报警：短信、邮件
                $data = [];
            }
            if(empty($data))
            {
                continue;
            }

            foreach ($data as &$list)
            {
                $list['create_time'] = date('Y-m-d h:i:s', $list['create_time']);
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }

            //写入json文件
            $json_path = EASYSWOOLE_ROOT.'/webroot/json';
            if(!is_dir($json_path))
            {
                mkdir($json_path, 0777, true);
            }

            $filePath = $json_path.'/'.$catId.'.json';
            //存入json文件
            $flag = file_put_contents($filePath, json_encode($data, JSON_UNESCAPED_UNICODE));

//            Cache::getInstance()->set('index_video_cat_id', );

            if(!$flag)
            {
                //TODO:报警：短信、邮件
            }
        }
    }
}