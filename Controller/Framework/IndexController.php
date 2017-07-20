<?php

class IndexController extends Framework_BaseController {

    public function index() {
        $img = "http://wx.qlogo.cn/mmopen/6MSTLT93SHuPRaJuO0SJicpFWicX9HVzDgHdybUDza0icgQdM8eYIteqWz6OsvxVEFO0rKoFMQSYtr56ASEVHb51h8BCshHxH7G/0";
//        $rs = file_get_contents($img);

        $rs = Helper_Request::curlPost($img,array(),false);
        echo ROOT_DIR.'laonie.jpg';
        file_put_contents(ROOT_DIR.'laonie.jpg',$rs);
        Helper_Image::imageResize(ROOT_DIR.'laonie.jpg',ROOT_DIR.'laonie_new.jpg',200);

        echo 123;
        exit;

        $this->disPlayPage('laonie.php');
        exit;


//        $this->disPlayPage('react.php');
//        exit;


        $rs = $this->_clc(2130);

        var_dump($rs);
        exit;

        echo json_encode(array());
        echo '<br>';
        echo json_encode(new stdClass());
        exit;
        $handle = fsockopen('192.168.1.168',8809, $errno, $errstr, 12);
        if (!$handle) throw new Exception("在主机：  打开socket失败，失败原因是: $errno - $errstr");

        exit(123);

        echo 12345;
        $aa = file_get_contents('http://bufdsafdsfdas.com/');
        var_dump($aa,1234);

        $array = array('a','b','c');
        $in = "('".implode("','",$array)."')";
        var_dump($in);    exit;


        //随机生成IP
        $ip1 = rand( 101, 255 ) . '.';
        $ip2 = rand( 1, 255 ) . '.';
        $ip3 = rand( 1, 255 ) . '.';
        $ip4 = rand( 1, 255 );
        $ip = $ip1 . $ip2 . $ip3 . $ip4;
        $clientIp = 'CLIENT-IP:' . $ip;
        $xorForwarded = 'X-FORWARDED-FOR:' . $ip;
        //设置目标和来源
        $url = 'http://frame.fun3.cn/?method=index.logIp';
        $refer = 'http://frame.fun3.cn/';
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url ); //目标
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array($xorForwarded , $clientIp ) );  //构造IP
        curl_setopt( $ch, CURLOPT_REFERER, $refer ); //来源
        $ret = curl_exec( $ch );
        curl_close( $ch );
        var_dump($ret);
    }

    private function _clc($total){
        $t50 = floor($total/50);
        $tmpT = $total-$t50*50;
        $t20 = floor($tmpT/20);
        $tmpT = $tmpT-$t20*20;
        $t10 = floor($tmpT/10);
        return array('t_50' => $t50,'t_20' => $t20,'t_10' => $t10);
    }

    public function logIp() {
        $ip = Helper_Ip::getCurrentIP();
        echo $ip;
    }

    public function decodeMd5(){
        $api = "";
    }



    public function testLink() {
        $a = 123;
        var_dump( Helper_Request::socketRequest( 'www.laonie.com', 'index.php?method=index.index' ) );
        echo $a;
    }


    public function test11() {
        pclose( popen( "/mnt/web/fang/test.php &", 'r' ) );
        echo 123;
    }


    public function getBoyaRank() {
        Helper_Tools::addUTF8Header();
        ini_set( 'xdebug.var_display_max_depth', -1 );
        ini_set( 'xdebug.var_display_max_children', -1 );
        ini_set( 'xdebug.var_display_max_data', -1 );
        Helper_Tools::addUTF8Header();
        $api = 'https://httpentexas02.boyaagame.com/texas/act/674/ajax.php';
        $data = array(
            'cmd' => 'getRanks',
            'feild' => 1,
            'type' => 0,
            'start' => 11,
            'end' => 100,
            'apik' => 'H2wgz7D1jD1jC1uo0Wnyd7esJ9c87d',
        );
        $data = Helper_Request::curlPost( $api, $data );
        echo '<pre>';
        //        ob_start();
        var_dump( json_decode( $data ) );
        //        ob_get_clean();
    }


    public function getFbId() {
        $str = file_get_contents( 'https://21.21poker.us/api.php?configId=facebook&method=user.getUsersRank' );
        preg_match_all( '/graph\.facebook\.com\/(.*?)\/picture/', $str, $match, PREG_PATTERN_ORDER );
        $data = array();
        $this->assignVariable( 'data', $match[1] );
        $this->disPlayPage( 'list.php' );
    }


    public function searchData() {
        $this->disPlayPage( 'angular/search.php' );
    }

    public function readDir() {
        echo '<pre>';
        print_r( $this->getDir( ROOT_DIR . 'View/' ) );
    }

    private function searchDir( $path, &$data ) {
        if ( is_dir( $path ) ) {
            $path = rtrim( $path, '/' ) . '/';
            $dp = dir( $path );
            while ( $file = $dp->read() ) {
                if ( $file != '.' && $file != '..' ) {
                    $this->searchDir( $path . $file, $data );
                }
            }
            $dp->close();
        }
        if ( is_file( $path ) ) {
            echo $path . "<br/>";
            $data[] = $path;
        }
    }

    private function getDir( $dir ) {
        $data = array();
        $this->searchDir( $dir, $data );
        return $data;
    }

    public function socketData() {
        $rs = Helper_Request::serverRequest( 'L', array( 'a' => 1 ) );
        var_dump( $rs );
    }

    public function send() {
        Helper_SwiftMail::sendMail( 'test', 'send multiple emails by once ', array_flip( Common::getConfig( 'notify_email' ) ) );
    }


}





