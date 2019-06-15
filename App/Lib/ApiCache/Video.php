<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/2
 * Time: 下午6:24
 */

namespace App\Lib\ApiCache;

use App\Model\Video as VideoModel;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Cache\Cache;
use function PHPSTORM_META\type;

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
//            $filePath = $json_path.'/'.$catId.'.json';
            //存入json文件
//            $flag = file_put_contents($filePath, json_encode($data, JSON_UNESCAPED_UNICODE));

            //存入Swoole Table
//            $flag = Cache::getInstance()->set($this->getCatIdKey($catId), $data);

//            $redisDb = Di::getInstance()->get('REDIS');
//            $flag = $redisDb->set($this->getCatIdKey($catId), json_encode($data, JSON_UNESCAPED_UNICODE));

            $cacheType = \Yaconf::get('base.cacheType');
            switch ($cacheType){
                case 'file':    //Json文件存储
                    $flag = file_put_contents($this->getStorePath($catId), json_encode($data, JSON_UNESCAPED_UNICODE));
                    break;
                case 'table':   //Swoole Table
                    $flag = Cache::getInstance()->set($this->getCatIdKey($catId), $data);
                    break;
                case 'redis':
                    $redisDb = Di::getInstance()->get('REDIS');
                    $flag = $redisDb->set($this->getCatIdKey($catId), $data);
                    break;
                default:
                    throw new \Exception('存储引擎错误');
                    break;
            }

            if(!$flag)
            {
                //TODO:报警：短信、邮件
            }
        }
    }


    public function getCacheList($catId=0)
    {
        $cacheType = \Yaconf::get('base.cacheType');
        switch ($cacheType){
            case 'file':
                $videoData = is_file($this->getStorePath($catId)) ? file_get_contents($this->getStorePath($catId)):[];
                $videoData = empty($videoData) ? []: json_decode($videoData, true);
                break;
            case 'table':
                $videoData = Cache::getInstance()->get($this->getCatIdKey($catId));
                $videoData = !$videoData ? [] : $videoData;
                break;
            case 'redis':
                $redisDb = Di::getInstance()->get('REDIS');
                $key = $this->getCatIdKey($catId);
                $videoData = $redisDb->get($key);
                $videoData = empty($videoData) ? [] : json_decode($videoData, true);
                break;
            default:
                throw new \Exception('存储引擎错误');
                break;
        }
        return $videoData;
    }

    /**
     * 获取json文件路径
     * @param int $catId
     * @return string
     */
    public function getStorePath($catId = 0)
    {
        $filePath =  EASYSWOOLE_ROOT.'/webroot/json';
        if(!is_dir($filePath))
        {
            mkdir($filePath, 0777, true);
        }
        return $filePath . '/'.$catId.'.json';
    }

    /**
     * 获取存储key
     * @param int $catId
     * @return string
     */
    public function getCatIdKey($catId = 0)
    {
        return 'index_video_cat_id_'.$catId;
    }
}