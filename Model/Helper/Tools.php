<?php

class Helper_Tools {

    //添加utf-8头标签
    public static function addUTF8Header() {
        header("Content-type: text/html; charset=utf-8");
    }

    //获取文件名，支持中文
    public static function getBasename($filename) {
        return preg_replace('/^.+[\\\\\\/]/', '', $filename);
    }

    //获取文件的后缀
    public static function getExtension($file) {
        return pathinfo($file, PATHINFO_EXTENSION);
    }


    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     * @param lng float 经度
     * @param lat float 纬度
     * @param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     * @return array 正方形的四个点的经纬度坐标
     */
    public static function returnSquarePoint($lng, $lat, $distance = 0.5) {
        $earth = 6371;
        $dlng = 2 * asin(sin($distance / (2 * $earth)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance / $earth;
        $dlat = rad2deg($dlat);
        return array(
            'left-top' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right-top' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );
    }



    ####################################GOOGLE 身份验证器###############################################
    /**
     * 生成秘钥
     * @return string
     */
    public static function createSecret() {
        require_once ROOT_DIR . 'Lib/GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        return $ga->createSecret();
    }

    /**
     * 生成二维码
     * @param $name
     * @param $secret
     * @return string
     */
    public static function qrCode($name, $secret, $title = '么么房') {
        require_once ROOT_DIR . 'Lib/GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        return $qrCodeUrl = $ga->getQRCodeGoogleUrl($name, $secret, $title);
    }

    /**
     * 验证
     * @param $code
     * @param $secret
     * @return bool
     */
    public static function googleVerify($code, $secret) {
        require_once ROOT_DIR . 'Lib/GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        $checkResult = $ga->verifyCode($secret, $code, 10);
        if ($checkResult) {
            return true;
        } else {
            return false;
        }
    }
    ####################################GOOGLE 身份验证器 END###############################################


    /**
     * 判断是否是移动设备
     * @return bool
     */
    public static function isMobileDevice() {
        //如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        //脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientKeyWords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientKeyWords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            //如果只支持wml并且不支持html那一定是移动设备
            //如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断是否是微信客户端
     * @return bool
     */
    public static function isWebChat() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }


}