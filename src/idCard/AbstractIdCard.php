<?php
/**
 * Notes:
 * File name:AbstractIdCard
 * Create by: Jay.Li
 * Created on: 2021/2/22 0022 12:06
 */


namespace JayAddress;


use ErrorException;
use JayAddress\Exceptions\ErrorMessage;
use JayAddress\Exceptions\FatalThrowableError;

abstract class AbstractIdCard
{
    /**
     * 调试
     * @var bool
     */
    protected $debug = false;

    /**
     * 输出错误格式
     * @var string
     */
    protected $errorMessage = 'array';

    /**
     * 输入号
     * @var string|null
     */
    public $idCard;

    /**
     * 长度
     * @var int
     */
    public $len = 0;

    /**
     * 计数器
     * @var int
     */
    protected static $pointer = 0;

    /**
     * 出生日期
     * @var
     */
    protected $time;

    /**
     * 地区编码
     * @var
     */
    protected $code;

    /**
     * 出生年
     * @var
     */
    protected $year;

    protected $cardData;

    const FIRST_YEAR = 1980;

    const ARGS = [0 => 1, 1 => 0, 2 => "X", 3 => 9, 4 => 8, 5 => 7, 6 => 6, 7 => 5, 8 => 4, 9 => 3, 10 => 2];

    const POSITION = [1 => 1, 2 => 2, 3 => 4, 4 => 8, 5 => 5, 6 => 10, 7 => 9, 8 => 7, 9 => 3, 10 => 6, 11 => 1, 12 => 2, 13 => 4, 14 => 8, 15 => 5, 16 => 10, 17 => 9, 18 => 7];

    const ZODIAC = [4 => '子鼠', 5 => '丑牛', 6 => '寅虎', 7 => '卯兔', 8 => '辰龙', 9 => '巳蛇', 10 => '午马', 11 => '未羊', 0 => '申猴', 1 => '酉鸡', 2 => '戌狗', 3 => '亥猪'];

    const MUNICIPALITY = [110000 => 110000, 120000 => 120000, 310000 => 310000, 500000 => 500000];

    public function __construct(?string $idCard = null)
    {
        $this->idCard = $idCard;

        self::$pointer = 0;

        $this->register();

        $this->initConfig((int)date('Y'));
    }

    protected function initConfig(int $year):self
    {
        $path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config/' . $year . DIRECTORY_SEPARATOR . 'config.json';

        if ($year <= self::FIRST_YEAR) {
            $path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config/1980' . DIRECTORY_SEPARATOR . 'config.json';
        }

        if ($year === (int)date('Y')) {
            $year = $year - 1;
            $path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config/' . $year . DIRECTORY_SEPARATOR . 'config.json';
        }


        $this->cardData = json_decode(file_get_contents($path), true);

        return $this;
    }

    protected function register()
    {
        error_reporting(-1);

        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);
    }

    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * @Notes:自定义异常返回
     *
     * @param $e
     * @auther: Jay
     * @Date: 2021/2/19 0019
     * @Time: 9:23
     * @return array|false|string
     */
    public function handleException($e)
    {
        if (! $e instanceof \Exception) {
            $e = new FatalThrowableError($e);
        }

        $result = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'status' => $e->getCode() ?? 5001000
        ];

        if (!isset(ErrorMessage::MESSAGE_ARRAY[$this->errorMessage])) {
            exit(0);
        }

        if ($this->debug) {
            var_export($result);
        }

        switch ($this->errorMessage) {
            case ErrorMessage::JSON_MESSAGE:
                return json_encode($result);
                break;
            case ErrorMessage::ARRAY_MESSAGE:
                return $result;
                break;
            case ErrorMessage::TEXT_MESSAGE:
                return implode(' --- ', $result);
                break;
        }
        exit(1);
    }

    /**
     * @Notes:开启debug调试显示错误信息
     *
     * @param bool $debug
     * @return $this
     * @auther: Jay
     * @Date: 2021/2/19 0019
     * @Time: 9:26
     */
    public function setDebug(bool $debug):self
    {
        $this->debug = $debug;

        return $this;
    }

    public static function make(?string $idCard = null)
    {
        return new static($idCard);
    }
}