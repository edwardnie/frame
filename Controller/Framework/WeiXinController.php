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
        Helper_Tools::addUTF8Header();
        $accessToken = Platform_WeiXin::getInstance( false )->getAccessToken();
        //var_dump($accessToken);
        $url = $this->_weiXin['weiXinApi'] . 'menu/create?access_token=' . $accessToken;
        echo $url;
        exit();
        //来来斗、牛、菜、单
//        $data = '
//        {
//             "button": [
//             {
//                 "name": "下载游戏",
//                 "sub_button": [
//                     {
//                         "type": "view",
//                         "name": "来来拼十",
//                         "url": "http://dn.lailaipk.com/"
//                     },
//                     {
//                        "type": "view",
//                        "name": "港城麻将",
//                        "url": "http://nb.618mj.com/"
//                     }
//                 ]
//             },
//             {
//                 "name": "招募代理",
//                 "sub_button": [
//                     {
//                         "type": "view",
//                         "name": "盈利模式",
//                         "url": "http://mp.weixin.qq.com/s/uVYAcqIfLbiIcT0dDfzf7g"
//                     },
//                     {
//                         "type": "view",
//                         "name": "绑定代理",
//                         "url": "http://lai1ba.com/wxcode?type=3&gid=152"
//                     },
//                     {
//                        "type": "view",
//                        "name": "代理注册",
//                        "url": "http://lai1ba.com/wxcode?type=1&gid=152"
//                     },
//                     {
//                         "type": "view",
//                         "name": "代理登入",
//                         "url": "http://lai1ba.com/wxcode?type=3&gid=152"
//                     },
//                     {
//                         "type": "view",
//                         "name": "账号密码登录",
//                         "url": "http://nb.618mj.com/mobile/wx_login"
//                     }
//                 ]
//             },
//             {
//                 "name": "联系我们",
//                 "sub_button": [
//                     {
//                         "type": "view",
//                         "name": "购买房卡",
//                         "url": "http://lai1ba.com/wxcode?type=2&gid=152"
//                     },
//                     {
//                        "type": "view",
//                        "name": "常见问题",
//                        "url": "http://mp.weixin.qq.com/s/lMXf19TVoTkT_0KYM87Obg"
//                     },
//                     {
//                         "type": "click",
//                         "name": "联系客服",
//                         "key": "statement"
//                     },
//                     {
//                         "type": "view",
//                         "name": "注册指南",
//                         "url": "https://mp.weixin.qq.com/s/cc-4XN5BvzEH0o6d1kbDqg"
//                     }
//                 ]
//             },
//         }';

//                $data = '
//                {
//                     "button": [
//                     {
//                         "type": "view",
//                         "name": "游戏下载",
//                         "url": "http://zs.618mj.com/"
//                     },
//                     {
//                         "name": "招募代理",
//                         "sub_button": [
//                             {
//                                "type": "click",
//                                "name": "推广活动",
//                                "key": "active"
//                             },
//                             {
//                                "type": "view",
//                                "name": "代理绑定",
//                                "url": "http://lai1ba.com/wxcode?type=3&gid=128"
//                             },
//                             {
//                                 "type": "view",
//                                 "name": "代理登入",
//                                 "url": "http://lai1ba.com/wxcode?type=3&gid=128"
//                             }
//                         ]
//                     },
//                     {
//                         "name": "联系我们",
//                         "sub_button": [
//                             {
//                                 "type": "view",
//                                 "name": "购买房卡",
//                                 "url": "http://lai1ba.com/wxcode?type=2&gid=128"
//                             },
//                             {
//                                 "type": "click",
//                                 "name": "联系客服",
//                                 "key": "statement"
//                             }
//                         ]
//                     },
//                 }';



        $data = '{
                "button":[
                    {
                        "name":"游戏下载",
                        "sub_button":[
                            {"type":"view","name":"游戏下载","url":"http://lai1ba.com/share/131/32696","sub_button":[]},
                            {"type":"view","name":"新手引导","url":"https://mp.weixin.qq.com/s?__biz=MzI2MjYyMDcwMA==&mid=2247483672&idx=1&sn=94ed97eebd08d868e7a4bc9ce98ba99b&chksm=ea49126bdd3e9b7d9cbe31593a51bff434b03c4f7ae40406715055daa019f712277d0e75aaca&scene=0&key=224de3a0d1eb57349c5bcd74cd713129c4e24171b3c9e8b6b0ef5f3bb6572300f2c90c7e7ab72ac862f609a690e73449235009e8be414e5ae7ec96a0aa62e6acd325d70def21a3ff0c8cb069816d8e93&ascene=0&uin=MjI0MzkzOTM2NA%3D%3D&devicetype=iMac+MacBookPro12%2C1+OSX+OSX+10.12.3+build(16D32)&version=11020201&pass_ticket=KmKeLI9cwSVpsoqUfovXgt%2F3Es3wY5%2B3%2BG%2FsYRBWfyphokZsin7cRRs%2Bxr0MXo","sub_button":[]},
                            {"type":"click","name":"人工客服","key":"service","sub_button":[]},
                            {"type":"click","name":"官方声明","key":"statement","sub_button":[]}
                        ]
                    },
                    {
                        "name":"游戏充值",
                        "sub_button":[
                            {"type":"view","name":"购买钻石","url":"http://lai1ba.com/wxcode?type=2","sub_button":[]}
                        ]
                    },
                    {
                        "name":"推广后台",
                        "sub_button":[
                            {"type":"view","name":"推广员后台","url":"http://lai1ba.com/wxcode?type=3","sub_button":[]},
                            {"type":"view","name":"申请推广员","url":"http://lai1ba.com/wxcode?type=1","sub_button":[]}
                        ]
                    }
                ]
            }';
        $response = Helper_Request::curlPost($url,$data);
        var_dump( $response );
    }

//,


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
