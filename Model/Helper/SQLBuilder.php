<?php

class Helper_SQLBuilder {

    /**
     * 操作符（等号）
     * @var        int
     */
    const OPERATION_EQUAL = 1;

    /**
     * 操作符（不等号）
     * @var        int
     */
    const OPERATION_NOT_EQUAL = 2;

    /**
     * 操作符（小于号）
     * @var        int
     */
    const OPERATION_LESS_THAN = 3;

    /**
     * 操作符（小于等于号）
     * @var        int
     */
    const OPERATION_LESS_THAN_OR_EQUAL = 4;

    /**
     * 操作符（大于号）
     * @var        int
     */
    const OPERATION_MORE_THAN = 5;

    /**
     * 操作符（大于等于号）
     * @var        int
     */
    const OPERATION_MORE_THAN_OR_EQUAL = 6;

    /**
     * 排序（升序）
     * @var        int
     */
    const SORT_TYPE_ASC = 1;

    /**
     * 排序（降序）
     * @var        int
     */
    const SORT_TYPE_DESC = 2;

    /**
     * 建立搜索语句
     * @param        string $tableName 表名
     * @param        array $fields 字段名
     * @param        array $conditions 搜索条件
     *                                                                array(
     *                                                                        {$fieldName}:{$value}
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        {$fieldName}:array(
     *                                                                                operation:int
     *                                                                                value:mixed
     *                                                                        )
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        array(
     *                                                                                fieldName: string
     *                                                                                operation: int
     *                                                                                value: mixed
     *                                                                        )
     *                                                                )
     * @param        array $sortBy 排序
     *                                                        array(
     *                                                                {$fieldName}:int
     *                                                        )
     * @param        array $limit array(
     *                                                                count: int
     *                                                                startIndex: int
     *                                                        )
     * @return        string
     */
    public static function buildSelectSQL( $tableName, $fields = array(), $conditions = array(), $sortBy = array(), $limit = array() ) {
        return 'SELECT' . self::_buildReturnFields( $fields ) . 'FROM `' . $tableName . '`' . self::_buildWhere( $conditions ) . self::_buildSortBy( $sortBy ) . self::_buildLimit( $limit );
    }

    /**
     * 生成搜索记录数量的SQL
     * @param        string $tableName 表名
     * @param        array $conditions 搜索条件
     *                                                                array(
     *                                                                        {$fieldName}:{$value}
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        {$fieldName}:array(
     *                                                                                operation:int
     *                                                                                value:mixed
     *                                                                        )
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        array(
     *                                                                                fieldName: string
     *                                                                                operation: int
     *                                                                                value: mixed
     *                                                                        )
     *                                                                )
     */
    public static function buildSelectCountSQL( $tableName, $conditions = array() ) {
        return 'SELECT COUNT( * ) AS `count` FROM `' . $tableName . '`' . self::_buildWhere( $conditions );
    }

    /**
     * 生成插入语句
     * @param        string $tableName 表名
     * @param        array $datas 数据
     *                                                        array(
     *                                                                {$fieldName}:{$value}
     *                                                        )
     * @return        string
     */
    public static function buildInsertSQL( $tableName, $datas ) {
        $data = self::_buildFields( $datas );

        return 'INSERT INTO `' . $tableName . '` ' . $data['keys'] . ' VALUES ' . $data['values'];
    }

    /**
     * 生成更新语句
     * @param        string $tableName 表名
     * @param        array $datas 数据
     *                                                        array(
     *                                                                {$fieldName}:{$value}
     *                                                        )
     * @param        array $conditions 搜索条件
     *                                                                array(
     *                                                                        {$fieldName}:{$value}
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        {$fieldName}:array(
     *                                                                                operation:int
     *                                                                                value:mixed
     *                                                                        )
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        array(
     *                                                                                fieldName: string
     *                                                                                operation: int
     *                                                                                value: mixed
     *                                                                        )
     *                                                                )
     * @return        string
     */
    public static function buildUpdateSQL( $tableName, $datas, $conditions = array() ) {
        return "UPDATE `{$tableName}` SET " . self::_buildUpdateDataFields( $datas ) . self::_buildWhere( $conditions );
    }

    /**
     * 生成插入或者更新的SQL语句
     * @param        string $tableName 表名
     * @param        array $datas 数据
     *                                                        array(
     *                                                                {$fieldName}:{$value}
     *                                                        )
     */
    public static function buildInsertOrUpdateSQL( $tableName, $datas ) {
        $data = self::_buildFields( $datas );

        return 'INSERT INTO `' . $tableName . '` ' . $data['keys'] . ' VALUES ' . $data['values'] . ' ON DUPLICATE KEY UPDATE ' . self::_buildUpdateDataFields( $datas );
    }

    /**
     * 生成删除语句
     * @param        string $tableName 表名
     * @param        array $conditions 搜索条件
     *                                                                array(
     *                                                                        {$fieldName}:{$value}
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        {$fieldName}:array(
     *                                                                                operation:int
     *                                                                                value:mixed
     *                                                                        )
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        array(
     *                                                                                fieldName: string
     *                                                                                operation: int
     *                                                                                value: mixed
     *                                                                        )
     *                                                                )
     * @return        string
     */
    public static function buildDeleteSQL( $tableName, $conditions = array() ) {
        return "DELETE FROM `{$tableName}` " . self::_buildWhere( $conditions );
    }

    public static function buildWhere( $conditions = array() ) {
        return self::_buildWhere( $conditions );
    }

    /**
     * 转换为安全数据
     * @param        mixed $value 数据
     * @return        int|string
     */
    private static function _changeToSafeValue( $value ) {
        if ( is_numeric( $value ) ) {
            return $value;
        } else {
            return "'" . addslashes( $value ) . "'";
        }
    }

    /**
     * 生成查询语句的返回字段
     * @param        string[] $fields 字段名
     */
    private static function _buildReturnFields( $fields = array() ) {
        if ( empty( $fields ) ) {
            return ' * ';
        }
        return ' `' . implode( '` , `', $fields ) . '` ';
    }

    /**
     * 生成WHERE子句
     * @param        array $conditions 搜索条件
     *                                                                array(
     *                                                                        {$fieldName}:{$value}
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        {$fieldName}:array(
     *                                                                                operation:int
     *                                                                                value:mixed
     *                                                                        )
     *                                                                )
     *                                                                |
     *                                                                array(
     *                                                                        array(
     *                                                                                fieldName: string
     *                                                                                operation: int
     *                                                                                value: mixed
     *                                                                        )
     *                                                                )
     * @return        string
     */
    private static function _buildWhere( $conditions = array() ) {
        if ( empty( $conditions ) ) {
            return '';
        }

        $where = ' WHERE';
        $isFirstExpression = true;
        foreach ( $conditions as $fieldName => $condition ) {
            $operation = '=';
            $value = $condition;
            if ( is_array( $condition ) ) {
                $operation = self::_changeOperationValueToString( $condition['operation'] );
                $value = $condition['value'];
                if ( is_numeric( $fieldName ) && isset( $condition['fieldName'] ) ) {
                    $fieldName = $condition['fieldName'];
                }
            }

            if ( !$isFirstExpression ) {
                $where .= ' AND';
            }
            $where .= " `{$fieldName}` {$operation} " . ( is_numeric( $value ) ? "'{$value}'" : ( "'" . addslashes( $value ) . "'" ) );
            $isFirstExpression = false;
        }

        return $where;
    }

    /**
     * 生成排序子句
     * @param        array $sortBy 排序
     *                                                        array(
     *                                                                {$fieldName}:int
     *                                                        )
     * @return        string
     */
    private static function _buildSortBy( $sortBy = array() ) {
        if ( empty( $sortBy ) ) {
            return '';
        }

        $orderBy = ' ORDER BY';
        $isFirstExpression = true;
        foreach ( $sortBy as $fieldName => $sortType ) {
            if ( !$isFirstExpression ) {
                $orderBy .= ' ,';
            }
            $orderBy .= " `{$fieldName}` " . ( $sortType == self::SORT_TYPE_ASC ? 'ASC' : 'DESC' );
            $isFirstExpression = false;
        }

        return $orderBy;
    }

    /**
     * 转换操作符的值为字符串
     * @param        int $operation 操作符的值
     * @return        string
     */
    private static function _changeOperationValueToString( $operation ) {
        switch ( $operation ) {
            case self::OPERATION_EQUAL:

                return '=';

            case self::OPERATION_NOT_EQUAL:

                return '!=';

            case self::OPERATION_LESS_THAN:

                return '<';

            case self::OPERATION_LESS_THAN_OR_EQUAL:

                return '<=';

            case self::OPERATION_MORE_THAN:

                return '>';

            case self::OPERATION_MORE_THAN_OR_EQUAL:

                return '>=';
        }
    }

    /**
     * 创建Values子句
     * @param        array $datas 数据
     *                                                        array(
     *                                                                {$fieldName}:{$value}
     *                                                        )
     *                                                        | array(
     *                                                                array(
     *                                                                        {$fieldName}:{$value}
     *                                                                )
     *                                                        )
     */
    private static function _buildFields( $datas ) {
        $isFirstData = true;
        $keys = "";
        $values = "";
        foreach ( $datas as $key => $value ) {
            if ( !is_array( $value ) ) {
                return self::_buildSingleData( $datas );
            } else {
                if ( !$isFirstData ) {
                    $values .= ' , ';
                }

                $data = self::_buildSingleData( $value );
                $values .= $data['values'];

                if ( $isFirstData ) {
                    $keys = $data['keys'];
                    $isFirstData = false;
                }
            }
        }

        return array(
            'keys' => $keys,
            'values' => $values,
        );
    }

    /**
     * 生成数值
     * @param        array $data 数据
     *                                                        array(
     *                                                                {$fieldName}:{$value}
     *                                                        )
     * @return        array(
     *                                keys: string
     *                                values: string
     *                        )
     */
    private static function _buildSingleData( $data ) {
        $values = array();
        $keys = array();
        foreach ( $data as $key => $value ) {
            $values[] = self::_changeToSafeValue( $value );
            $keys[] = $key;
        }

        return array(
            'keys' => '( `' . implode( '` , `', $keys ) . '` )',
            'values' => '( ' . join( ' , ', $values ) . ' )',
        );
    }

    /**
     * 获取限制数
     * @param        array $limit array(
     *                                                                count: int
     *                                                                startIndex: int
     *                                                        )
     * @return string
     */
    private static function _buildLimit( $limit ) {
        if ( empty( $limit ) ) {
            return '';
        }

        if ( empty( $limit['count'] ) || !isset( $limit['startIndex'] ) ) {
            return '';
        }

        return " LIMIT {$limit['startIndex']} , {$limit['count']}";
    }

    /**
     * 生成更新数据的字段语句
     * @param        array $datas 数据
     *                                                        array(
     *                                                                {$fieldName}:{$value}
     *                                                        )
     * @return        string
     */
    private static function _buildUpdateDataFields( $datas ) {
        $fields = array();
        foreach ( $datas as $fieldName => $value ) {
            $fields[] = "`{$fieldName}` = " . self::_changeToSafeValue( $value );
        }

        return implode( ' , ', $fields );
    }

}

