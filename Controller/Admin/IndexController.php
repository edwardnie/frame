<?php

class IndexController extends AdminController {

    public function getMenu(){
        $data = Admin_Menu::getInstance()->getMenu();
        var_dump($data);
    }
}