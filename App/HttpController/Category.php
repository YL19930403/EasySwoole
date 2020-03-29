<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/25
 * Time: 上午10:17
 */

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Message\Status;

class Category extends Controller
{

    //http://wudy.easyswoole.cn:9501/category
    public function index()
    {
//        $this->response()->write('category hello');
        $data = [
            'name' => 'wudy',
            'age' => 26,
        ];
        return $this->writeJson(Status::CODE_OK, 'ok', $data);

    }
}