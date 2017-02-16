<?php
date_default_timezone_set('Asia/Shanghai'); //'Asia/Shanghai'   亚洲/上海
define('VERSION', rand(1,100000));
return array(
    'email_config' => array(
        'host' => 'smtp.126.com',
        'port' => '25',
        'user' => 'trisee@126.com',
        'passwd' => '',
        'name' => '么么房'
    )
);