<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2020/4/18
 * Time: 上午12:08
 */

namespace App\Exception;

use EasySwoole\Core\Http\Message\Status;
use Throwable;

class ApiException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = Status::RET_SUCCESS, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}