<?php

class UserController extends AdminController {

    //用户列表
    public function userList() {
        $data = User_Info::getInstance()->getUserList();
        $data = $this->_formatUserData( $data );
        $this->assignVariable( 'data', $data );
        $this->disPlayPage( 'user/user' );
    }

    //用户的增改操作
    public function userOperate() {
        $uid = $this->inputDatas['uid'];
        $userData = User_Info::getInstance()->getUserByID( $uid );
        $submit = $this->inputDatas['sub'];
        if ( $submit ) {
            $data = $this->inputDatas['data'];
            if ( $userData ) {
                if ( $data['password'] ) {
                    $data['password'] = md5( $data['password'] . $userData['pass_key'] );
                } else {
                    unset( $data['password'] );
                }
                User_Info::getInstance()->updateUserInfo( $data, array( 'uid' => $uid ) );
            } else {
                $data['pass_key'] = rand( 1000, 9999 );
                $data['password'] = md5( $data['password'] . $data['pass_key'] );
                User_Info::getInstance()->insertUser( $data );
            }
            echo "<script> parent.hideBox('addBox');</script>";
            exit();
        }
        $userRole = User_Role::getInstance()->getRoleList();
        $this->assignVariable( 'userData', $userData );
        $this->assignVariable( 'userRole', $userRole );
        $this->assignVariable( 'title', $userData ? '修改用户' : '添加用户' );
        $this->disPlayPage( 'user/user_op' );
    }

    public function deleteUser() {

    }

    //角色列表
    public function roleList() {
        $data = User_Role::getInstance()->getRoleList();
        $this->assignVariable( 'data', $data );
        $this->disPlayPage( 'user/user_role' );
    }

    ////角色的增改操作
    public function roleOperate() {
        $roleId = $this->inputDatas['role_id'];
        $roleData = User_Role::getInstance()->getRoleById( $roleId );
        $submit = $this->inputDatas['sub'];
        if ( $submit ) {
            $data = array(
                'role_name_en' => $this->inputDatas['role_name_en'],
                'role_name_zh' => $this->inputDatas['role_name_zh'],
                'role_functions' => json_encode( $this->inputDatas['data'] )
            );
            if ( $roleData ) {
                User_Role::getInstance()->updateRole( $roleId, $data );
            } else {
                User_Role::getInstance()->insertRole( $data );
            }
            echo "<script> parent.hideBox('addBox');</script>";
            exit();
        }
        $userRole = User_Role::getInstance()->getRoleList();
        $menuList = Admin_Menu::getInstance()->getMenu();
        $this->assignVariable( 'roleData', $roleData );
        $this->assignVariable( 'userRole', $userRole );
        $this->assignVariable( 'menuList', $menuList );
        $this->assignVariable( 'title', $roleData ? '修改角色' : '添加角色' );
        $this->disPlayPage( 'user/role_op' );
    }

    private function _formatUserData( $data = array() ) {
        $formatData = array();
        $userRoles = User_Role::getInstance()->getRoleList();
        foreach ( $data as $list ) {
            $list['role_name'] = $userRoles[$list['role_id']]['role_name_' . $this->locale];
            $formatData[$list['role_id']]['role_name'] = $userRoles[$list['role_id']]['role_name_' . $this->locale];
            $formatData[$list['role_id']]['child'][] = $list;
        }
        return $formatData;
    }


}