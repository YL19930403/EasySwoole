<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/3/23
 * Time: 上午10:01
 */

namespace App\Model\Entity;

/**
 * Class VideoInfo
 * @package App\Model\Entity
 * @property int cat_id 视频分类id
 * @property int type 视频类型
 * @property int status 状态
 * @property int video_id 视频id
 * @property string name 视频名称
 * @property string content 视频内容描述
 * @property string image 视频图片地址
 * @property string url 视频url
 * @property string school_name 学校名
 */
class VideoInfo extends Entity{
    /**
     * 过滤参数，转变数据类型
     * @var array
     */
    private $_rule = [
        [
            [
                'cat_id', 'type', 'status', 'video_id'
            ],
            'intval'
        ],

        [
            [
                'name', 'content', 'image', 'url', 'school_name'
            ],
            'strval'
        ],

    ];

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->$name = $this->toType($name,$value);
    }

    /**
     * 转变类型，不存在rule中的，全部都是string类型
     * @param $name
     * @param $value
     * @return string
     */
    private function toType($name,$value) {
        foreach ($this->_rule as $key => $val) {
            if (in_array($name,$val[0])) {
                return $val[1]($value);
            }
        }

        return !is_array($value) ? strval($value) : $value;
    }

}