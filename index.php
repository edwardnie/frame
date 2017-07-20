<?php
date_default_timezone_set('Asia/Shanghai'); //'Asia/Shanghai'   亚洲/上海
$month = date('Ym');
$firstDay = $month . '01';
if ( $month == '201706' ) {
    $firstDay = $month . '05';
}
//echo $startTime = strtotime( $firstDay );
//echo '<br>';
//echo $endTime = strtotime( date( 'Y-m-d', strtotime( "$firstDay +1 month -1 day" ) ) )+86400;
//     exit();


define("ROOT_DIR", dirname(__FILE__) . '/');
define("MOD_DIR", ROOT_DIR . "Model/");
define("CON_DIR", ROOT_DIR . "Controller/Framework/");
define("CONFIG_DIR", ROOT_DIR . "Config/");
define("VIEW_DIR", ROOT_DIR . "View/frame/");
define("THEMES_DIR", ROOT_DIR . "Themes/");
define('DEBUG',true);
include MOD_DIR . 'Common.php';

//注册自动加载函数
Common::registerAutoLoad();
//初始化错误信息显示
Common::initShowErrorMessage();
session_start();
//防止注入脚本
require_once(ROOT_DIR . './waf.php');
$method = $_GET['method'] ? $method = $_GET['method'] : 'index.index';
$method = explode('.', $method);
$con = ucfirst($method[0]) . "Controller";
$act = $method[1];
$conObject = new $con();
//echo strtotime('20170606');
//exit();
if (method_exists($conObject, $act)) {
    try {
        $result = $conObject->$act();
    } catch (Exception $e) {
        $result = array(
            'error' => array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            )
        );
    }

    if (isset($_GET['format']) && $_GET['format'] == 'json') {
        $conObject->outputJson($result);
    }
} else {
    throw new Exception(404);
}
return;




