<?php

/**
 * 数组的助手类
 */
class Helper_Str {


    public static function clear($str) {
        $str = trim( $str );
//        $str = strip_tags( $str, "" );//剥去 HTML、XML 以及 PHP 的标签。
        $str = str_replace( "\t", "", $str );
        $str = str_replace( "\r\n", "", $str );
        $str = str_replace( "\r", "", $str );
        $str = str_replace( "\n", "", $str );
        $str = str_replace( " ", "", $str );
        return trim( $str );
    }

    public static function replaceSpecialChar( $char ) {//过滤特殊字符
        $char = htmlspecialchars( $char ); //将特殊字元转成 HTML 格式
        $charArray = array( "~", "!", "@", "#", "$", "%", "^", "&", "*", "+", "`", "-", "=", "[", "]", "{", "}", "\\", "|", ";", ":", "'",
            "\"", "?", "/", ">", ".", "<", ",", "", "’", "”", "；", "：", "？", "。", "》", "《", "，",
        );
        foreach ( $charArray as $k => $val ) {
            $char = str_replace( $val, "", $char );
        }
        htmlentities( $char, ENT_QUOTES );
        return $char;//返回处理结果
    }

    /**截取汉字和字符
     * @param $str
     * @param $len
     * @param string $charset
     * @return string
     */
    public static function hideString( $str, $len, $charset = "utf-8" ) {
        if ( !is_numeric( $len ) or $len <= 0 ) {
            return "";
        }
        $stringLen = strlen( $str );
        if ( strtolower( $charset ) == "utf-8" ) {
            $lenStep = 3;
        } else {
            $lenStep = 2;
        }
        $length = 0;
        $strLen = 0;
        for ( $i = 0; $i < $stringLen; $i++ ) {
            if ( $length >= $len )
                break;
            if ( ord( substr( $str, $i, 1 ) ) > 0xa0 ) {
                $i += $lenStep - 1;
                $strLen += $lenStep;
            } else {
                $strLen++;
            }
            $length++;
        }
        $resultStr = substr( $str, 0, $strLen );
        return strlen( $resultStr ) < $stringLen ? $resultStr . '...' : $resultStr;
    }

    public static function strLength( $str, $charset = 'utf-8' ) {
        if ( $charset == '' ) {
            $str = iconv( 'utf-8', 'gb2312', $str );
        }
        $num = strlen( $str );
        $cnNum = 0;
        for ( $i = 0; $i < $num; $i++ ) {
            if ( ord( substr( $str, $i + 1, 1 ) ) > 127 ) {
                $cnNum++;
                $i++;
            }
        }
        $enNum = $num - ( $cnNum * 2 );
        $number = ( $enNum / 2 ) + $cnNum;
        return ceil( $number ) * 22;
    }


    public static function highLight( $str, $search, $color = '#f33', $fontSize = '14px', $fontWeight = 'bold' ) {
        return str_replace( $search, "<em style='color: $color;font-size: $fontSize;font-weight: $fontWeight'>{$search}</em>", $str );
    }



    private static function _getFirstChar( $s0 ) {
        $fchar = ord( $s0{0} );
        if ( $fchar >= ord( "A" ) and $fchar <= ord( "z" ) )
            return strtoupper( $s0{0} );
        $s = $s0;
        $asc = ord( $s{0} ) * 256 + ord( $s{1} ) - 65536;
        if ( $asc >= -20319 and $asc <= -20284 )
            return "A";
        if ( $asc >= -20283 and $asc <= -19776 )
            return "B";
        if ( $asc >= -19775 and $asc <= -19219 )
            return "C";
        if ( $asc >= -19218 and $asc <= -18711 )
            return "D";
        if ( $asc >= -18710 and $asc <= -18527 )
            return "E";
        if ( $asc >= -18526 and $asc <= -18240 )
            return "F";
        if ( $asc >= -18239 and $asc <= -17923 )
            return "G";
        if ( $asc >= -17922 and $asc <= -17418 )
            return "H";
        if ( $asc >= -17417 and $asc <= -16475 )
            return "J";
        if ( $asc >= -16474 and $asc <= -16213 )
            return "K";
        if ( $asc >= -16212 and $asc <= -15641 )
            return "L";
        if ( $asc >= -15640 and $asc <= -15166 )
            return "M";
        if ( $asc >= -15165 and $asc <= -14923 )
            return "N";
        if ( $asc >= -14922 and $asc <= -14915 )
            return "O";
        if ( $asc >= -14914 and $asc <= -14631 )
            return "P";
        if ( $asc >= -14630 and $asc <= -14150 )
            return "Q";
        if ( $asc >= -14149 and $asc <= -14091 )
            return "R";
        if ( $asc >= -14090 and $asc <= -13319 )
            return "S";
        if ( $asc >= -13318 and $asc <= -12839 )
            return "T";
        if ( $asc >= -12838 and $asc <= -12557 )
            return "W";
        if ( $asc >= -12556 and $asc <= -11848 )
            return "X";
        if ( $asc >= -11847 and $asc <= -11056 )
            return "Y";
        if ( $asc >= -11055 and $asc <= -10247 )
            return "Z";
        return null;
    }

    /**
     * 获取文字的首字母
     * @param $zh
     * @return string
     */
    public static function firstChars( $zh ) {
        $ret = "";
        $s1 = iconv( "UTF-8", "gb2312", $zh );
        $s2 = iconv( "gb2312", "UTF-8", $s1 );
        if ( $s2 == $zh ) {
            $zh = $s1;
        }
        for ( $i = 0; $i < strlen( $zh ); $i++ ) {
            $s1 = substr( $zh, $i, 1 );
            $p = ord( $s1 );
            if ( $p > 160 ) {
                $s2 = substr( $zh, $i++, 2 );
                $ret .= self::_getFirstChar( $s2 );
            } else {
                $ret .= $s1;
            }
        }
        return $ret;
    }


    public static function isUtf8( $word ) {
        if ( preg_match( "/^([" . chr( 228 ) . "-" . chr( 233 ) . "]{1}[" . chr( 128 ) . "-" . chr( 191 ) . "]{1}[" . chr( 128 ) . "-" . chr( 191 ) . "]{1}){1}/", $word ) == true || preg_match( "/([" . chr( 228 ) . "-" . chr( 233 ) . "]{1}[" . chr( 128 ) . "-" . chr( 191 ) . "]{1}[" . chr( 128 ) . "-" . chr( 191 ) . "]{1}){1}$/", $word ) == true || preg_match( "/([" . chr( 228 ) . "-" . chr( 233 ) . "]{1}[" . chr( 128 ) . "-" . chr( 191 ) . "]{1}[" . chr( 128 ) . "-" . chr( 191 ) . "]{1}){2,}/", $word ) == true ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $email
     * @return bool
     */
    public static function isEmail( $email ) {
        return preg_match( '/[\w!#$%&"*+/=?^_`{|}~-]+(?:\.[\w!#$%&"*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/', $email, $match ) ? true : false;
    }

    /**
     * @param $mobile
     * @return bool
     */
    public static function isMobile( $mobile ) {
        return preg_match( '/1[3|4|5|7|8|][0-9]{9}/', $mobile, $match ) ? true : false;
    }



}