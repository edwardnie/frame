<?php
define( "ROOT_DIR", dirname( __FILE__ ) . '/' );
define( "MOD_DIR", ROOT_DIR . "Model/" );
define( "CON_DIR", ROOT_DIR . "Controller/Admin/" );
define( "CONFIG_DIR", ROOT_DIR . "Config/" );
define( "VIEW_DIR", ROOT_DIR . "View/admin/" );
define( "THEMES_DIR", ROOT_DIR . "Themes" );
define('DEBUG',true);
include MOD_DIR . 'Common.php';
//注册自动加载函数
Common::registerAutoLoad();
//初始化错误信息显示
Common::initShowErrorMessage();
session_start();
if ( empty( $_GET['method'] ) ) {
    $method = 'admin.index';
} else {
    $method = $_GET['method'];
}
$method = explode( '.', $method );
$con = ucfirst( $method[0] ) . "Controller";
$act = $method[1];
$conObject = new $con();
if ( method_exists( $conObject, $act ) ) {
    try {
        $result = $conObject->$act();
    } catch ( Exception $e ) {
        $result = array(
            'error' => array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            )
        );

    }

    if ( isset( $_GET['format'] ) && $_GET['format'] == 'json' ) {
        $conObject->outputJson( $result );
    }
}
return;




