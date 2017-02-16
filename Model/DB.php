<?php

class DB {
    public static function add( $table, $data, $dbKey = 'data' ) {
        if ( !is_array( $data ) )
            return false;
        $db = Common::getDatabaseEngine( $dbKey );
        $sql = Helper_SQLBuilder::buildInsertSQL( $table, $data );
        $db->query( $sql );
        return $db->insertId();
    }

    public function delete( $table, $conditions, $dbKey = 'data' ) {
        if ( !is_array( $conditions ) )
            return false;
        $db = Common::getDatabaseEngine( $dbKey );
        $sql = Helper_SQLBuilder::buildDeleteSQL( $table, $conditions );
        $db->query( $sql );
        return $db->affectedRows();
    }

    public function update($table, $data, $conditions , $dbKey = 'data') {
        if ( !is_array( $conditions ) )
            return false;
        $db = Common::getDatabaseEngine( $dbKey );
        $sql = Helper_SQLBuilder::buildUpdateSQL( $table, $data, $conditions );
        $db->query( $sql );
        return $db->affectedRows();
    }

    public function getList($table, $conditions, $fields = array(), $limit = array(), $sort = array(), $dbKey = 'data' ) {
        if ( !is_array( $conditions ) )
            return false;
        $db = Common::getDatabaseEngine( $dbKey );
        $sql = Helper_SQLBuilder::buildSelectSQL( $table, $fields, $conditions, $sort, $limit );
        return $db->fetchArray( $sql );
    }

    public function getOne($table, $conditions, $fields = array(), $limit = array(), $sort = array(),$dbKey = 'data' ) {
        if ( !is_array( $conditions ) )
            return false;
        $db = Common::getDatabaseEngine( $dbKey );
        $sql = Helper_SQLBuilder::buildSelectSQL( $table, $fields, $conditions, $sort, $limit );
        return $db->fetchOneAssoc( $sql );
    }

}