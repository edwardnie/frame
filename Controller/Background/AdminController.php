<?php

/**
 * Created by PhpStorm.
 * User: edward_nie
 * Date: 16/11/22
 * Time: 下午6:36
 */
class AdminController extends Framework_BaseController {

    public function deleteUser(){
        $uid = $this->inputDatas['uid'];
        User_Info::getInstance()->deleteUser($uid);
        return array('ret' => 0);
    }
}