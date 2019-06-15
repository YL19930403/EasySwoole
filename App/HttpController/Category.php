<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/25
 * Time: 上午10:17
 */

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;

class Category extends Controller
{

    //http://wudy.easyswoole.cn:8090/category
    public function index()
    {
//        $this->response()->write('category hello');
        $data = [
            'name' => 'wudy',
            'age' => 26,
        ];
        return $this->writeJson(200, 'ok', $data);

    }
}