<?php
require_once ROOT_DIR . 'Lib/sina/saetv2.ex.class.php';

class SinaController extends Framework_BaseController {
    public $sinaConfig;

    public function __construct() {
        parent::__construct();
        $this->sinaConfig = Common::getConfig( 'weibo' );
    }

    public function login() {
        //        Helper_Login::checkIP( 100 );
        $auth = new SaeTOAuthV2( $this->sinaConfig['appKey'], $this->sinaConfig['secretKey'] );
        $code_url = $auth->getAuthorizeURL( $this->sinaConfig['callback'] );
        header( 'location:' . $code_url );
    }

    public function callback() {
        $auth = new SaeTOAuthV2( $this->sinaConfig['appKey'], $this->sinaConfig['secretKey'] );
        if ( isset( $_REQUEST['code'] ) ) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = $this->sinaConfig['callback'];
            $token = $auth->getAccessToken( 'code', $keys );
        }
        $redirectUrl = $_SESSION['pre_url']?$_SESSION['pre_url']:Helper_Url::getAdminUrl();
        if ( $token ) {
            $c = new SaeTClientV2( $this->sinaConfig['appKey'], $this->sinaConfig['secretKey'], $token['access_token'] );
            $uid_get = $c->get_uid();
            $uid = $uid_get['uid'];
            $userInfo = $c->show_user_by_id( $uid );//根据ID获取用户等基本信息
            if ( $userInfo['screen_name'] ) {
                $webUser = User_Admin::getInstance()->getUserInfo( array( 'platform_id' => $uid, 'source' => 'sina' ) );
                $data = array(
                    'name' => $userInfo['screen_name'],
                    'password' => md5( $uid ),
                    'user_type' => 100,
                    'avatar' => $userInfo['profile_image_url'],
                    'platform_id' => $uid,
                    'source' => 'sina'
                );
                if ( empty( $webUser ) ) {
                    $userId = User_Admin::getInstance()->addUser( $data );
                } else {
                    if ( $webUser['name'] != $data['name'] || $webUser['avatar'] != $data['avatar'] ) {
                        $data['uid'] = $webUser['uid'];
                        User_Admin::getInstance()->addUser( $data );
                    }
                    $userId = $webUser['uid'];
                }
                $log = array(
                    'user' => $userInfo['screen_name'],
                    'ip' => Helper_Tools::getCurrentIP(),
                    'time' => time()
                );
                Admin_Login::getInstance()->insertLogin( $log );
                User_Admin::getInstance()->addUser( array( 'uid' => $userId, 'last_login_time' => time() ) );
                setcookie( 'sessionId', md5( 'Login_' . $userInfo['screen_name'] . '_' . md5( 'sina' ) ) );
                setcookie( 'admin_name', $userInfo['screen_name'] );
                setcookie( 'pwd', md5( 'sina' ) );
                setcookie( 'avatar', $userInfo['profile_image_url'] );
                setcookie( 'admin_user_type', 100 );
                setcookie( 'admin_uid', $userId );
                header( 'location:' . $redirectUrl );
                exit;
            }
            header( 'location:' . $redirectUrl );
            exit;
        }
        header( 'location:' . $redirectUrl );
        exit;
    }
}
