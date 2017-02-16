<?php

class Helper_Math {

    /**
     * @param $proArr array 概率数组  base 100  array(1=>10,2=>90)
     * @param int $base 概率基数
     * @return int
     */
    public static function getRandomNum( $proArr, $base = 10000 ) {
        $result = 0;
        $proSum = $base;
        foreach ( $proArr as $key => $proValue ) {
            $randNum = mt_rand( 1, $proSum );
            if ( $randNum <= $proValue ) {
                $result = $key;
                break;
            } else {
                $proSum -= $proValue;
            }
        }

        return (integer)$result;
    }

    /**
     * 生成指定区间的随机数
     * @param $proArr   array(1=>array('rate' => 1,'start' => 1,'end' => 3),2=>array('rate' => 10,'start' => 11,'end' => 22))
     * @param int $base
     * @return int
     */
    public static function getRangeRandomNum( $proArr, $base = 10000 ) {
        $result = mt_rand( 150, 300 );
        $proSum = $base;
        foreach ( $proArr as $proValue ) {
            $randNum = mt_rand( 1, $proSum );
            if ( $randNum <= $proValue['rate'] ) {
                $result = mt_rand( $proValue['start'], $proValue['end'] );
                break;
            } else {
                $proSum -= $proValue['rate'];
            }
        }
        return (integer)$result;
    }


    /**
     * @param $num int 产生随机数数量
     * @param $start int 起始
     * @param $end int 结束
     * @return string
     */
    public static function getDiffRandom( $num, $start, $end ) {
        $count = 0;
        $rs = '';
        $array = array();
        while ( $count < $num ) {
            $a[] = mt_rand( $start, $end );
            $array = array_unique( $a );
            $count = count( $array );
        }
        foreach ( $array as $v ) {
            $rs .= $v;
        }
        return $rs;
    }

    /**
     * 产生指定个数、大小、总和的随机数
     * @param $min
     * @param $max
     * @param $n
     * @param $sum
     * @return array|null
     */
    public static function generateRandom( $min, $max, $n, $sum ) {
        if ( $min * $n > $sum || $max * $n < $sum ) {
            return null;
        }
        // 每个数最小值为 $min
        $result = array_fill( 0, $n, $min );
        // 剩余未分配的数值
        $left = $sum - $n * $min;
        // 把剩余数值随机分配给各个数
        while ( $left !== 0 ) {
            $idx = rand( 0, $n - 1 );
            $s = rand( 1, $left );
            if ( $result[$idx] + $s > $max ) {
                continue;
            } else {
                $result[$idx] += $s;
                $left -= $s;
            }
        }
        return $result;
    }


    /**********************************六合彩的方法********************************************/
    /**
     * 实现数学上的组合数算法
     * @param array $object 候选集合
     * @param int $objectLength 候选的集合大小
     * @param int $selectNum 组合元素的个数
     * @param array $keyArray 储存当前组合中的元素，这里储存的是元素键值
     * @param int $const 相当一个常量，一直保持不变
     * @return array
     */
    public static function combine($object, $objectLength, $selectNum, $keyArray, $const) {
        for ($i = $objectLength; $i >= $selectNum; $i--) {
            $keyArray[$selectNum - 1] = $i - 1;
            if ($selectNum > 1) {
                $combine [] = self::combine($object, $i - 1, $selectNum - 1, $keyArray, $const);
            } else {
                $oneCombine = '';
                for ($j = $const - 1; $j >= 0; $j--) {
                    $oneCombine .= $object[$keyArray[$j]] . ',';
                }
                $combine[] = rtrim($oneCombine, ',');
            }
        }
        return $combine;
    }

    /**
     * 递归输出数组
     * @param array $arr 待输出的数组
     * @return array 返回数组元素个数
     */
    public static function recursionArray($arr, &$combineArr) {
        foreach ($arr as $value) {
            if (is_array($value)) {
                self::recursionArray($value, $combineArr);
            } else {
                $combineArr[] = $value;
            }
        }
        return $combineArr;
    }

    /**
     * 计算玩家中6喝彩的结果个数
     * @param array $rs 开奖结果
     * @param array $combine 投注
     * @return array
     */
    function getCombineResult($rs, $combine) {
        $combineRs = array();
        foreach ($combine as $list) {
            $prizeNum = 0;
            $listArr = explode(',', $list);
            foreach ($listArr as $key) {
                if (in_array($key, $rs)) {
                    $prizeNum++;
                }
            }
            switch ($prizeNum) {
                case 3: $combineRs[3]++; break;
                case 4: $combineRs[4]++; break;
                case 5: $combineRs[5]++; break;
                case 6: $combineRs[6]++; break;
                default:  ;
            }
        }
        return $combineRs;
    }



}


