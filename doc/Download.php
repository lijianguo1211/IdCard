<?php
/**
 * Notes:
 * File name:Download
 * Create by: Jay.Li
 * Created on: 2021/2/23 0023 10:14
 */


class Download
{
    protected $type;

    protected $url;

    public function __construct(bool $type = true)
    {
        $this->type = $type;

        $this->url = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'url.json'), true);
    }

    public function download()
    {
        if ($this->type) {
            $urlColl = $this->url['newest'];
        } else {
            $urlColl = $this->url['overTheYears'];
        }

        $path = __DIR__ . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR;

        $count = count($urlColl);

        $i = 1;
        foreach ($urlColl as $year => $url) {
            $filePath = $path . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . 'config.json';
            echo " ########## [ $i / $count ] ########## " . PHP_EOL;
            $i++;
            $data = $this->http($url);

            if ($data['status'] !== 200) {

                echo "######## message start ########" . PHP_EOL;

                echo $data['message'] . PHP_EOL;

                echo "######## message end ########" . PHP_EOL;
            }
            $contents = $data['data'];
            $contents = str_replace(' ', '', $contents);
            preg_match_all('/\<tr.*?\>(.*?)\<\/tr\>/is', $contents, $matches);

            $data = [];
            foreach($matches[1] as $item) {
                $isMatch = preg_match('/\<td.*?\>(\w+)\<\/td\>.*?\<td.*?\>(.*?)\<\/td\>.*?/is', $item, $match);
                if ($isMatch) {
                    preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $match[2], $matches);
                    $data[$match[1]] = [
                        'code' => $match[1],
                        'code_name' => implode('', $matches[0]),
                    ];
                }
            }


            echo $filePath . PHP_EOL;
            $data = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            file_put_contents($filePath, $data);

            unset($data, $contents, $matches);
        }
    }

    public function diffCode()
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'json';

        $getAllFile = function (string $filePath) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath));

            foreach ($files as $name => $file) {

                if ($file->isDir()) {
                    continue;
                }

                if (!$file->isFile()) {
                    continue;
                }

                yield $file->getRealPath();
            }
        };

        $resources = '';
        $year = 1980;
        foreach ($getAllFile($filePath) as $file) {
            if ($year == 1980) {
                $year++;
                $resources = json_decode(file_get_contents($file), true);//old
                continue;
            }

            $tempResources = json_decode(file_get_contents($file), true);//new


            echo "##############" . PHP_EOL;
            $delete = array_diff_key($resources, $tempResources);
            $last = array_diff_key($tempResources, $resources);

            $data = compact('delete', 'last');

            $data = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'diff' . DIRECTORY_SEPARATOR . $year . '.json', $data);

            echo "##############" . PHP_EOL;
            $resources = $tempResources;
            $year++;
        }
    }

    protected function http(string $url, string $method = 'GET', array $data = [])
    {
        $resource = curl_init($url);
        if ($resource === false) {
            return ['status' => 5000000, 'message' => "curl init 打开cURL句柄失败！"];
        }

        try {
            //允许 cURL 函数执行的最长秒数。
            curl_setopt($resource, CURLOPT_TIMEOUT, 120);
            if ($method === 'POST') {
                //TRUE 时会发送 POST 请求，类型为：application/x-www-form-urlencoded，是 HTML 表单提交时最常见的一种
                curl_setopt($resource, CURLOPT_POST, true);
                curl_setopt($resource, CURLOPT_POSTFIELDS, json_encode($data)); //设置请求体，提交数据包
            }
            //TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
            curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);

            //当 HTTP 状态码大于等于 400，TRUE 将将显示错误详情。 默认情况下将返回页面，忽略 HTTP 代码。
            curl_setopt($resource, CURLOPT_FAILONERROR, true);
            $result = [
                'status' => 200,
                'message' => 'success',
            ];
            // 抓取URL并把它传递给浏览器
            $result['data'] = curl_exec($resource);

        } catch (\Exception $e) {
            $message = curl_error($resource);//错误消息
            $code = curl_errno($resource);//错误代码

            $result = [
                'status' => 5000001,
                'message' => $e->getMessage() . ' === ' . $message,
                'code' => $code,
            ];
        }

        curl_close($resource);// 关闭 cURL 会话

        return $result;
    }

    public function copy()
    {
        $pathDiff = __DIR__ . DIRECTORY_SEPARATOR . 'diff';
        $pathJson = __DIR__ . DIRECTORY_SEPARATOR . 'json';
        $files = [
            $pathDiff => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'diff',
            $pathJson => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config',
        ];
        try {
            foreach ($files as $source => $dest) {
                $this->copyDir($source, $dest);
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public function copyDir($source, $dest)
    {
        if (!file_exists($dest)){
            mkdir($dest);
        }
        $handle = opendir($source);
        while (($item = readdir($handle)) !== false) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            $_source = $source . '/' . $item;
            $_dest = $dest . '/' . $item;
            if (is_file($_source)) {
                copy($_source, $_dest);
            }
            if (is_dir($_source)) {
                $this->copyDir($_source, $_dest);
            }
        }
        closedir($handle);
    }
}