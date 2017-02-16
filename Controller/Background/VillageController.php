<?php

class VillageController extends Framework_BaseController {

    /**
     * @param village_id int
     * @return array
     */
    public function addConnect() {
        if ( empty( $this->inputDatas['village_id'] ) || intval( $this->inputDatas['village_id'] ) < 1 ) {
            return array( 'state' => 0 );
        }
        $userInfo = Helper_Login::getUserInfo();
        $data = array(
            'user_id' => $userInfo['admin_uid'],
            'village_id' => $this->inputDatas['village_id'],
            'type' => 2,
            'created' => time()
        );
        User_Collect::getInstance()->addCollect( $data );
        return array( 'state' => 1 );
    }


    /**
     * @param village_id int
     * @return array
     */
    public function deleteConnect() {
        if ( empty( $this->inputDatas['village_id'] ) || intval( $this->inputDatas['village_id'] ) < 1 ) {
            return array( 'state' => 0 );
        }
        $userInfo = Helper_Login::getUserInfo();
        $data = array(
            'user_id' => $userInfo['admin_uid'],
            'village_id' => $this->inputDatas['village_id'],
        );
        User_Collect::getInstance()->deleteCollect( $data );
        return array( 'state' => 1 );
    }


    public function getDongSelect() {
        $dong = Village_Dong::getInstance()->getDongData( $this->inputDatas['village_id'] );
        $data = array();
        $name = $this->inputDatas['dong'];
        if ( preg_match_all( "/[\x7f-\xff]+/", $name ) == 0 ) {
            $name = $name . '号';
        }
        foreach ( $dong as $list ) {
            if ( $list['dong_address'] == $this->inputDatas['dong_address'] && strstr( $list['name'], $name ) ) {
                $data[] = $list;
            }
        }
        $data = Helper_Math::arraySort( $data, 'name', SORT_ASC, SORT_NUMERIC );
        return $data;
    }


    public function getRoomSelect() {
        $room = Village_Room::getInstance()->getRoomData( $this->inputDatas['village_id'], $this->inputDatas['dong_id'] );
        $name = $this->inputDatas['room_number'];
        $data = array();
        foreach ( $room as $list ) {
            //            var_dump($name,$list['name'],preg_match_all( "/^$name/", $list['name'] ));
            //            preg_match_all( "/^$name/", $list['name'], $arr);
            //            var_dump($arr);
            if ( preg_match( "/^$name/", $list['name'] ) ) {
                $data[] = $list;
            }
        }
        //        var_dump($data);
        return $data;
    }


    public function setHouseLocale() {
        if ( empty( $this->inputDatas['village_id'] ) || intval( $this->inputDatas['house_locale'] ) < 1 ) {
            return array( 'state' => 0 );
        }
        Village_Data::getInstance()->updateVillage( array( 'house_locale' => $this->inputDatas['house_locale'] ), $this->inputDatas['village_id'] );
        return array( 'state' => 1 );
    }

} 