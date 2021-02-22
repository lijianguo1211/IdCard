<?php
/**
 * Notes:
 * File name:ErrorMessage
 * Create by: Jay.Li
 * Created on: 2021/2/19 0019 12:13
 */


namespace JayAddress\Exceptions;


class ErrorMessage
{
    const JSON_MESSAGE = 'json';
    const ARRAY_MESSAGE = 'array';
    const TEXT_MESSAGE = 'text';

    const MESSAGE_ARRAY = [
        self::JSON_MESSAGE => 1,
        self::ARRAY_MESSAGE => 2,
        self::TEXT_MESSAGE => 3,
    ];
}