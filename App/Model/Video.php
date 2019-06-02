<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/26
 * Time: 上午8:53
 */

namespace App\Model;

use EasySwoole\Mysqli\TpORM;
use EasySwoole\Config;


class Video  extends Base //extends TpORM
{

    private $fields = 'id, name, cat_id, image, url, type, content, uploader, create_time, update_time, status, video_id, video_duration';

    public function __construct()
    {
        $this->tableName = 'video';
        parent::__construct();
    }

    public function getVideoBy($id)
    {
        return $this->where(['id' => 1])->field('*')->find();
    }

    /**
     * 通过条件获取video数据
     * @param int $page_no
     * @param int $page_size
     * @param array $condition
     * @return array
     * @throws \Exception
     */
    public function getVideoList($page_no = 1, $page_size=10, array $condition = [])
    {
        if(!empty($page_size))
        {
            $this->db->pageLimit = $page_size;
        }

        if(!empty($condition['cat_id']))
        {
            $this->db->where('cat_id', $condition['cat_id']);
        }
        $this->db->where('status', \Yaconf::get("status.normal"));
        $this->db->orderBy('id', 'desc');
        $result = $this->db->paginate($this->tableName, $page_no, $this->fields);
//        echo $this->db->getLastQuery();
        $data = [
            'total_page' => (int)$this->db->totalPages,
            'page_size' => (int)$page_size,
            'count' => (int)$this->db->totalCount,
            'lists' => $result,
        ];
        return $data;
    }


}