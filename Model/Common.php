<?php

/*
 * 通用功能类
 * */

class Common {

    /**
     * 普通数据引擎
     * var array()
     */
    private static $_dbNormalEngine = array();

    private static $_cacheNormalEngine = array();

    /**
     * 解除转义
     * @param        mixed $var
     * @return        mixed
     */
    public static function prepareGPCData(& $var) {
        if (is_array($var)) {
            while ((list($key, $val) = each($var)) != null) {
                $var[$key] = self::prepareGPCData($val);
            }
        } else {
            $var = stripslashes($var);
        }

        return $var;
    }

    /**
     * 初始化显示执行错误信息
     */
    public static function initShowErrorMessage() {
        if (DEBUG) {
            self::openErrorMessage();
        } else {
            self::closeErrorMessage();
        }
    }

    /**
     * 打开执行错误输出
     */
    public static function openErrorMessage() {
        error_reporting(E_ALL ^ E_NOTICE);
        ini_set('display_errors', 'On');
    }

    /**
     * 关闭执行错误输出
     */
    public static function closeErrorMessage() {
        error_reporting(0);
        ini_set('display_errors', 'Off');
    }

    /**
     * 注册自动加载文件类
     */
    public static function registerAutoLoad() {
        spl_autoload_register(array('Common', 'autoLoad'));
    }

    /**
     *自动加载类文件
     * @param string $className
     */
    public static function autoLoad($className) {
        $className = str_replace('_', '/', $className);
        if (file_exists(MOD_DIR . "{$className}.php")) {
            include(MOD_DIR . "{$className}.php");
            return;
        }
        if (file_exists(CON_DIR . "{$className}.php")) {
            include(CON_DIR . "{$className}.php");
            return;
        }
    }

    /**
     *获取配置文件内容
     * @param string $configKey 配置文件索引值
     * @return array
     * */
    protected static function & getConfigFile($configKey) {
        $config = array();
        if (file_exists(CONFIG_DIR . "{$configKey}.php")) {
            $config = require CONFIG_DIR . "{$configKey}.php";
        }
        return $config;
    }

    /**
     * 获取系统配置信息
     * @param string $key 配置文件项
     * @return array
     */

    public static function & getConfig($key = '') {
        static $config = array();
        if (empty($config)) {
            //$config = self::getConfigFile( 'SystemConfig' );
            $config = array_merge_recursive(self::getConfigFile('SystemConfig'), self::getConfigFile("NormalConfig"));
        }
        if ($key) {
            return $config[$key];
        }
        return $config;
    }

    public static function getLocale(){
        $locale = Common::getCache()->get('locale');
        return $locale = $locale?$locale:'zh';
    }

    public static function getLang($key){
        $locale = self::getLocale();
        $config = array();
        if (file_exists(CONFIG_DIR . "Lang.php")) {
            $config = require CONFIG_DIR . "Lang.php";
        }
        return $config[$locale][$key];
    }


    /**
     * 获取普通数据的数据库引擎
     * @param string $dbKey 数据库的键
     * @return Framework_MysqlDb
     */
    public static function & getDatabaseEngine($dbKey = 'data') {
        if (!isset(self::$_dbNormalEngine[$dbKey])) {
            $dbConfig = Common::getConfig('mysqlDb');
            self::$_dbNormalEngine[$dbKey] = new Framework_MysqlDb($dbConfig[$dbKey]);
        }
        return self::$_dbNormalEngine[$dbKey];
    }


    /**
     *
     * 获取Cache实例
     * @param string $data
     * @return array|Framework_Memcache
     */
    public static function & getCache($data = 'data') {
        static $cache = array();
        if (empty($cache)) {
            $config = self::getConfig('memcache');
            $memConfig = $config[$data];
            self::$_cacheNormalEngine = new Framework_Memcache($memConfig);
        }
        return self::$_cacheNormalEngine;
    }

    public static function dbQuery($sql, $dbKey = 'data') {
        $db = Common::getDatabaseEngine($dbKey);
        return $db->query($sql);
//        return true;
    }

    public static function dbFetchArray($sql, $dbKey = 'data') {
        $db = Common::getDatabaseEngine($dbKey);
        return $db->fetchArray($sql);
    }

    public static function dbFetchOneAssoc($sql, $dbKey = 'data') {
        $db = Common::getDatabaseEngine($dbKey);
        return $db->fetchOneAssoc($sql);
    }

    public static function dbFetchCount($sql, $dbKey = 'data') {
        $db = Common::getDatabaseEngine($dbKey);
        $data = $db->fetchOneAssoc($sql);
        return $data['count'];
    }

    public static function clearCacheByKey($key) {
        return Common::getCache()->delete($key);

    }

    public static function factory($class) {
        return new $class;
    }

    /**
     * 进程锁
     * @param $key
     * @param int $value
     * @param int $timeout
     * @throws Exception
     */
    public static function lock($key, $value = 1, $timeout = 5) {
        $cacheInstance = self::getCache();
        if (!$cacheInstance->add($key . "_lock", $value, $timeout)) {
            throw new Exception();
        }
    }

    /**
     * 删除进程锁
     * @param string $key
     */
    public static function unLock($key) {
        $cacheInstance = self::getCache();
        $cacheInstance->delete($key . "_lock");
    }
}
