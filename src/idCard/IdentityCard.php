<?php
/**
 * Notes:
 * File name:identity card
 * Create by: Jay.Li
 * Created on: 2021/2/17 0017 17:58
 */

namespace JayAddress;

use JayAddress\Exceptions\ValidateExceptions;
use JayAddress\Traits\CreateIdCard;
use JayAddress\Traits\ValidateIdCard;

class IdentityCard extends AbstractIdCard implements InterfaceIdCard
{
    use CreateIdCard, ValidateIdCard;

    /**
     * @Notes: 返回长度
     *
     * @param string|null $idCard
     * @return int
     * @auther: Jay
     * @Date: 2021/2/17 0017
     * @Time: 18:01
     */
    public function getLength(?string $idCard = null):int
    {
        return $this->len = (int)strlen($idCard);
    }

    /**
     * @Notes: 得到地址信息
     *
     * @param string|null $idCard
     * @param bool $type//true 返回字符串，false 返回数组
     * @return string|array|null
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/19 0019
     * @Time: 16:55
     */
    public function getAddress(?string $idCard = null, bool $type = true)
    {
        $this->validateIdCard($idCard);

        $province = substr($this->code, 0, 2) . '0000';
        $city = substr($this->code, 0, 4) . '00';
        $area = $this->code;

        $nowYear = (int)date("Y") - 1;

        $this->initConfig($nowYear);

        $diffBasePath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'diff' . DIRECTORY_SEPARATOR;

        $isNow = true;
        $provinceStr = $cityStr = $areaStr = null;
        while ($nowYear > self::FIRST_YEAR) {
            try {
                if ($isNow) {
                    $provinceStr = $this->cardData[$province]['code_name'];
                    if (!isset(self::MUNICIPALITY[$province])) {
                        $cityStr = $this->cardData[$city]['code_name'];
                    }
                    $areaStr = $this->cardData[$area]['code_name'];
                    break;
                }
                throw new \Exception("循环找数据");
            } catch (\Exception $e) {
                $tmpData = json_decode(file_get_contents($diffBasePath . $nowYear . '.json'), true);
                $isNow = false;
                $nowYear--;
                if (isset($tmpData['delete'][$this->code])) {
                    $isNow = true;
                    $this->initConfig($nowYear);
                }
            }
        }
        $address = '';

        if ($provinceStr) {
            $address .= $provinceStr;
        }

        if ($cityStr) {
            $address .= $cityStr;
        }

        if ($areaStr) {
            $address .= $areaStr;
        }

        if ($type) {
            return $address;
        }

        return [
            'province' => $provinceStr,
            'city' => $cityStr,
            'area' => $areaStr,
        ];
    }

    /**
     * @Notes:得到性别
     *
     * @param string|null $idCard
     * @return string
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/18 0018
     * @Time: 9:33
     */
    public function getSex(?string $idCard = null):string
    {
        $this->validateIdCard($idCard);

        $start = -1;

        if ($this->len === 18) {
            $start = -2;
        }

        return (int)substr($idCard, $start) % 2 === 0 ? '女' : '男';
    }

    /**
     * @Notes:得到年龄
     *
     * @param string|null $idCard
     * @return string
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/18 0018
     * @Time: 10:23
     */
    public function getAge(?string $idCard = null):string
    {
        $this->validateIdCard($idCard);

        $nowYear = date('Y');

        return bcsub($nowYear, $this->year, 0);
    }

    /**
     * @Notes:出生日期
     *
     * @param string|null $idCard
     * @param bool $type
     * @return string
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/18 0018
     * @Time: 10:53
     */
    public function getBirthDate(?string $idCard = null, bool $type = true):string
    {
        $this->validateIdCard($idCard);

        if ($type) {
            return sprintf('%d年%d月%d日', $this->year, substr($this->time, 4, 2), substr($this->time, 6, 2));
        }

        return $this->time;
    }

    /**
     * @Notes:生日
     *
     * @param string|null $idCard
     * @param bool $type
     * @return false|string
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/18 0018
     * @Time: 11:00
     */
    public function getBirthday(?string $idCard = null, bool $type = true):string
    {
        $this->validateIdCard($idCard);

        if ($type) {
            return sprintf("%d月%d日", substr($this->time, 4, 2), substr($this->time, 6, 2));
        }

        return substr($this->time, 4, 4);
    }

    /**
     * @Notes:得到属相
     *
     * @param string|null $idCard
     * @return string
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/18 0018
     * @Time: 11:54
     */
    public function getZodiac(?string $idCard = null):string
    {
        $this->validateIdCard($idCard);

        $mod = $this->year % 12;

        return self::ZODIAC[(int)$mod];
    }

    /**
     * @Notes:得到星座
     *
     * @param string|null $idCard
     * @return string
     * @throws ValidateExceptions
     * @auther: Jay
     * @Date: 2021/2/18 0018
     * @Time: 15:19
     */
    public function getStarSigns(?string $idCard = null):string
    {
        $this->validateIdCard($idCard);

        $month = (int)substr($this->time, 4, 2);
        $day = (int)substr($this->time, 6, 2);

        $starSigns = function (int $month, int $day) {
            switch ($month) {
                case 1:
                    if ($day < 20) {
                        $res = '摩羯座';
                    } else {
                        $res = '水瓶座';//1.20 - 2.18
                    }
                    break;
                case 2:
                    if ($day < 19) {
                        $res = '水瓶座';
                    } else {
                        $res = '双鱼座';//2.19 - 3.20
                    }
                    break;
                case 3:
                    if ($day < 21) {
                        $res = '双鱼座';
                    } else {
                        $res = '白羊座';//3.21 - 4.19
                    }
                    break;
                case 4:
                    if ($day < 20) {
                        $res = '白羊座';
                    } else {
                        $res = '金牛座';//4.20 - 5.20
                    }
                    break;
                case 5:
                    if ($day < 21) {
                        $res = '金牛座';
                    } else {
                        $res = '双子座';//5.21-6.21
                    }
                    break;
                case 6:
                    if ($day < 22) {
                        $res = '双子座';
                    } else {
                        $res = '巨蟹座';//6.22 - 7.22
                    }
                    break;
                case 7:
                    if ($day < 23) {
                        $res = '巨蟹座';
                    } else {
                        $res = '狮子座';//7.23 - 8.22
                    }
                    break;
                case 8:
                    if ($day < 23) {
                        $res = '狮子座';
                    } else {
                        $res = '处女座';//8.23 - 9.22
                    }
                    break;
                case 9:
                    if ($day < 23) {
                        $res = '处女座';
                    } else {
                        $res = '天枰座';//9.23 - 10.23
                    }
                    break;
                case 10:
                    if ($day < 24) {
                        $res = '天枰座';
                    } else {
                        $res = '天蝎座';//10.24 - 11.22
                    }
                    break;
                case 11:
                    if ($day < 23) {
                        $res = '天蝎座';
                    } else {
                        $res = '射手座';//11.23 - 12.21
                    }
                    break;
                case 12:
                    if ($day < 22) {
                        $res = '射手座';
                    } else {
                        $res = '摩羯座';//12.22 - 1.19
                    }
                    break;
                default:
                    $res = '未知';
                    break;
            }

            return $res;
        };

        return $starSigns($month, $day);
    }

    /**
     * @Notes:得到全部信息
     *
     * @param string|null $idCard
     * @return array
     * @auther: Jay
     * @Date: 2021/2/22 0022
     * @Time: 12:20
     */
    public function getInfo(?string $idCard = null): array
    {
        // TODO: Implement getInfo() method.
        try {
            $data = [
                'status' => 200,
                'message' => 'success',
                'data' => [
                    'address' => $this->getAddress($idCard),
                    'birthDate' => $this->getBirthDate($idCard),
                    'birthday' => $this->getBirthday($idCard),
                    'zodiac' => $this->getZodiac($idCard),
                    'starSigns' => $this->getStarSigns($idCard),
                    'age' => $this->getAge($idCard),
                    'sex' => $this->getSex($idCard),
                ],
            ];
        } catch (ValidateExceptions $e) {
            $data = [
                'status' => 200,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return $data;
    }
}