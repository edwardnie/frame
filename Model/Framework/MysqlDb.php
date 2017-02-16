<?php

class Framework_mysqlDb {

    protected $db;

    public function __construct( $config ) {
        $this->db = mysqli_connect( "{$config['host']}", $config['user'], $config['passwd'], $config['name'], $config['port'] );
        if ( mysqli_connect_errno() ) {
            throw new Exception();
        } else {
            mysqli_set_charset( $this->db, 'utf8' );
            $this->checkErr();
            return $this->db;
        }
    }

    public function checkErr() {
        $errorno = mysqli_errno( $this->db );
        if ( $errorno > 0 ) {
            if(DEBUG){
                var_dump(mysqli_error( $this->db ), $errorno);
            }
            throw new Exception( mysqli_error( $this->db ), $errorno );
        }
    }

    public function query( $sql ) {
        $result = mysqli_query( $this->db, $sql );
        $this->checkErr();
        return $result;
    }

    public function fetchArray( $sql ) {
        $result = $this->query( $sql );
        return $this->res2Assoc( $result );

    }

    public function fetchAssoc( $result ) {
        return mysqli_fetch_assoc( $result );
    }


    public function fetchOneAssoc( $sql ) {
        $result = $this->query( $sql );
        return $this->fetchAssoc( $result );
    }

    public function fetchObject( $sql ) {
        $result = $this->query( $sql );
        return mysqli_fetch_object( $result );
    }

    public function & res2Assoc( & $res ) {
        $rows = array();
        while ( ( $row = $this->fetchAssoc( $res ) ) != null ) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function insertId() {
        return mysqli_insert_id( $this->db );
    }

    public function getCount( $tables, $condition = "" ) {
        $r = $this->fetchOneAssoc( "SELECT COUNT(*) AS `count` FROM {$tables} " . ( $condition ? " WHERE {$condition}" : "" ) );
        return $r['count'];
    }

    /*
     * 取得最近一次与 link_identifier 关联的 INSERT，UPDATE 或 DELETE 查询所影响的记录行数
     * */
    public function affectedRows() {
        return mysqli_affected_rows( $this->db );
    }

    /*
     * 事务处理
     * */
    public function startTransaction() {
        mysqli_query( "SET AUTOCOMMIT=0", $this->db ); //设置为不自动提交，因为mysqli默认立即执行
        mysqli_query( "START TRANSACTION", $this->db );
    }

    public function rollbackTransaction() {
        mysqli_query( "ROLLBACK", $this->db );
    }

    /**
     * 获取错误号码
     *
     * @return integer
     */
    public function getErrorNumber() {
        return mysqli_errno( $this->db );
    }

    /**
     * 获取错误消息
     *
     * @return string
     */
    public function getErrorMessage() {
        return mysqli_error( $this->db );
    }


}

?>
