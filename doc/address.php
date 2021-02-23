<?php
/**
 * Notes:
 * File name:${fILE_NAME}
 * Create by: Jay.Li
 * Created on: 2021/2/17 0017 16:58
 */

class MyDb
{
    protected static $db = null;

    public static function getInstance(string $fileName)
    {
        if (self::$db === null) {
            self::$db = new SQLite3($fileName);
        }

        return self::$db;
    }
}

$db = MyDb::getInstance('address.db');

//$sql = "CREATE TABLE if not exists address (
//  id int primary key NOT NULL,
//  year char(4) NOT NULL,
//  code char(6) NOT NULL,
//  code_name VARCHAR(50) NOT NULL
//);";
//
//$ret = $db->exec($sql);
//if(!$ret){
//    echo $db->lastErrorMsg();
//} else {
//    echo "Table created successfully\n";
//}
//$db->close();exit();

//$file = __DIR__ . DIRECTORY_SEPARATOR . 'address_code.csv';
//
//$resource = fopen($file, 'r');
//
//$addressFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config/address.php';
//file_put_contents($addressFile, "<?php \n return [ \n");
//if ($resource === false) {
//    exit(sprintf('文件 %s 资源打开失败！', $file));
//}
//$row = 1;
//$data = [];
//while (($data = fgetcsv($resource)) !== false) {
//    $year = (int)$data[0];
//    $code = (int)$data[1];
//    $codeName = trim($data[2], "\ \t\n\r\0\x0B");
//
//    $selectSql = sprintf("select count(id) as num from address where id = %d ", $row);
//
//    if (!$db->query($selectSql)->fetchArray()['num']) {
//            $sql = sprintf("insert into address (id, year, code, code_name) values (%d, '%s', '%s', '%s')", $row, $year, $code, $codeName);
//            $ret = $db->exec($sql);
//            if(!$ret){
//                echo $db->lastErrorMsg() . $row . " -- $code \n";
//            } else {
//                echo "Records created successfully $row -- $code \n";
//            }
//    }
//    $row++;
////    $data = ['year' => (int)$data[0], 'code' => (int)$data[1], 'code_name' => $data[2]];
////    var_dump($data);
//}
//
//fclose($resource);


//for ($i = 1980; $i < 2021; $i++) {
//    $dataJson = [];
//    $sql = sprintf("select * from `address` where `year` = '%s'", $i);
//    $res = $db->query($sql);
//    while($data = $res->fetchArray(SQLITE3_ASSOC)) {
//
//        $dataJson[$data['code']] = [
//            'code' => $data['code'],
//            'code_name' => $data['code_name'],
//        ];
//    }
//    $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $i . DIRECTORY_SEPARATOR . 'config.json';
//
//    file_put_contents($filePath, json_encode($dataJson, JSON_UNESCAPED_UNICODE));
//    unset($dataJson, $data);
//}

$db->close();

function download(string $url, string $filename = '')
{
    $contents = file_get_contents($url);
    $contents = str_replace(' ', '', $contents);
    preg_match_all('/\<tr.*?\>(.*?)\<\/tr\>/is', $contents, $matches);
    $data = [];

    foreach($matches[1] as $item) {
        $isMatch = preg_match('/\<td.*?\>(\w+)\<\/td\>.*?\<td.*?\>(.*?)\<\/td\>.*?/is', $item, $match);
        if ($isMatch) {
            print_r($match);
            preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $match[2], $matches);
            $data[$match[1]] = join('', $matches[0]);
        }
    }

    //file_put_contents($filename, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}


//download('http://www.mca.gov.cn/article/sj/xzqh/2020/2020/202003301019.html');

include 'Download.php';
//
$download = new Download(false);
//
////$download->download();
//$download->diffCode();

$download->copy();