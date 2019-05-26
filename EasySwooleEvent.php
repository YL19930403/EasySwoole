<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Component\Di;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    /**
     * @param ServerManager $server
     * @param EventRegister $register
     * 主服务创建事件
     * 在执行该事件的时候，已经完成的工作有:
     * 1.框架初始化事件
     * 2.配置文件加载完成
     * 3.主Swoole Server创建成功
     * 4.主Swoole Server 注册了默认的onRequest,onTask,onFinish事件
     */
    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        //mysql相关
        //Di 依赖注入
        Di::getInstance()->set('MYSQL', \MysqliDb::class, Array(
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => '000000',
            'db' => 'blog',
            'port' => 3306,
            'charset' => 'utf8',
        ));
    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}