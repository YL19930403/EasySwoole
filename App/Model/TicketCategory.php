<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/3/22
 * Time: 下午6:27
 */

namespace App\Model;

/**
 * Class TicketCategory
 * @package App\Model
 * @property int page_no
 * @property int page_size
 */
class TicketCategory extends Base
{
    protected const PAGE_NO = 1;
    protected const PAGE_SIZE = 10;

    private $fields = 'id, pid, cat_level, cat_name, status, cat_type, create_time';

    public function __construct()
    {
        $this->tableName = 't_ticket_category';
        parent::__construct();
    }

    public  function getCategoryList(array $condition = [], $page_no = self::PAGE_NO, $page_size= self::PAGE_SIZE){
        $this->db->pageLimit = $page_size;

        $this->db->where('is_delete', 0);
        if (!empty($condition['cat_level'])){
            $this->db->where('cat_level', intval($condition['cat_level']));
        }

        if (!empty($condition['cat_name'])){
            $this->db->where('cat_name', strval($condition['cat_name']), 'like');
        }
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