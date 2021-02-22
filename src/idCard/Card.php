<?php
/**
 * Notes:
 * File name:Card
 * Create by: Jay.Li
 * Created on: 2021/2/22 0022 14:17
 */


namespace JayAddress;

/**
 * @method static validateIdCard(?string $idCard = null)
 * @method static createCard(int $number = 18, ?int $sex = null)
 * @method static multipleCreateCard(int $count = 5, int $number = 18, ?int $sex = null)
 * @method static getInfo(?string $idCard = null)
 * @method static getLength(?string $idCard = null)
 * @method static getAddress(?string $idCard = null, bool $select = true)
 * @method static getBirthDate(?string $idCard = null, bool $type = true)
 * @method static getBirthday(?string $idCard = null, bool $type = true)
 * @method static getAge(?string $idCard = null)
 * @method static getSex(?string $idCard = null)
 * @method static getZodiac(?string $idCard = null)
 * @method static getStarSigns(?string $idCard = null)
 *
 * @see \JayAddress\IdentityCard
 */
class Card
{
    public static function __callStatic($method, $args)
    {
        $instance = IdentityCard::make();

        return $instance->$method(...$args);
    }
}