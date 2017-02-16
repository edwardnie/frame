<?php

class Helper_Request {

    public static function curlPost($url, $data, $post = true, $header = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if ($post) {
            if ($header) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function curlCookie() {
        /*-----保存COOKIE-----*/
        $url = 'www.111cn.net'; //url地址
        $post = "id=user&pwd=123456"; //POST数据
        $cookie = tempnam('./', 'cookie'); //cookie临时文件
        $ch = curl_init($url); //初始化
        curl_setopt($ch, CURLOPT_HEADER, 1); //将头文件的信息作为数据流输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回获取的输出文本流
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); //发送POST数据
        $content = curl_exec($ch); //执行curl并赋值给$content
        preg_match('/Set-Cookie:(.*);/iU', $content, $str); //正则匹配
        $cookie = $str[1]; //获得COOKIE（SESSIONID）
        curl_close($ch); //关闭curl
        /*-----使用COOKIE-----*/
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }

    /**
     * socket 链接
     * @param $type
     * @param $info
     * @return mixed|null|string
     * @throws Exception
     */
    public static function serverRequest($info) {
        $config = Common::getConfig('socket');
//        $info['timestamp'] = time();
//        $info['cmd'] = $type;
//        $info = json_encode($info);
//        $sign = md5($config['secret'] . $info);
//        $post_string = $sign . $info;

        $post_string = $info;
        $handle = fsockopen($config['ip'], $config['port'], $errno, $errstr, 2);
        if (!$handle) throw new Exception("在主机：  打开socket失败，失败原因是: $errno - $errstr");
        fputs($handle, $post_string);
        $result = "";
        $total_len = 0;
        while (!feof($handle)) {
            stream_set_timeout($handle, 1);
            $buffer = fgets($handle, 4096); //4K per 20 sec
            $info = stream_get_meta_data($handle);
            if ($info ['timed_out']) break;
            $total_len += strlen($buffer);
            $result .= $buffer;
            if ($total_len > 1000000) return ""; // more than 2M filesize, properly not correct url for news or list
        }
        //fclose($handle);
        var_dump($result);
        if (!empty($result)) {
            $result = json_decode($result, true);
        } else {
            $result = array();
        }
        return $result;
    }

    /**
     * PHP 模拟异步
     * @param $hostname
     * @param int $port
     * @return bool
     */
    public static function socketRequest($hostname,$url,$port=80){
        $fp = fsockopen($hostname, $port, $errno, $errstr, 30);
        if ($fp) {
            $out = "GET /$url / HTTP/1.1\r\n";
            $out .= "Host: {$hostname}\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            fclose($fp);
            return true;
        }
        return false;
    }
}
