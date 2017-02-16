<?php
class Framework_Memcached {

    private $expire = 0; //默认有效期(无限期)
    private $_cacheInstance;

    public function __construct( $config ) {
        $this->_cacheInstance = new Memcached();
        $this->_cacheInstance->addServer( $config['host'], $config['port'] );
    }

    public function add( $key, $val, $expire = 0 ) {
        $expire = ( $expire > 0 ) ? $expire : $this->expire;
        return $this->_cacheInstance->add( $key, $val, $expire );
    }

    public function set( $key, $val, $expire = 0 ) {
        $expire = ( $expire > 0 ) ? $expire : $this->expire;
        return $this->_cacheInstance->set( $key, $val, $expire );
    }

    public function replace( $key, $val, $expire = 0 ) {
        $expire = ( $expire > 0 ) ? $expire : $this->expire;
        return $this->_cacheInstance->replace( $key, $val, $expire );
    }

    public function get( $key ) {
        return $this->_cacheInstance->get( $key );
    }

    public function delete( $key ) {
        return $this->_cacheInstance->delete( $key );
    }

    public function decrement( $key, $value = 1 ) {
        return $this->_cacheInstance->decrement( $key, $value );
    }

    public function increment( $key, $value = 1 ) {
        return $this->_cacheInstance->increment( $key, $value );
    }
}
