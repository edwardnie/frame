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
