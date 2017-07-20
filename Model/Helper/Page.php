<?php

class Helper_Page {

    /**
     * 分页
     * @param $url string 链接地址
     * @param int $rows 每页显示的数量
     * @param string $total 总页数
     * @param int $page
     * @return string
     */
    public static function getpage( $url, $rows = 15, $total = '', $page = 1 ) {
        $totalpage = @ceil( $total / $rows );
        $nextpage = $page + 1;
        $uppage = $page - 1;
        if ( $nextpage > $totalpage ) {
            $nextpage = $totalpage;
        }
        if ( $uppage < 1 ) {
            $uppage = 1;
        }
        $s = $page - 3;
        if ( $s < 1 ) {
            $s = 1;
        }
        $b = $s;
        for ( $ii = 0; $ii < 6; $ii++ ) {
            $b++;
        }
        if ( $b > $totalpage ) {
            $b = $totalpage;
        }
        for ( $j = $s; $j <= $b; $j++ ) {
            if ( $j == $page ) {
                $show .= " <li><span>$j</span></li>";
            } else {
                $show .= "<li><a href=\"$url&page={$j}\" title=\"第{$j}页\">$j</a></li>";
            }
        }
        $showpage = "<ul class=\"pagination\"><li><a href=\"$url&page=1\" title=\"首页\" class=\"pb\"><span>首页</span></li><li><a href='{$url}&page={$uppage}'>上一页</a></li>{$show}  <li><span><a href=\"$url&page=$nextpage\" title=\"下一页\" class=\"next\" >下一页</a></span></li>  <li><a href=\"$url&page=$totalpage\" title=\"尾页\" class=\"pe\">尾页</A></li></ul>";
        if ( $totalpage > 1 ) {
            return $showpage;
        }
    }

}

