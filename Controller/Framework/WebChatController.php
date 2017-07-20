<?php

class WebChatController extends Framework_BaseController {

    public function login() {
        $url = Platform_WebChat::getInstance()->getLoginUrl();
        header( 'Location:' . $url );
        exit;
    }

    public function callback() {
        $code = $this->inputDatas['code'];
        $state = $this->inputDatas['state'];
        $accessToken = Platform_WebChat::getInstance()->getAccessToken( $code, $state );
        $userInfo = Platform_WebChat::getInstance()->getUserInfo( $accessToken );
        $redirectUrl = $_SESSION['pre_url']?$_SESSION['pre_url']:Helper_Url::getUrl( 'user.userInfo' );
        if ( $userInfo['unionid'] ) {
            $webUser = User_Admin::getInstance()->getUserInfo( array( 'platform_id' => $userInfo['unionid'],'source' =>'webchat' ) );
            $data = array(
                'name' => $userInfo['nickname'],
                'password' => md5( $userInfo['unionid'] ),
                'user_type' => 100,
                'avatar' => $userInfo['headimgurl'],
                'platform_id' => $userInfo['unionid'],
                'source' => 'webchat'
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
            setcookie( 'sessionId', md5( 'Login_' . $userInfo['nickname'] . '_' . md5( $userInfo['unionid'] ) ) );
            setcookie( 'admin_name', $userInfo['nickname'] );
            setcookie( 'pwd', md5( $userInfo['unionid'] ) );
            setcookie( 'avatar', $userInfo['headimgurl'] );
            setcookie( 'admin_user_type', 100 );
            setcookie( 'admin_uid', $userId );
            header( 'location:' . $redirectUrl );
            exit;
        }
        header( 'location:' . $redirectUrl );
        exit;
    }
}
