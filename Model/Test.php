<?php

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2016/1/15
 * Time: 17:21
 */
class Test extends Data {

    private static $_singletonObjects = NULL;

    public function __construct() {
        parent::__construct(TABLE_TEST);
    }

    /**
     * @return null|Test
     */
    public static function getInstance() {
        if (self::$_singletonObjects === NULL) {
            self::$_singletonObjects = new self(   );
        }
        return self::$_singletonObjects;
    }


    public function testS() {
        $sql = Helper_SQLBuilder::buildSelectSQL(TABLE_TEST);
        return $this->_getDb()->fetchArray($sql);
    }
}