<?php

class AdminController extends Framework_BaseController {

    public $loginUser;
    public $loginState = false;
    public $uid = 0;
    public $role;

    public function __construct() {
        parent::__construct();
        Helper_Login::checkLogin();
        //10以内的是后台用户
        $this->loginUser = Helper_Login::getUserInfo();
        $this->loginState = Helper_Login::loginState();
        $this->uid = $this->loginUser['admin_uid'];
        $this->role = User_Role::getInstance()->getRoleById($this->loginUser['admin_role_id']);
    }

    public function index(){
        $menuList = User_Role::getInstance()->getUserFunctions($this->role);
        $this->assignVariable('menuList',$menuList);
        $this->disPlayPage('index');
    }

    public function login(){
        if($this->loginState){
            $this->redirect(Helper_Url::getAdminUrl('admin.index'));
        }
        $this->disPlayPage('login');
    }


    public function show(){
        $this->disPlayPage('default');
    }

    public function test(){
        echo 123;
    }

    public function test1(){
        echo 456;
    }

    public function loginOut(){
        setcookie( 'PHPWEBCOOKIE', '', time() - 3600 );
        setcookie( 'PHPSESSIONWEBID', '', time() - 3600 );
        session_destroy();
        $this->disPlayPage( 'login.php' );
    }

}
