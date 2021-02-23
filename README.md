### 这是一个关于身份证号验证的处理包

* 主要的功能包括

1. 身份证号是否合法

2. 解析身份证号得到有用的信息

3. 生成一个符合规定的身份证（jia）便于测试使用

* 数据验证和生成测试数据是根据 **[GB11643-1999]**中华人民共和国国家标准中文档规定（可以直接搜索这个规定里面有详细的介绍）

* config目录中的文件是根据当前国家统计与2020年11月份统计的数据做的处理，按年份做的处理

* doc目录中的文件是一份2020年11月的csv国家行政编号数据，以及存入的一份SQLITE3的数据库文件，具体可以自己再做处理使用

#### 使用方式

```php
use JayAddress\Card;
use JayAddress\IdentityCard;
```

1. 静态方式调用,所有的`public`方法都可以静态调用

```php
Card::createCard();
```

2. 通过创建一个对象的方式调用

```php
$obj = new IdentityCard();
//Or
$obj = IdentityCard::make();
```

* 具体示例

```php
use JayAddress\Card;
use JayAddress\IdentityCard;

$obj = new IdentityCard();
//Or
$obj = IdentityCard::make();

for ($i = 0; $i < 10; $i++) {
    try {
        //生成一个测试身份证（jia）号
        $idCard = $obj->createCard();
        $idCard = Card::createCard();
        //生成多个个测试身份证（jia）号
        $dataIdCard = $obj->multipleCreateCard(2000);

        print_r($dataIdCard);
        echo PHP_EOL;
        print_r($idCard);
        echo PHP_EOL;
        //解析出详细的信息，返回一个关联数组
        $data = $obj->getInfo($idCard);
        print_r($data);
        echo PHP_EOL;
        //性别
        $sex = $obj->getSex($idCard);
        print_r($sex);
        echo PHP_EOL;
        //年龄
        $age = $obj->getAge($idCard);
        print_r($age);
        //出生年月日
        $birth = $obj->getBirthDate($idCard, false);
        echo PHP_EOL;
        print_r($birth);
        echo PHP_EOL;
        //生日
        $birthday = $obj->getBirthday($idCard, false);
        print_r($birthday);
        echo PHP_EOL;
        //属相
        $zodiac = $obj->getZodiac($idCard);
        print_r($zodiac);
        echo PHP_EOL;
        //星座
        $starSigns = $obj->getStarSigns($idCard);
        print_r($starSigns);
        //验证一个证件号码
        $obj->validateIdCard($idCard);
        echo PHP_EOL;
        //得到地址信息
        $address = $obj->getAddress($idCard);
        print_r($address);
        echo PHP_EOL;
    } catch (\JayAddress\Exceptions\ValidateExceptions $e) {
        var_dump($e->getMessage());
    }
}

//打印信息如下：

//422424194002298236
//Array
//(
//    [status] => 200
//    [message] => success
//[data] => Array
//(
//    [address] => 甘肃省武都地区康县
//    [birthDate] => 1926年12月18日
//[birthday] => 12月18日
//[zodiac] => 寅虎
//[starSigns] => 射手座
//[age] => 95
//            [sex] => 女
//        )
//
//)
//
//女
//95
//19261218
//1218
//寅虎
//射手座
//甘肃省武都地区康县

```

#### 最后需要注意，如果是自己调试使用，可以调用`setDebug()`方法开启错误日志输出，或者是把错误写入日志文件。所有的方法调用最好全部放入`try catch`
当中，错误在`ValidateExceptions`当中都有捕获，特别是`validateIdCard()`方法，不需要去判断返回值，没有异常发生，就代表没有问题，有异常就代表验证
不通过。
