<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/23
 * Time: 下午2:37
 */

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->response()->write('hello world');
    }
}