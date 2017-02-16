<?php

class Admin_Menu extends Data {

    const TABLE_MENU = 'frame_menu';
    const TABLE_FUNCTION = 'frame_function';
    private static $_singletonExtendObjects = NULL;

    public static function getInstance() {
        if ( self::$_singletonExtendObjects === NULL ) {
            self::$_singletonExtendObjects = new self();
        }
        return self::$_singletonExtendObjects;
    }

    public function getMenu(){
        $sql = "select * from frame_menu as a INNER JOIN frame_function as b ON a.menu_id = b.menu_id ORDER BY a.menu_order ASC ,b.func_order ASC";
        $data = $this->_getDb()->fetchArray($sql);
        $menuList = array();
        $locale = Common::getLocale();
        foreach ($data as $list){
            $menuList[$list['menu_id']]['menu_id'] = $list['menu_id'];
            $menuList[$list['menu_id']]['name'] = $list['name_'.$locale];
            $menuList[$list['menu_id']]['icon'] = $list['menu_icon'];
            $menuList[$list['menu_id']]['child'][$list['func_id']]['func_id'] = $list['func_id'];
            $menuList[$list['menu_id']]['child'][$list['func_id']]['name'] = $list['func_name_'.$locale];
            $menuList[$list['menu_id']]['child'][$list['func_id']]['icon'] = $list['func_icon'];
            $menuList[$list['menu_id']]['child'][$list['func_id']]['func_state'] = $list['func_state'];
            $menuList[$list['menu_id']]['child'][$list['func_id']]['url'] = $list['func_url'];
        }
        return $menuList;
    }

}