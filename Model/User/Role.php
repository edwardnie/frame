<?php

class User_Role extends Data {

    const TABLE_NAME = 'frame_user_role';
    private static $_singletonExtendObjects = NULL;

    public static function getInstance() {
        if ( self::$_singletonExtendObjects === NULL ) {
            self::$_singletonExtendObjects = new self();
        }
        return self::$_singletonExtendObjects;
    }

    public function getUserFunctions($role){
        $menu = Admin_Menu::getInstance()->getMenu();
        $roleMenu = array();
        foreach ($role['data'] as $menuId => $list){
            $roleMenu[$menuId]['menu_id'] = $menu[$menuId]['menu_id'];
            $roleMenu[$menuId]['name'] = $menu[$menuId]['name'];
            $roleMenu[$menuId]['icon'] = $menu[$menuId]['icon'];
            foreach ($list as $funcId => $item){
                $roleMenu[$menuId]['child'][$funcId] = $menu[$menuId]['child'][$funcId];
            }
        }
        return $roleMenu;
    }


    public function checkFunction($role,$menuId,$funcId,$op){
        $funcOp = $role['data'][$menuId][$funcId];
        return in_array($op,$funcOp);
    }

    public function getRoleList(){
        $sql = Helper_SQLBuilder::buildSelectSQL(self::TABLE_NAME);
        $data = $this->_getDb()->fetchArray($sql);
        $roles = array();
        foreach ($data as $list){
            $roles[$list['role_id']] = $list;
        }
        return $roles;
    }

    public function getRoleById($roleId){
        if(empty($roleId)){
            return array();
        }
        $sql =Helper_SQLBuilder::buildSelectSQL(self::TABLE_NAME,array(),array('role_id' => $roleId));
        $data = $this->_getDb()->fetchOneAssoc($sql);
        if($data){$data['data'] = json_decode($data['role_functions'],true);}
        return $data?$data:array();
    }

    public function updateRole($roleId,$data){
        if(empty($roleId)|| empty($data)){
            return false;
        }
        $sql = Helper_SQLBuilder::buildUpdateSQL(self::TABLE_NAME,$data,array('role_id' => $roleId));
        $this->_getDb()->query($sql);
        return false;
    }

    public function insertRole($data){
        if(empty($data)){
            return false;
        }
        $sql = Helper_SQLBuilder::buildInsertSQL(self::TABLE_NAME,$data);
        $this->_getDb()->query($sql);
        return false;
    }
}