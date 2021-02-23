<?php
/**
 * Notes:
 * File name:${fILE_NAME}
 * Create by: Jay.Li
 * Created on: 2021/2/18 0018 10:08
 */

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use JayAddress\Card;
use JayAddress\IdentityCard;

//$obj = new IdentityCard();

$idCard = '140211199512011726';



//$obj->setDebug(true);
//var_dump(Card::getLength($idCard));
//die();

for ($i = 0; $i < 10; $i++) {
    try {
        //$idCard = $obj->createCard();
        $idCard = Card::createCard();
//    $dataIdCard = $obj->multipleCreateCard(2000);
//
//    print_r($dataIdCard);
//    echo PHP_EOL;exit;

        $obj = IdentityCard::make($idCard);

        $obj->setDebug(true);
        print_r($idCard);
        echo PHP_EOL;
//        $data = $obj->getInfo($idCard);
        $data = $obj->getInfo();
        print_r($data);
        echo PHP_EOL;
//        $sex = $obj->getSex($idCard);
        $sex = $obj->getSex();
        print_r($sex);
        echo PHP_EOL;
        $age = $obj->getAge();
//        $age = $obj->getAge($idCard);
        print_r($age);
        $birth = $obj->getBirthDate();
//        $birth = $obj->getBirthDate($idCard, false);
        echo PHP_EOL;
        print_r($birth);
        echo PHP_EOL;
        $birthday = $obj->getBirthday();
//        $birthday = $obj->getBirthday($idCard, false);
        print_r($birthday);
        echo PHP_EOL;
        $zodiac = $obj->getZodiac();
//        $zodiac = $obj->getZodiac($idCard);
        print_r($zodiac);
        echo PHP_EOL;
        $starSigns = $obj->getStarSigns();
//        $starSigns = $obj->getStarSigns($idCard);
//        print_r($starSigns);
//        $obj->validateIdCard($idCard);
        echo PHP_EOL;
        $address = $obj->getAddress();
//        $address = $obj->getAddress($idCard);
        print_r($address);
        echo PHP_EOL;
    } catch (\JayAddress\Exceptions\ValidateExceptions $e) {
        var_dump($e->getMessage());
    }
}





//$starSigns = function (int $month, int $day) {
//    switch ($month) {
//        case 1:
//            if ($day < 20) {
//                $res = '摩羯座';
//            } else {
//                $res = '水瓶座';//1.20 - 2.18
//            }
//            break;
//        case 2:
//            if ($day < 19) {
//                $res = '水瓶座';
//            } else {
//                $res = '双鱼座';//2.19 - 3.20
//            }
//            break;
//        case 3:
//            if ($day < 21) {
//                $res = '双鱼座';
//            } else {
//                $res = '白羊座';//3.21 - 4.19
//            }
//            break;
//        case 4:
//            if ($day < 20) {
//                $res = '白羊座';
//            } else {
//                $res = '金牛座';//4.20 - 5.20
//            }
//            break;
//        case 5:
//            if ($day < 21) {
//                $res = '金牛座';
//            } else {
//                $res = '双子座';//5.21-6.21
//            }
//            break;
//        case 6:
//            if ($day < 22) {
//                $res = '双子座';
//            } else {
//                $res = '巨蟹座';//6.22 - 7.22
//            }
//            break;
//        case 7:
//            if ($day < 23) {
//                $res = '巨蟹座';
//            } else {
//                $res = '狮子座';//7.23 - 8.22
//            }
//            break;
//        case 8:
//            if ($day < 23) {
//                $res = '狮子座';
//            } else {
//                $res = '处女座';//8.23 - 9.22
//            }
//            break;
//        case 9:
//            if ($day < 23) {
//                $res = '处女座';
//            } else {
//                $res = '天枰座';//9.23 - 10.23
//            }
//            break;
//        case 10:
//            if ($day < 24) {
//                $res = '天枰座';
//            } else {
//                $res = '天蝎座';//10.24 - 11.22
//            }
//            break;
//        case 11:
//            if ($day < 23) {
//                $res = '天蝎座';
//            } else {
//                $res = '射手座';//11.23 - 12.21
//            }
//            break;
//        case 12:
//            if ($day < 22) {
//                $res = '射手座';
//            } else {
//                $res = '摩羯座';//12.22 - 1.19
//            }
//            break;
//        default:
//            $res = '未知';
//            break;
//    }
//
//    return $res;
//};
//
//
//var_dump($starSigns(12, 12));
