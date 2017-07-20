<?php
$url = $_SERVER['HTTP_HOST'];
define('BASE_URL', 'http://frame.laonie.com');
define('CLOUD_URL', '');
define('MEMCACHE_SWITCH',0);
return array(
    'mysqlDb' => array(
        'data' => array(
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'root',
            'passwd' => '',
            'name' => 'frame'
        ),
    ),
    'memcache' => array(
        'data' => array(
            "host" => "127.0.0.1",
            "port" => 11211,
        ),
    ),
    'socket' => array(
        'ip' => '192.168.1.133',
        'port' => 8087,
        'secret' => 'laonie'
    ),
    'callbackDataHashKey' => 'frame',
    'encryptKey' => 'frame',
    'discuzEncryptKey' => 'frame',
    'themesUrl' => BASE_URL . '/Themes/',
    'configUrl' => BASE_URL . '/',
    'adminUrl' => BASE_URL . '/center_op.php',
    'apiUrl' => BASE_URL . '/api.php',
    //来来
//    'weiXin' => array(
//        'weiXinToken' => 'laiyibatoken',
//        'weiXinApi' => 'https://api.weixin.qq.com/cgi-bin/',
//        'appId' => 'wx8ab200e5f751425b',
//        'appSecret' => 'b317907e426e88c52e90b8c32781727d'
//    ),
       //来3
        'weiXin' => array(
            'weiXinToken' => 'laiyibatoken',
            'weiXinApi' => 'https://api.weixin.qq.com/cgi-bin/',
            'appId' => 'wx5f620d428af83fd8',
            'appSecret' => '1e19410f93968f86d3ad95ebff21ec1a'
        ),

    //宁波
//    'weiXin' => array(
//        'weiXinToken' => 'laiyibatoken',
//        'weiXinApi' => 'https://api.weixin.qq.com/cgi-bin/',
//        'appId' => 'wx58b0ac91ff25b08c',
//        'appSecret' => '5d68c16c3e2cd4c9197f4da661e998d6'
//    ),

    'aliyun' => array(
        'Bucket' => 'odfang',
        'AccessKeyId' => '',
        'AccessKeySecret' => ''
    ),

    'weibo' => array(
        'appKey' => '4230238129',
        'secretKey' => '',
        'callback' => BASE_URL . '/?method=Sina.callback'
    ),
    'webChat' => array(
        'appId' => '',
        'appSecret' => ''
    )
);
