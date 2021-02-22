<?php
/**
 * Notes:
 * File name:CreateIdCard
 * Create by: Jay.Li
 * Created on: 2021/2/22 0022 11:59
 */

namespace JayAddress\Traits;


trait CreateIdCard
{
    /**
     * @Notes: 生成一个
     *
     * @param int $number
     * @param int|null $sex
     * @return string
     * @auther: Jay
     * @Date: 2021/2/22 0022
     * @Time: 12:01
     */
    public function createCard(int $number = 18, ?int $sex = null)
    {
        $address = function ($year) {
            $data = $this->initConfig($year);

            if (json_last_error()) {
                $resData = include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config/config.php';

                $key = array_rand($resData);
                $resData = $resData[$key];

                return $resData[array_rand($resData)];
            }

            return array_rand($data);
        };

        $year = function ($number) {
            $start = 1900;
            $end = (int)date('Y');

            $year = mt_rand($start, $end);
            if ($number == 15) {
                return substr($year, 2, 2);
            }

            return $year;
        };

        $month = function () {
            $month = mt_rand(1, 12);

            if ($month < 10) {
                return 0 . $month;
            }

            return $month;
        };

        $day = function ($year, $month) {
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
                    if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0)
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

            $day = mt_rand(1, $dayCount);
            if ($day < 10) {
                $day = 0 . $day;
            }

            return $day;
        };

        $code = function ($sex) {
            $str = "0123456789";
            $len = 2;
            if ($sex === null) {
                $len = 3;
            }
            $code = '';
            while (strlen($code) < $len) {
                $str = str_shuffle($str);
                $i = mt_rand(0, 9);
                $code .= $str[$i];
            }

            return $sex === null ? $code : $code . $sex;
        };

        $args = function ($idCard) {
            $num = 0;
            for ($i = 0, $j = 18; $i < 17, $j > 1; $i++, $j--) {
                $num += self::POSITION[$j] * $idCard[$i];
            }

            $mod = $num % 11;

            return self::ARGS[$mod];
        };

        $year = $year($number);
        $address = $address($year);

        $month = $month();
        $day = $day($year, (int)$month);
        $code = $code($sex);
        $idCard = $address . $year . $month . $day . $code;
        if ($number === 18) {
            $args = $args($idCard);

            $idCard = $idCard . $args;
        }

        return $idCard;
    }


    /**
     * @Notes: 生成多个
     *
     * @param int $count
     * @param int $number
     * @param int|null $sex
     * @return array
     * @auther: Jay
     * @Date: 2021/2/22 0022
     * @Time: 12:01
     */
    public function multipleCreateCard(int $count = 5, int $number = 18, ?int $sex = null)
    {
        $result = [];
        $idCard = $this->createCard($number, $sex);

        while (!isset($result[$idCard]) && $count > 0) {
            $result[$idCard] = $count;
            $idCard = $this->createCard($number, $sex);
            $count--;
        }

        return $result;
    }
}