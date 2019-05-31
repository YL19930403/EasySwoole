<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/29
 * Time: 下午5:45
 */

namespace App\Model;
use EasySwoole\Core\Component\Di;

/**
 * Class Base
 * @package App\Model
 * @property string tableName
 */
class Base
{
    private $db = '';

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __construct()
    {
        if(empty($this->tableName))
        {
            throw new \Exception('table error');
        }
        $db = Di::getInstance()->get('MYSQL');
        if($db instanceof \MysqliDb)
        {
            $this->db = $db;
        }else{
            throw new \Exception('db error');
        }
    }

    public function add($data)
    {
        if(empty($data) || !is_array($data))
        {
            return false;
        }
        return $this->db->insert($this->tableName, $data);
    }
}