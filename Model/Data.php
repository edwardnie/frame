<?php

/**
 * 数据操作基类
 * Class Data
 */
class Data {
    private $_dbInstance = NULL;
    private $_cacheInstance = NULL;

    protected function _getDb() {
        if ( $this->_dbInstance == NULL ) {
            $this->_dbInstance = Common::getDatabaseEngine( 'data' );
        }
        return $this->_dbInstance;
    }

    protected function _getCache() {
        if ( $this->_cacheInstance == NULL ) {
            $this->_cacheInstance = Common::getCache( 'data' );
        }
        return $this->_cacheInstance;
    }
}