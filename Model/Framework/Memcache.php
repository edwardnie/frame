<?php

class Framework_Memcache {

    public $flag = MEMCACHE_COMPRESSED; //默认Flag
    private $expire = 0; //默认有效期(无限期)
    private $_cacheInstance;

    public function __construct( $config ) {
        if(MEMCACHE_SWITCH){
            $this->_cacheInstance = memcache_connect( $config['host'], $config['port'] );
        }else{
            $this->_cacheInstance = NULL;
        }

    }

    public function add( $key, $val, $expire = 0, $flag = 0 ) {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        $expire = ( $expire > 0 ) ? $expire : $this->expire;
        $flag = $flag ? MEMCACHE_COMPRESSED : 0;
        return memcache_add( $this->_cacheInstance, $key, $val, $flag, $expire );
    }

    public function set( $key, $val, $expire = 0, $flag = 0 ) {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        $expire = ( $expire > 0 ) ? $expire : $this->expire;
        $flag = $flag ? MEMCACHE_COMPRESSED : 0;
        return memcache_set( $this->_cacheInstance, $key, $val, $flag, $expire );
    }

    public function replace( $key, $val, $expire = 0, $flag = 0 ) {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        $expire = ( $expire > 0 ) ? $expire : $this->expire;
        $flag = $flag ? MEMCACHE_COMPRESSED : 0;
        return memcache_replace( $this->_cacheInstance, $key, $val, $flag, $expire );
    }

    public function get( $key, $flag = 0 ) {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        $flag = $flag ? MEMCACHE_COMPRESSED : 0;
        return memcache_get( $this->_cacheInstance, $key, $flag );
    }

    public function delete( $key ) {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        return memcache_delete( $this->_cacheInstance, $key );
    }

    public function decrement( $key, $value = 1 ) {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        return memcache_decrement( $this->_cacheInstance, $key, $value );
    }

    public function increment( $key, $value = 1 ) {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        return memcache_increment( $this->_cacheInstance, $key, $value );
    }

    public function close() {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        return memcache_close( $this->_cacheInstance );
    }

    public function flush() {
        if(!MEMCACHE_SWITCH){
            return false;
        }
        return memcache_flush( $this->_cacheInstance );
    }


}
