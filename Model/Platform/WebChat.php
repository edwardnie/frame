<?php

/**
 * Class Platform_WebChat
 * 微信网页版登陆
 */
class Platform_WebChat {
    private static $_singletonObjects = null;
    private $_cacheInstance = null;
    private $_dbInstance = null;
    private $_weiXin = array();

    public function  __construct( $exit = true ) {
        $this->_weiXin = Common::getConfig( 'webChat' );
    }

    /**
     * @param bool $exit
     * @return null|Platform_WebChat
     */
    public static function getInstance( $exit = true ) {
        if ( self::$_singletonObjects == null ) {
            self::$_singletonObjects = new self( $exit );
        }
        return self::$_singletonObjects;
    }

    public function getLoginUrl() {
        $redirectUrl = urlencode( Helper_Url::getAdminUrl( 'webChat.callback' ) );
        $state = microtime( true ) . rand( 1, 10000000000 );
        $_SESSION[$state] = $state;
        $url = "https://open.weixin.qq.com/connect/qrconnect?appid={$this->_weiXin['appId']}&redirect_uri={$redirectUrl}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
        return $url;
    }

    public function getAccessToken( $code, $state = '' ) {
        $bool = ( $state == $_SESSION[$state] ) ? true : false;
        unset( $_SESSION[$state] );
        if ( !$bool ) {
            return array( 'errmsg' => "invalid state" );
        }
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_weiXin['appId']}&secret={$this->_weiXin['appSecret']}&code={$code}&grant_type=authorization_code";
        $data = Helper_Request::curlPost( $url, array(), false );
        $data = json_decode( $data, true );
        return $data;
    }

    public function checkToken( $refreshToken ) {
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$this->_weiXin['appId']}&grant_type=refresh_token&refresh_token={$refreshToken}";
        $data = file_get_contents( $url );
        $data = json_decode( $data, true );
        return $data;
    }

    public function getUserInfo( $accessToken ) {
        if ( $accessToken['access_token'] && $accessToken['openid'] ) {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$accessToken['access_token']}&openid={$accessToken['openid']}";
            $data = Helper_Request::curlPost( $url, array(), false );
            $data = json_decode( $data, true );
        } else {
            return array( 'errmsg' => "invalid params" );
        }
        return $data;
    }

    private function _getCache() {
        if ( $this->_cacheInstance == NULL ) {
            $this->_cacheInstance = Common::getCache( 'data' );
        }
        return $this->_cacheInstance;
    }

} 