<?php

/**
 * URL助手
 * @author Lucky
 */
class Helper_Url {

//    /**
//     * 获取CDN地址
//     * @param        string $filePath 相对路径
//     * @param        int $userId [optional]        用户ID，如果不填，则使用随机
//     * @return        string
//     */
//    public static function cdn( $filePath = '', $userId = 0 ) {
//        $cdnUrls = Common::getConfig( 'cdnUrl' );
//        if ( $userId == 0 ) {
//            $userId = mt_rand();
//        }
//
//        if ( ( $cdnUrlCount = count( $cdnUrls ) ) <= 0 ) {
//            return '';
//        }
//
//        return $cdnUrls[$userId % $cdnUrlCount] . $filePath;
//    }

    public static function themes( $filePath = '' ) {
        $themes = Common::getConfig( 'themesUrl' );
        return $themes . $filePath . '?' . VERSION;
    }

    public static function cdn($filePath = ''){
        $themes = Common::getConfig( 'cdnUrl' );
        return $themes . $filePath . '?' . VERSION;
    }


    public static function getUrl( $method = '', $params = array() ) {
        if ( $method ) {
            $params = array_merge( array( 'method' => $method ), $params );
        }
        $url = Common::getConfig( 'configUrl' );
        if ( $params ) {
            if ( strpos( $url, "?" ) === false ) {
                $url .= "?";
            } else {
                $url .= "&";
            }
            $url .= http_build_query( $params );
        }
        return $url;
    }

    public static function getAdminUrl( $method = '', $params = array() ) {
        if ( $method ) {
            $params = array_merge( array( 'method' => $method ), $params );
        }
        $url = Common::getConfig( 'adminUrl' );
        if ( $params ) {
            if ( strpos( $url, "?" ) === false ) {
                $url .= "?";
            } else {
                $url .= "&";
            }
            $url .= http_build_query( $params );
        }
        return $url;
    }

    public static function getMobileUrl( $method = '', $params = array() ) {
        if ( $method ) {
            $params = array_merge( array( 'method' => $method ), $params );
        }
        $url = Common::getConfig( 'mobileUrl' );
        if ( $params ) {
            if ( strpos( $url, "?" ) === false ) {
                $url .= "?";
            } else {
                $url .= "&";
            }
            $url .= http_build_query( $params );
        }
        return $url;
    }

    public static function getApiUrl( $method = '', $params = array() ) {
        if ( $method ) {
            $params = array_merge( array( 'method' => $method ), $params );
        }
        $url = Common::getConfig( 'apiUrl' );
        if ( $params ) {
            if ( strpos( $url, "?" ) === false ) {
                $url .= "?";
            } else {
                $url .= "&";
            }
            $url .= http_build_query( $params );
        }
        return $url;
    }

    /**
     * 获得请求的协议
     * @return        https:// or http:// :string
     */
    public static function getHttpProtocol() {
        return self::isHttpsProtocal() ? 'https://' : 'http://';
    }

    /**
     * 当前请求是否使用https协议
     * @return        boolean
     */
    public static function isHttpsProtocal() {
        if ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 替换不符合当前请求的HTTP协议头
     * @param        string $url URL地址
     * @return        string
     */
    public static function replaceInvalidHttpProtocalHeader( $url ) {
        if ( Helper_Url::isHttpsProtocal() ) {
            if ( strpos( $url, 'https://' ) === false ) {
                $url = str_replace( 'http://', 'https://', $url );
            }
        } else {
            if ( strpos( $url, 'http://' ) === false ) {
                $url = str_replace( 'https://', 'http://', $url );
            }
        }
        return $url;
    }

    public static function getNowUrl() {
        return self::getHttpProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    }

    public static function getQuery() {
        return $_SERVER['QUERY_STRING'];
    }

    public static function buildQuery( $data = array() ,$type=1) {
        $query = $_SERVER['QUERY_STRING'];
        parse_str( $query, $array );
        foreach ( $data as $k => $v ) {
            if ( $v )
                $array[$k] = $v;
            else {
                unset( $array[$k] );
            }
        }
        unset($array['page']);
		if($type) {
			unset($array['dong_like']);
			unset($array['room_number']);
		}
        return http_build_query( $array );
    }

    public static function checkSign($data,$sign){
        sort($data,SORT_STRING );
        $hash = md5(implode(',',$data).Common::getConfig('callbackDataHashKey'));
        if($sign == $hash){
            return true;
        }
        return false;
    }

}