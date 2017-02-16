<?php

class UserController extends Framework_BaseController {

    public function login() {
        if ( Helper_Login::loginState() ) {
            header( 'Location:' . Helper_Url::getUrl( 'user.userInfo' ) );
            exit;
        }
        $this->disPlayPage( 'web/login.php' );
    }


    public function loginBox() {
        $this->disPlayPage( 'web/login_box.php' );
    }

    public function logout() {
        setcookie( 'PHPWEBCOOKIE', '', time() - 3600 );
        setcookie( 'PHPSESSIONWEBID', '', time() - 3600 );
        session_destroy();
        if($_REQUEST['logout_type']){
            header( 'Location:' . Helper_Url::getUrl( 'houseMobile.home' ) );
        }   else
        header( 'Location:' . Helper_Url::getUrl( 'user.login' ) );
        exit;
    }

    public function userInfo() {
        unset( $_SESSION['pre_url'] );
        if ( $this->inputDatas['sub'] == 'delete' ) {
            $checked = $this->inputDatas['checkbox'];
            User_Collect::getInstance()->deleteCollectCheck( $checked );
        }
        $limit = 4;
        $page = intval( $this->inputDatas['page'] ) ? intval( $this->inputDatas['page'] ) : 1;
        if ( $page - 1 < 0 ) {
            $n = 0;
        } else {
            $n = $page - 1;
        }
        $start = $n * $limit;
        $collect = User_Collect::getInstance()->getHouseCollectList( array(), $start, $limit );
        $houseList = array();
        foreach ( $collect as $k => $list ) {
            $houseList[$k] = House_Data::getInstance()->getHouseData( $list['house_id'] );
            $houseList[$k]['collect_id'] = $list['id'];
            House_Data::exchangeRoom( $houseList, $k );
        }
        $total = User_Collect::getInstance()->getHouseCollectNum();
        $pageString = Helper_Page::getpage( Helper_Url::getUrl( 'user.userInfo', $this->inputDatas ), $limit, $total, $page );
        $this->assignVariable( 'pageString', $pageString );
        $this->assignVariable( 'houseList', $houseList );
        $this->assignVariable( 'total', $total );
        $this->disPlayPage( 'web/my_collect.php' );
    }

    public function villageCollect() {
        if ( $this->inputDatas['sub'] == 'delete' ) {
            $checked = $this->inputDatas['checkbox'];
            User_Collect::getInstance()->deleteCollectCheck( $checked );
        }
        $limit = 4;
        $page = intval( $this->inputDatas['page'] ) ? intval( $this->inputDatas['page'] ) : 1;
        if ( $page - 1 < 0 ) {
            $n = 0;
        } else {
            $n = $page - 1;
        }
        $start = $n * $limit;
        $collect = User_Collect::getInstance()->getVillageCollectList( array(), $start, $limit );
        $houseList = array();
        foreach ( $collect as $k => $list ) {
            $houseList[$k] = Village_Data::getInstance()->getVillageData( $list['village_id'] );
            $houseList[$k]['collect_id'] = $list['id'];
        }
        $total = User_Collect::getInstance()->getVillageCollectNum();
        $pageString = Helper_Page::getpage( Helper_Url::getUrl( 'user.villageCollect', $this->inputDatas ), $limit, $total, $page );
        $this->assignVariable( 'pageString', $pageString );
        $this->assignVariable( 'houseList', $houseList );
        $this->assignVariable( 'total', $total );
        $this->disPlayPage( 'web/village_collect.php' );
    }

    public function faq() {
        if ( $this->inputDatas['sub'] == 'delete' ) {
            $checked = $this->inputDatas['checkbox'];
            User_Faq::getInstance()->deleteFaq( $checked );
        }
        $limit = 10;
        $page = intval( $this->inputDatas['page'] ) ? intval( $this->inputDatas['page'] ) : 1;
        if ( $page - 1 < 0 ) {
            $n = 0;
        } else {
            $n = $page - 1;
        }
        $start = $n * $limit;
        $condition = array( 'uid' => Helper_Login::getUserInfo( 'admin_uid' ) );
        $houseList = User_Faq::getInstance()->getList( $condition, $start, $limit );
        $total = User_Faq::getInstance()->getListNum( $condition );
        $pageString = Helper_Page::getpage( Helper_Url::getUrl( 'user.faq', $this->inputDatas ), $limit, $total, $page );
        $this->assignVariable( 'pageString', $pageString );
        $this->assignVariable( 'houseList', $houseList );
        $this->assignVariable( 'total', $total );
        $this->disPlayPage( 'web/my_faq.php' );
    }

    public function myHouse() {
        //Helper_Login::checkWebLogin();
        if ( $this->inputDatas['sub'] == 'delete' ) {
            //            $checked = $this->inputDatas['checkbox'];
            //            User_Collect::getInstance()->deleteCollectCheck( $checked );
        }
        $limit = 4;
        $page = intval( $this->inputDatas['page'] ) ? intval( $this->inputDatas['page'] ) : 1;
        if ( $page - 1 < 0 ) {
            $n = 0;
        } else {
            $n = $page - 1;
        }
        $start = $n * $limit;
        $condition['tel'] = Helper_Login::getUserInfo( 'tel' );
        if ( $condition['tel'] ) {
            $data = House_Data::getInstance()->getHouseList( $condition, $start, $limit );
            foreach ( $data as $k => $v ) {
                $data[$k] = House_Data::getInstance()->getHouseData( $v['house_id'] );
            }
            $total = House_Data::getInstance()->getHouseNum( $condition );
        }

        $this->assignVariable( 'dataList', $data );
        $params = $this->inputDatas;
        unset( $params['page'] );
        $pageString = Helper_Page::getpage( Helper_Url::getUrl( '', $params ), $limit, $total, $page );
        $this->assignVariable( 'pageString', $pageString );
        $this->assignVariable( 'houseList', $data );
        $this->assignVariable( 'total', $total );
        $this->disPlayPage( 'web/my_house.php' );
    }

    /**
     * 预约看房
     */
    public function showList() {
        //Helper_Login::checkPermission();
        $limit = 4;
        $page = intval( $this->inputDatas['page'] ) ? intval( $this->inputDatas['page'] ) : 1;
        if ( $page - 1 < 0 ) {
            $n = 0;
        } else {
            $n = $page - 1;
        }
        $condition = array();
        $start = $n * $limit;
        if ( Helper_Login::getUserInfo( 'tel' ) ) {
            $condition['name'] = Helper_Login::getUserInfo( 'tel' );
            $data = User_Order::getInstance()->getOrderList( $condition, $start, $limit );
            $total = User_Order::getInstance()->getOrderNum( $condition );
            foreach ( $data as $k => $v ) {
                $time = $data[$k]['created'];
                $data[$k] = House_Data::getInstance()->getHouseData( $v['house_id'] );
                $data[$k]['o_time'] = $time;
            }
            $params = $this->inputDatas;
            $pageString = Helper_Page::getpage( Helper_Url::getUrl( 'user.showList', $params ), $limit, $total, $page );
        }
        $this->assignVariable( 'dataList', $data );
        $this->assignVariable( 'pageString', $pageString );
        $this->assignVariable( 'total', $total );
        $this->disPlayPage( 'web/my_show.php' );
    }


}
