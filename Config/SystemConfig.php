<?php
$url = $_SERVER['HTTP_HOST'];
define('BASE_URL', 'http://frame.laonie.com');
define('CLOUD_URL', 'http://odfang.oss-cn-hangzhou.aliyuncs.com');
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
    'weiXin' => array(
        'weiXinToken' => 'edward_nie',
        'weiXinApi' => 'https://api.weixin.qq.com/cgi-bin/',
        'appId' => 'wx02250f651bee1bd3',
        'appSecret' => '3e9fb7a1c61a2be7d88e66d119d450df'
    ),

    'aliyun' => array(
        'Bucket' => 'odfang',
        'AccessKeyId' => 'BBjDi8uydiZ0PaPJ',
        'AccessKeySecret' => '2TfefbfURs76q8UYBOsCBNCXeOxCBW'
    ),

    'weibo' => array(
        'appKey' => '4230238129',
        'secretKey' => 'a76e8ab4b32e39d4e4cdfa0211e75083',
        'callback' => BASE_URL . '/?method=Sina.callback'
    ),
    'webChat' => array(
        'appId' => 'wx03b735be01e5d967',
        'appSecret' => 'ca4cef628f045c1a8348031d994e025f'
    )
);
