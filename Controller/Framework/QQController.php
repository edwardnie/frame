<?php
require_once ROOT_DIR . 'Lib/qq_login/API/qqConnectAPI.php';

class QQController extends Framework_BaseController {

    public function login() {
        Helper_Login::checkIP(100);
        $qc = new QC();
        $qc->qq_login();
    }

    public function callback() {
        $qc = new QC();
        $accessToken = $qc->qq_callback();
        $openid = $qc->get_openid();
//        $userInfo = $qc->get_user_info();
//        var_dump($accessToken,$openid);exit;
        $redirectUrl = $_SESSION['pre_url']?$_SESSION['pre_url']:Helper_Url::getUrl( 'user.userInfo' );
        if ( $openid ) {
            $userInfo = $qc->get_user_info();
            $webUser = User_Admin::getInstance()->getUserInfo( array( 'platform_id' => $openid,'source' =>'qq' ) );
            $data = array(
                'name' => $userInfo['nickname'],
                'password' => md5( $openid ),
                'user_type' => 100,
                'avatar' => $userInfo['figureurl_2'],
                'platform_id' => $openid,
                'source' => 'qq'
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
                'user' => $userInfo['nickname'],
                'ip' => Helper_Tools::getCurrentIP(),
                'time' => time()
            );
            Admin_Login::getInstance()->insertLogin( $log );
            User_Admin::getInstance()->addUser( array( 'uid' => $userId, 'last_login_time' => time() ) );
            setcookie( 'sessionId', md5( 'Login_' . $userInfo['nickname'] . '_' . md5( $openid ) ) );
            setcookie( 'admin_name', $userInfo['nickname'] );
            setcookie( 'pwd', md5( $openid ) );
            setcookie( 'avatar', $userInfo['figureurl_2'] );
            setcookie( 'admin_user_type', 100 );
            setcookie( 'admin_uid', $userId );
            header( 'location:' . $redirectUrl );
            exit;
        }
        echo '<script>window.opener.location.href="' . $redirectUrl . '";</script>';
        exit;
    }

}
