<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;


use App\Exception\ExceptionHandler;
use App\Lib\Redis\Redis;
use App\Process\Consumer;
use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\SysConst;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use App\Lib\ES\ElasticSearch;
use EasySwoole\Core\Utility\File;
use EasySwoole\Core\Swoole\Time\Timer;
use  App\Lib\ApiCache\Video as VideoCache;
use EasySwoole\Core\Component\Crontab\CronTab;
use EasySwoole\Core\Swoole\Process\ProcessManager;


Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');

        //载入Conf文件夹中所有的配置文件
//        EASYSWOOLE_ROOT = /Users/yuliang/EasySwoole
        self::loadConf(EASYSWOOLE_ROOT . '/Config');

        // 注册异常处理类
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER, [ExceptionHandler::class, 'handle']);
    }


    public static function loadConf($ConfPath)
    {
        $Conf = Config::getInstance();
        $files = File::scanDir($ConfPath);
        foreach ($files as $file)
        {
            $data = require_once $file;
            $Conf->setConf(strtolower(basename($file, '.php')), (array)$data);
        }
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

        Di::getInstance()->set('REDIS', Redis::getInstance());

        //注入elasticsearch
        Di::getInstance()->set('ES', ElasticSearch::getInstance());

        //注册消费者进程
        $allNum = 3;
//        for($i=0; $i<$allNum; $i++)
//        {
//            //执行顺序：在EasySwooleEvent的mainServerCreate中注册， Consumer::class继承AbstractProcess，会去执行run方法
//            ProcessManager::getInstance()->addProcess("consumer_{$i}", Consumer::class);
//        }

        //Crontab定时器
//        $cacheObj = new VideoCache();
//        CronTab::getInstance()->addRule('crobtab_wudy_test1', '*/1 * * * *', function () use ($cacheObj) {
//            $cacheObj->setIndexVideo();
//        });
//            ->addRule('crobtab_wudy_test2', '*/1 * * * *', function (){
//            var_dump('crobtab_wudy_test2');
//        })


        //Swoole定时器
//        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workId) use ($cacheObj) {
//            if($workId == 0)  //work进程由多个，只使用workId=0的进程
//            {
//                Timer::loop(1000*6, function () use ($cacheObj){
//                    $cacheObj->setIndexVideo();
//                });
//            }
//        });
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