<?php
/**
 * Notes:
 * File name:ValidateIdCard
 * Create by: Jay.Li
 * Created on: 2021/2/22 0022 12:02
 */


namespace JayAddress\Traits;


use JayAddress\Exceptions\ValidateExceptions;

trait ValidateIdCard
{
    /**
     * @Notes:验证
     *
     * @param string|null $idCard
     * @return bool
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/18 0018
     * @Time: 17:42
     */
    public function validateIdCard(?string $idCard = null)
    {
        if (self::$pointer) {
            return false;
        }

        if ($idCard === null) {
            $idCard = $this->idCard;
        }

        if (empty($idCard)) {
            throw new ValidateExceptions('当前传入的身份证数据为空', 4001001);
        }

        $len = $this->getLength($idCard);
        $lenArr = [15 => true, 18 => true];

        if (!isset($lenArr[$len])) {
            throw new ValidateExceptions('当前验证的号码段长度不符合要求', 4001002);
        }

        $this->code = (int)substr($idCard, 0, 6);

        if ($this->len === 18) {
            $validate = function (string $idCard, bool $type) {
                $this->identityCard18($idCard, $type);
            };
        } else {
            $validate = function (string $idCard, bool $type) {
                if (!$type) {
                    $this->identityCard15($idCard);
                }
            };
        }
        $validate($idCard, false);

        if ($this->code < 110000 || $this->code > 830000) {
            //编码地址不符合规定
            throw new ValidateExceptions(sprintf("编码地址不符合规定, 当前的地址编码是：%d", $this->code), 4001003);
        }

        if ((int)$this->year > (int)date('Y')) {
            //出生年不符合规定
            throw new ValidateExceptions(sprintf("出生年不符合规定, 当前输入的年份是：%d", $this->year), 4001004);
        }

        $month = (int)substr($this->time, 4, 2);
        if ($month < 1 || $month > 12) {
            //出生月数不符合规定
            throw new ValidateExceptions(sprintf("出生月数不符合规定, 当前输入的月份是：%d", $month), 4001005);
        }

        $day = (int)substr($this->time, 6, 2);

        if (!$this->checkDay($month, $day)) {
            //出生月的天数不符合规定
            throw new ValidateExceptions(sprintf("出生月的天数不符合规定, 当前输入的月份天数是：%d", $day), 4001006);
        }

        $validate($idCard, true);

        self::$pointer++;

        return true;
    }

    /**
     * @Notes:15位的处理
     *
     * @param string $idCard
     * @auther: Jay
     * @Date: 2021/2/19 0019
     * @Time: 17:57
     */
    protected function identityCard15(string $idCard)
    {
        $this->time = '19' . substr($idCard, 6, 6);
        //15位的固定加19
        $this->year = '19' . substr($idCard, 6, 2);
    }

    /**
     * @Notes:验证18位的身份证是否符合要求
     *
     * @param string $idCard
     * @param bool $type
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/18 0018
     * @Time: 17:34
     */
    protected function identityCard18(string $idCard, bool $type = true)
    {
        if ($type) {
            $num = 0;
            for ($i = 0, $j = $this->len; $i < $this->len - 1, $j > 1; $i++, $j--) {
                $num += self::POSITION[$j] * $idCard[$i];
            }

            $mod = $num % 11;

            if (!isset(self::ARGS[$mod])) {
                throw new ValidateExceptions(sprintf("身份证校验码戳错，不在GB11643-1999规定范围内, 当前的取余为 %d", $mod), 4001007);
            }

            $args = self::ARGS[$mod];

            $argsTemp = strtoupper(substr($idCard, -1));
            $res = $argsTemp === 'X' ? $argsTemp : (int)$argsTemp;
            if ($args !== $res) {
                throw new ValidateExceptions(sprintf("身份证校验码戳错，不在GB11643-1999规定范围内, 当前的校验码应该是 %d, 但当前身份证的校验码是：%s", $args, $res), 4001008);
            }

        } else {
            $this->time = substr($idCard, 6, 8);

            $this->year = substr($idCard, 6, 4);
        }
    }

    /**
     * @Notes:验证每个月天数合法性
     *
     * @param int $month
     * @param int $day
     * @return bool
     * @auther: Jay
     * @Date: 2021/2/19 0019
     * @Time: 17:17
     */
    protected function checkDay(int $month, int $day):bool
    {
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                $dayCount = 31;
                break;
            case 2:
                if (($this->year % 4 == 0 && $this->year % 100 != 0) || $this->year % 400 == 0)
                {
                    $dayCount = 29;
                }
                else
                {
                    $dayCount = 28;
                }
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                $dayCount = 30;
                break;
            default:
                $dayCount = 0;
                break;

        }

        if ($day >= 1 && $day <= $dayCount) {
            return true;
        }

        return false;
    }
}