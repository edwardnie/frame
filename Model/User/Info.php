<?php

class User_Info extends Data {

    const TABLE_NAME = 'frame_user';
    private static $_singletonExtendObjects = NULL;

    public static function getInstance() {
        if ( self::$_singletonExtendObjects === NULL ) {
            self::$_singletonExtendObjects = new self();
        }
        return self::$_singletonExtendObjects;
    }

    public function getUserByID($uid) {
        $uid = intval($uid);
        if(empty($uid)) return array();
        $data = $this->_getCache()->get( 'cache_frame_user_'.$uid );
        if ( empty( $data ) ) {
            $sql = Helper_SQLBuilder::buildSelectSQL(self::TABLE_NAME,array(),array('uid' => $uid));
            $data = $this->_getDb()->fetchOneAssoc($sql);
            $this->_getCache()->set( 'cache_frame_user_'.$uid, $data, 600 );
        }
        return $data;
    }

    public function getUserByName($username) {
        if(empty($username)) return array();
        $data = $this->_getCache()->get( 'cache_frame_user_'.$username );
        if ( empty( $data ) ) {
            $sql = Helper_SQLBuilder::buildSelectSQL(self::TABLE_NAME,array(),array('username' => $username));
            $data = $this->_getDb()->fetchOneAssoc($sql);
            $this->_getCache()->set( 'cache_frame_user_'.$username, $data, 600 );
        }
        return $data;
    }

    public function updateUserInfo($update,$condition){
        if(!is_array($condition)||!is_array($condition)){return false;}
        $sql = Helper_SQLBuilder::buildUpdateSQL(self::TABLE_NAME,$update,$condition);
        $this->_getDb()->query($sql);
        $this->_getCache()->delete('cache_frame_user_'.$condition['uid']);
        return true;
    }

    public function insertUser($data){
        $sql = Helper_SQLBuilder::buildInsertSQL(self::TABLE_NAME,$data);
        $this->_getDb()->query($sql);
        return true;
    }

    public function deleteUser($uid){
        $uid = intval($uid);
        if($uid){
            $sql = Helper_SQLBuilder::buildDeleteSQL(self::TABLE_NAME,array('uid' => $uid));
            $this->_getDb()->query($sql);
        }
        return false;
    }

    public function getUserList($condition = array()){
        $sql = Helper_SQLBuilder::buildSelectSQL(self::TABLE_NAME,array(),$condition);
        return $this->_getDb()->fetchArray($sql);
    }

}