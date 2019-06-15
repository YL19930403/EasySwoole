<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/5/27
 * Time: 下午5:10
 */

namespace App\Process;

use Swoole\Process;
use App\Lib\Redis\Redis;
use  App\Lib\AliyunSDK\Sms;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Swoole\Process\AbstractProcess;

class Consumer extends AbstractProcess
{
    private $isRun = false;

    public function run(Process $process)
    {
        $this->addTick(5000, function (){
            if(!$this->isRun)
            {
//                var_dump($this->getProcessName().' task run check');
                $this->isRun = true;
                while (true)
                {
                    try{
                        $task = Redis::getInstance()->lPop('redis_test');
                        if($task)
                        {
                            //发送邮件、或者短信、或者写日志
                            Logger::getInstance()->log($this->getProcessName() . "---" . $task);

                            $authCodeMT = mt_rand(100000,999999);
                            $result = Sms::sendSms($task, $authCodeMT);
                            if($result['Code'] != 'OK')
                            {
                                Logger::getInstance()->log('验证码发送失败'. json_encode($result, JSON_UNESCAPED_UNICODE));
                            }
                        }else{
                            break;
                        }
                    }catch (\Throwable $throwable){
                        break;
                    }
                }
                $this->isRun = false;
            }

        });
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str, ...$args)
    {
        // TODO: Implement onReceive() method.
    }
}