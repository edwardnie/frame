<?php

class WeiXinController extends Framework_BaseController {

    private $_weiXin = array();

    public function __construct() {
        parent::__construct();
        $this->_weiXin = Common::getConfig( 'weiXin' );
    }

    public function showMsg() {
        $data = Platform_WeiXin::getInstance( false )->getMsg();
        $this->assignVariable( 'data', $data );
        $this->disPlayPage( 'frame/wx/msg.php' );
    }


    public function index() {
        $data = Platform_WeiXin::getInstance()->request();
        list( $content, $type ) = $this->_replay( $data );
        Platform_WeiXin::getInstance()->response( $content, $type );
    }

    public function menuAdd() {
        $accessToken = Platform_WeiXin::getInstance( false )->getAccessToken();
        $url = $this->_weiXin['weiXinApi'] . 'menu/create?access_token=' . $accessToken;
        $data = '';
        /**'{
         * "button": [
         * {
         * "name": "菜单",
         * "sub_button": [
         * {
         * "type": "view",
         * "name": "搜索",
         * "url": "http://www.soso.com/"
         * },
         * {
         * "type": "view",
         * "name": "视频",
         * "url": "http://v.qq.com/"
         * },
         * {
         * "type": "click",
         * "name": "赞我一下",
         * "key": "523"
         * }
         * ]
         * },
         * {
         * "name": "技术进阶",
         * "sub_button": [
         * {
         * "type": "view",
         * "name": "菜鸟",
         * "url": "http://www.soso.com/"
         * },
         * {
         * "type": "view",
         * "name": "老菜鸟",
         * "url": "http://v.qq.com/"
         * },
         * {
         * "type": "view",
         * "name": "小牛",
         * "url": "http://v.qq.com/"
         * },
         * {
         * "type": "view",
         * "name": "大牛",
         * "url": "http://v.qq.com/"
         * },
         * {
         * "type": "view",
         * "name": "神人",
         * "url": "http://v.qq.com/"
         * }
         * ]
         * },
         * {
         * "type": "click",
         * "name": "按钮事件",
         * "key": "3"
         * }
         * ]
         * }';    **/
        $response = Helper_Request::curlPost($url,$data);
        var_dump( $response );
    }

    public function menuDelete() {
        $url = $this->_weiXin['weiXinApi'] . 'menu/delete?access_token=' . Platform_WeiXin::getInstance( false )->getAccessToken();
        $response = Helper_Request::curlPost( $url, array(), false );
        var_dump( $response );
        return true;
    }

    private function _replay( $data = array() ) {
        if ( $data['Content'] == 'music' ) {
            $music = array( '因为有你', 'sad but hearted music', 'http://laonie.duapp.com/music/cut.mp3',
                'http://laonie.duapp.com/music/cut.mp3' );
            return array( $music, 'music' );
        }
        if ( $data['Content'] == 'image' ) {
            $music = array(
                array( 'Window XP 系统彻底淘汰了，win8安全性能有待考验，win7是不错的选择哦',
                    '这是俺老婆', 'http://laonie.duapp.com/images/sign.jpg',
                    'http://www.baidu.com' ),
                array( '这是俺老婆哦，很漂亮吧，闪瞎你们的狗眼', '这是俺老婆', 'http://laonie.duapp.com/images/love.jpg',
                    'http://laonie.duapp.com/images/love.jpg' ),
                array( '这是俺老婆的老家哦，环境真的很不错哦', '这是俺老婆', 'http://laonie.duapp.com/images/sign.jpg',
                    'http://laonie.duapp.com/images/sign.jpg' ) );
            return array( $music, 'news' );
        }
        if ( $data['Event'] == 'CLICK' ) {
            if ( $data['EventKey'] == '3' ) {
                $url = Helper_Url::getUrl( 'index.showCode' ) . '&openid=' . $data['FromUserName'];
                $gift = array(
                    array( '金币大放送',
                        '', 'http://laonie.duapp.com/images/sign.jpg' ),
                    array( "二人斗地主兑换码\n\r本周未领取", '本周未领取',
                        'http://laonie.duapp.com/images/love.jpg', $url . '&id=1' ),
                    array( '德州扑克兑换码', '本周未领取',
                        'http://laonie.duapp.com/images/sign.jpg', $url . '&id=2' ),
                    array( '二人斗地主兑换码', '本周未领取',
                        'http://laonie.duapp.com/images/love.jpg', $url . '&id=3' ),
                    array( '百家乐兑换码', '本周未领取',
                        'http://laonie.duapp.com/images/sign.jpg', $url . '&id=4' ),
                );
                return array( $gift, 'news' );
            }
        }
        if ( $data['Event'] == 'subscribe' ) {
            $url = Helper_Url::getUrl( 'index.showCode' ) . '&openid=' . $data['FromUserName'];
            $gift = array(
                array( '金币大放送',
                    '', 'http://laonie.duapp.com/images/sign.jpg' ),
                array( "二人斗地主兑换码\n\r本周未领取", '本周未领取',
                    'http://laonie.duapp.com/images/love.jpg', $url . '&id=1' ),
                array( "德州扑克兑换码\n\r本周未领取", '本周未领取',
                    'http://laonie.duapp.com/images/sign.jpg', $url . '&id=2' ),
                array( "二人斗地主兑换码\n\r本周未领取", '本周未领取',
                    'http://laonie.duapp.com/images/love.jpg', $url . '&id=3' ),
                array( "百家乐兑换码\n\r本周未领取", '本周未领取',
                    'http://laonie.duapp.com/images/sign.jpg', $url . '&id=4' ),
            );
            return array( $gift, 'news' );
        }
        //		return array( '谢谢您的光临', 'text' );
    }

    public function getTempQR() {
        $data = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}';
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . Platform_WeiXin::getInstance( false )->getAccessToken();
        $response = Helper_Request::curlPost($url,$data);
        var_dump( $response );
    }

    public function getPermanentQR() {

    }
}
