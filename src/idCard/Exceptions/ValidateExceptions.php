<?php
/**
 * Notes:
 * File name:Validate
 * Create by: Jay.Li
 * Created on: 2021/2/18 0018 16:35
 */

namespace JayAddress\Exceptions;

use Throwable;

class ValidateExceptions extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf("当前公民身份证号码验证出错：%s", $message);
        parent::__construct($message, $code, $previous);
    }
}