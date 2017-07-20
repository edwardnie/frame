<?php

class Helper_Login {
    public static function checkLogin( $type = 1 ) {
        if ($_REQUEST['code'] && strtolower( $_SESSION['auth_img_code'] ) != md5( strtolower( $_REQUEST['code'] ) )) {
            echo "<script>alert('验证码错误')</script>";
            include VIEW_DIR . 'login.php';
            exit;
        }
        if ( !self::loginState() ) {
            $admin = $_REQUEST['admin'];
            $password = $_REQUEST['password'];
            if ( is_null( $admin ) || is_null( $password ) || is_null($_REQUEST['code']) ) {
                include VIEW_DIR . 'login.php';
            } else {
                $adminData = User_Info::getInstance()->getUserByName( $admin );
                if ( $password && md5( $password . $adminData['pass_key'] ) == $adminData['password'] ) {
                    //后台用户登录日志
                    $data = array(
                        'uid' => $adminData['uid'],
                        'ip' => Helper_Ip::getCurrentIP(),
                        'time' => time()
                    );
                    DB::add('frame_log_login',$data);
                    //更新用户的最后登录时间
                    User_Info::getInstance()->updateUserInfo( array( 'last_active' => time() ), array( 'uid' => $adminData['uid'] ) );
                    $userInfo = array(
                        'admin_uid' => $adminData['uid'],
                        'admin_name' => $adminData['nickname'],
                        'admin_role_id' => Helper_Encrypt::encrypt( $adminData['role_id'] ),
                        'tel' => $adminData['tel'],
                        'email' => time(),
                        'expire_time' => time() + 43200,
                        'current_ip' => Helper_Ip::getCurrentIP()
                    );
                    setcookie( 'PHPWEBCOOKIE', Helper_Encrypt::encrypt( json_encode( $userInfo ) ), time() + 36000 );
                    setcookie( 'PHPSESSIONWEBID', md5( 'Login_' . Helper_Encrypt::encrypt( json_encode( $userInfo ) ) . Common::getConfig( 'encryptKey' ) ), time() + 36000 );
                    echo "<script>window.location.href='center_op.php'</script>";
                } else {

                    echo "<script>alert('用户名密码错误')</script>";
                    include VIEW_DIR . 'login.php';
                }
            }
            exit;
        }else{
            //防止前端用户来后台登录做权限判断
            $userInfo = self::getUserInfo();
            if ( $userInfo['admin_uid'] && ( $userInfo['admin_role_id'] == 100 || $userInfo['admin_role_id'] < 1 ) ) {
                header( 'location:' . Helper_Url::getUrl( 'user.login' ) );
                exit;
            }
        }

    }

    public static function checkIP( $userType = 100 ) {
        Helper_Tools::addUTF8Header();
        $ip = Helper_Ip::getCurrentIP();
        $data = User_Ip::getInstance()->getIpList();
        $ips = array();
        foreach ( $data as $list ) {
            $ips[] = $list['ip'];
        }
        if ( !( in_array( $ip, $ips ) || $userType == 1 ) ) {
            echo "<script>alert('该用户禁止在指定IP以外的地址登陆')</script>";
            echo "<script>history.go(-1);</script>";
            exit;
        }
        return true;
    }

    /**
     * 判断用户是否有权限操作
     * @param int $userType
     * @return bool
     */
    public static function checkPermission( $userType = 1 ) {
        Helper_Tools::addUTF8Header();
        if ( !self::loginState() ) {
            echo "<script>alert('登录信息已经失效，请重新登录!'); history.go(-1);</script>";
            exit;
        }
        $userInfo = self::getUserInfo();
        if ( $userInfo['admin_role_id'] > $userType ) {
            echo "<script>alert('没有权限操作这一项'); history.go(-1);</script>";
            exit;
        }
        return true;
    }

    public static function getUserInfo( $key = '' ) {
        $userInfo = Helper_Encrypt::decrypt( $_COOKIE['PHPWEBCOOKIE'] );
        $userInfo = json_decode( $userInfo, true );
        $userInfo['admin_role_id'] = Helper_Encrypt::decrypt( $userInfo['admin_role_id'] );
        if ( $key ) {
            return $userInfo[$key];
        }
        return $userInfo;
    }

    public static function loginState() {
        if ( md5( 'Login_' . $_COOKIE['PHPWEBCOOKIE'] . Common::getConfig( 'encryptKey' ) ) == $_COOKIE['PHPSESSIONWEBID'] ) {
            //如果过期了，清除cookie
            $userInfo = self::getUserInfo();
            if ( $userInfo['expire_time'] < time() ) {
                setcookie( 'PHPSESSIONWEBID', '', time() - 3600 );
                setcookie( 'PHPWEBCOOKIE', '', time() - 3600 );
                return false;
            }
            //如果IP对不上，清除cookie
            if ( $userInfo['current_ip'] != Helper_Ip::getCurrentIP() ) {
                setcookie( 'PHPSESSIONWEBID', '', time() - 3600 );
                setcookie( 'PHPWEBCOOKIE', '', time() - 3600 );
                return false;
            }
            return true;
        }
        setcookie( 'PHPSESSIONWEBID', '', time() - 3600 );
        setcookie( 'PHPWEBCOOKIE', '', time() - 3600 );
        return false;
    }
}