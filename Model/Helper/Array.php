<?php

/**
 * 数组的助手类
 */
class Helper_Array {

    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this method.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function arrayMergeRecursiveDistinct( array &$array1, array &$array2 ) {
        $merged = $array1;

        foreach ( $array2 as $key => &$value ) {
            if ( is_array( $value ) && isset( $merged[$key] ) && is_array( $merged[$key] ) ) {
                $merged[$key] = Helper_Array::arrayMergeRecursiveDistinct( $merged[$key], $value );
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * 实现Shift，但是不会影响键值
     * @param        array $arr
     * @return        mixed
     */
    public static function arrayKeyShift( & $arr, $isReturn = 1 ) {
        reset( $arr );
        $key = key( $arr );
        if ( $key !== null ) {
            $data = $arr[$key];
            unset( $arr[$key] );
        }
        if ( $isReturn ) {
            return $data;
        }
    }

    /**
     * 解决array_diff效率低下的问题（已知问题，会消除数组1的同值项，但是很快）
     * @param array $data1 数组1
     * @param array $data2 数组2
     */
    public static function arrayDiffFast( $data1, $data2 ) {
        if ( !$data1 && !$data2 ) {
            return array();
        }
        $data1 = array_flip( $data1 );
        $data2 = array_flip( $data2 );
        foreach ( $data2 as $hash => $key ) {
            if ( isset( $data1[$hash] ) )
                unset( $data1[$hash] );
        }
        return array_flip( $data1 );
    }

    /**
     * 解决array_diff效率低下的问题（不会消除数组1的同值项，但是没有arrayDiffFast快）
     * @param array $data1 数组1
     * @param array $data2 数组2
     */
    public static function arrayDiffFast2( $firstArray, $secondArray ) {
        //转换第二个数组的键值关系
        $secondArray = array_flip( $secondArray );

        // 循环第一个数组
        foreach ( $firstArray as $key => $value ) {
            // 如果第二个数组中存在第一个数组的值
            if ( isset( $secondArray[$value] ) ) {
                // 移除第一个数组中对应的元素
                unset( $firstArray[$key] );
            }
        }

        return $firstArray;
    }

    /**
     * 数组中的对象相减
     * @param array $data1 数组1
     * @param array $data2 数组2
     */
    public static function arrayDiffObject( $array1, $array2 ) {
        foreach ( $array2 as $key => $obj ) {
            if ( in_array( $obj, $array1 ) ) {
                unset( $array1[array_search( $obj, $array1 )] );
            }
        }

        return $array1;
    }

    /**
     * 安全数据合并
     * @param        array $keys 键
     * @param        array $values 值
     */
    public static function safeArrayCombine( $keys, $values, $walkValue = 0 ) {
        if ( ( $valuesCount = count( $values ) ) < ( $keysCount = count( $keys ) ) ) {
            while ( count( $values ) < $keysCount ) {
                $values[] = $walkValue;
            }
        } else if ( $valuesCount > $keysCount ) {
            while ( count( $values ) > $keysCount ) {
                array_pop( $values );
            }
        }

        return array_combine( $keys, $values );
    }

    /**
     * 批量添加前缀后缀
     * @param array $array 数组
     * @param string $prefix 前缀
     * @param string $suffix 后缀
     */
    public static function batchAddFix( $array, $prefix = '', $suffix = '' ) {
        $return = array();
        foreach ( $array as $item ) {
            $return[] = $prefix . $item . $suffix;
        }
        return $return;
    }

    /**
     * 多维数组排序
     * @param $arr 原始数组
     * @param $key 键值
     * @param int $sort
     * @return mixed
     */
    public static function arraySort( $arr, $key, $sort = SORT_ASC ) {
        $newArr = array();
        foreach ( $arr as $k => $v ) {
            $newArr[$k] = $v[$key];
        }
        array_multisort( $newArr, $sort, $arr );
        return $arr;
    }

    /**
     * 获取多维数组中值为指定值的键值
     * @param $val
     * @param $key
     * @param array $array
     * @return int|string
     */
    public static function getMiltKeyByValue( $val, $key, $array = array() ) {
        $rs = '';
        foreach ( $array as $k => $list ) {
            if ( $val == $list[$key] ) {
                $rs = $k;
                break;
            }
        }
        return $rs;
    }

    /**
     * 获取多维数组某个特定键的所有值
     * @param array $array
     * @param $string
     * @return bool
     */
    public static function arrayGetByKey($array, $string){
        if (!trim($string)) return false;
        preg_match_all("/\"$string\";\w{1}:(?:\d+:|)(.*?);/", serialize($array), $res);
        return $res[1];
    }

}