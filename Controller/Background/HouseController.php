<?php

class HouseController extends Framework_BaseController {

    /**
     * @param house_id int
     * @return array
     */
    public function addConnect() {
        if ( empty( $this->inputDatas['house_id'] ) || intval( $this->inputDatas['house_id'] ) < 1 ) {
            return array( 'state' => 0 );
        }
        $userInfo = Helper_Login::getUserInfo();
        $data = array(
            'user_id' => $userInfo['admin_uid'],
            'house_id' => $this->inputDatas['house_id'],
            'type' => 1,
            'created' => time()
        );
        User_Collect::getInstance()->addCollect( $data );
        return array( 'state' => 1 );
    }


    /**
     * @param house_id int
     * @return array
     */
    public function deleteConnect() {
        if ( empty( $this->inputDatas['house_id'] ) || intval( $this->inputDatas['house_id'] ) < 1 ) {
            return array( 'state' => 0 );
        }
        $userInfo = Helper_Login::getUserInfo();
        $data = array(
            'user_id' => $userInfo['admin_uid'],
            'house_id' => $this->inputDatas['house_id'],
        );
        User_Collect::getInstance()->deleteCollect( $data );
        return array( 'state' => 1 );
    }

    public function addHouseQ() {
        $request = $this->inputDatas;
        $hashData = $request['hash_data'];
        unset( $request['hash_data'] );
        $content = $this->inputDatas['content'];
        if ( Helper_Url::checkSign( $request, $hashData ) ) {
            House_Question::getInstance()->addQ( array( 'ip' => Helper_Tools::getCurrentIP(), 'house_id' => $this->inputDatas['house_id'], 'content' => $content, 'created' => time() ) );
            return array( 'state' => 1 );
        }
        return array( 'state' => 0 );
    }

    /**
     *  前端登盘审核
     */
    public function checkPassport() {
        Helper_Login::checkLogin();
        $houseId = $this->inputDatas['house_id'];
        $check = $this->inputDatas['check'];
        House_Data::getInstance()->updateHouse( array( 'check' => $check ), $houseId );
        return array( 'state' => 1, 'msg' => ( $check == 1 ) ? '通过审核' : '未通知审核' );
    }

    public function deleteHouseQ() {
        Helper_Login::checkLogin();
        House_Question::getInstance()->deleteQ( array( 'hq_id' => $this->inputDatas['id'] ) );
        return array( 'state' => 1 );
    }

    /**
     * 房源置顶
     */
    public function houseUp() {
        Helper_Login::checkLogin();
        $houseId = $this->inputDatas['house_id'];
//        $houseOrder = Config::getInstance()->getConfigByName('house_order_num');
        House_Data::getInstance()->updateHouse(array('order_num' => 1000),$houseId);
//        Config::getInstance()->modifyConfigNumber('house_order_num');
        return array( 'state' => 1 );
    }

    public function deleteCheckHouse(){
        Helper_Login::checkLogin();
        $ids = $this->inputDatas['vids'];
        if($ids){
            $in = "(".implode(',',$ids).")";
            $sql = "delete from house where house_id IN {$in}";
            Common::dbQuery($sql);
        }

    }
} 