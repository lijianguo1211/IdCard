<?php
/**
 * Notes:
 * File name:InterfaceIdCard
 * Create by: Jay.Li
 * Created on: 2021/2/22 0022 12:08
 */


namespace JayAddress;

interface InterfaceIdCard
{
    public function getInfo(?string $idCard = null):array;

    public function getLength(?string $idCard = null):int;

    /**
     * @Notes:
     *
     * @param string|null $idCard
     * @param bool $type
     * @return string|null|array
     * @auther: Jay
     * @Date: 2021/2/23 0023
     * @Time: 18:10
     */
    public function getAddress(?string $idCard = null, bool $type = true);

    public function getBirthDate(?string $idCard = null, bool $type = true):string;

    public function getBirthday(?string $idCard = null, bool $type = true):string;

    public function getAge(?string $idCard = null):string;

    public function getSex(?string $idCard = null):string;

    public function getZodiac(?string $idCard = null):string;

    public function getStarSigns(?string $idCard = null):string;
}