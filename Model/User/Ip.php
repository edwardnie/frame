<?php

class User_Ip extends Data {

    const TABLE_NAME = 'frame_limit_ip';
    private static $_singletonExtendObjects = NULL;

    public static function getInstance() {
        if ( self::$_singletonExtendObjects === NULL ) {
            self::$_singletonExtendObjects = new self();
        }
        return self::$_singletonExtendObjects;
    }

    public function getIpList(){
        $data = $this->_getCache()->get('cache_frame_limit_ip');
        if(empty($data)){
            $sql = Helper_SQLBuilder::buildSelectSQL(self::TABLE_NAME);
            $rs = $this->_getDb()->fetchArray($sql);
            if($rs){
                foreach ($rs as $list){
                    $data[] = $list['ip'];
                }
                $this->_getCache()->set('cache_frame_limit_ip',$data,86400);
            }
        }
        return $data;
    }
}