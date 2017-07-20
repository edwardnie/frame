<?php

/**
 * 图片处理类
 * Class Helper_Image
 */
class Helper_Image {

    /**
     * 裁剪图片
     * @param $src_file 原图片
     * @param $dst_file 新图片存储路径
     * @param int $new_width 新宽度
     * @param int $new_height 新高度
     * @param int $opType 压缩方式 1、固定高度宽度自适应 2、固定宽度高度自适应
     * @param int $quality 图片质量
     * @return string
     */
    public static function imageResize( $src_file, $dst_file, $new_width = 798, $new_height = 450, $opType = 1, $quality = 90 ) {
        ini_set( 'memory_limit', '1024M' );
        // 图像类型
        //        $type = exif_imagetype( $src_file );
        list( $objWidth, $objHeight, $type ) = getimagesize( $src_file );
        $support_type = array( IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_BMP );
        if ( !in_array( $type, $support_type, true ) ) {
            return false;
        }
        //Load image
        switch ( $type ) {
            case IMAGETYPE_JPEG :
                $src_img = imagecreatefromjpeg( $src_file );
                break;
            case IMAGETYPE_PNG :
                $src_img = imagecreatefrompng( $src_file );
                break;
            case IMAGETYPE_GIF :
                $src_img = imagecreatefromgif( $src_file );
                break;
            default:
                $src_img = imagecreatefromjpeg( $src_file );
                break;
        }
        $w = imagesx( $src_img );
        $h = imagesy( $src_img );

        if($new_width / $w>$new_height / $h){
            $opType = 1;
        }else{
            $opType = 2;
        }

        //固定高度，宽度自适应
        if ( $opType == 1 ) {
            $new_width = $new_height * $w / $h;
        }
        if ( $opType == 2 ) {
            //固定宽度，高度自适应
            $new_height = $new_width * $h / $w;
        }
        //TODO 根据高宽比自适应调节
        $ratio_w = 1.0 * $new_width / $w;
        $ratio_h = 1.0 * $new_height / $h;
        $ratio = 1.0;
        // 生成的图像的高宽比原来的都小，或都大 ，原则是 取大比例放大，取大比例缩小（缩小的比例就比较小了）
        //原图比较小不处理
        if ($ratio_w > 1 && $ratio_h > 1 ) {
            $inter_img = imagecreatetruecolor( $w, $h );
            imagecopy( $inter_img, $src_img, 0, 0, 0, 0, $w, $h );
            // 生成一个以最大边长度为大小的是目标图像$ratio比例的临时图像
            // 定义一个新的图像
            $new_img = imagecreatetruecolor( $w, $h );
            imagecopyresampled( $new_img, $inter_img, 0, 0, 0, 0, $w, $h, $w, $h );
        } elseif (  $ratio_w < 1 && $ratio_h < 1  ) {
            if ( $ratio_w < $ratio_h ) {
                $ratio = $ratio_h; // 情况一，宽度的比例比高度方向的小，按照高度的比例标准来裁剪或放大
            } else {
                $ratio = $ratio_w;
            }
            // 定义一个中间的临时图像，该图像的宽高比 正好满足目标要求
            $inter_w = (int)( $new_width/$ratio);
            $inter_h = (int)( $new_height/$ratio);
            $inter_img = imagecreatetruecolor( $inter_w, $inter_h );
            imagecopy( $inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h );
            // 生成一个以最大边长度为大小的是目标图像$ratio比例的临时图像
            // 定义一个新的图像
            $new_img = imagecreatetruecolor( $new_width, $new_height );
            imagecopyresampled( $new_img, $inter_img, 0, 0, 0, 0, $new_width, $new_height, $inter_w, $inter_h );
        } else {
            // 2 目标图像 的一个边大于原图，一个边小于原图 ，先放大平普图像，然后裁剪
            $ratio = $ratio_h > $ratio_w ? $ratio_h : $ratio_w; //取比例大的那个值
            // 定义一个中间的大图像，该图像的高或宽和目标图像相等，然后对原图放大
            $inter_w = (int)( $w * $ratio );
            $inter_h = (int)( $h * $ratio );
            $inter_img = imagecreatetruecolor( $inter_w, $inter_h );
            //将原图缩放比例后裁剪
            imagecopyresampled( $inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h, $w, $h );
            // 定义一个新的图像
            $new_img = imagecreatetruecolor( $new_width, $new_height );
            imagecopy( $new_img, $inter_img, 0, 0, 0, 0, $new_width, $new_height );
        }
        switch ( $type ) {
            case IMAGETYPE_JPEG :
                imagejpeg( $new_img, $dst_file, $quality ); // 存储图像
                break;
            case IMAGETYPE_PNG :
                imagejpeg( $new_img, $dst_file, $quality );
                break;
            case IMAGETYPE_GIF :
                imagegif( $new_img, $dst_file, $quality );
                break;
            default:
                imagejpeg( $new_img, $dst_file, $quality );
                break;
        }
        return $dst_file;
    }

    /**
     * 字体水印
     * @param $obj 原图片
     * @param $det 最终存储
     * @param int $quality 质量
     * @param string $text 文字
     */
    public static function waterFont( $obj, $det, $quality = 100, $text = 'MyHouse' ) {
        //创建图片的实例
        $objData = imagecreatefromstring( file_get_contents( $obj ) );
        //打上文字
        $font = ROOT_DIR . 'Themes/font/font.ttf';//字体
        $black = imagecolorallocate( $objData, 0x00, 0x00, 0x00 );//字体颜色
        list( $objWidth, $objHeight, $objType ) = getimagesize( $obj );
        $pos = self::imgPos( $objWidth, $objHeight, 0, 0, 9 );
        imagefttext( $objData, 22, 13, $pos['x'], $pos['y'], $black, $font, $text );
        //输出图片
        switch ( $objType ) {
            case 1://GIF
                imagegif( $objData, $det );
                break;
            case 2://JPG
                imagejpeg( $objData, $det, $quality );
                break;
            case 3://PNG
                imagepng( $objData, $det, $quality );
                break;
            default:
                imagejpeg( $objData, $det, $quality );
                break;
        }
        imagedestroy( $objData );
    }

    /**
     * 图片水印
     * @param $obj 原图
     * @param $det 最终存储的图片
     * @param int $pos 位置
     * @param int $opacity 透明度
     * @param int $quality 图片质量
     */
    public static function waterImg( $obj, $det, $pos = 9, $opacity = 95, $quality = 90 ) {
        $water = ROOT_DIR . '/Themes/images/water.png';
        //创建图片的实例
        $objData = imagecreatefromstring( file_get_contents( $obj ) );
        $waterData = imagecreatefromstring( file_get_contents( $water ) );
        //获取水印图片的宽高
        list( $waterWidth, $waterHeight ) = getimagesize( $water );
        //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
        list( $objWidth, $objHeight, $objType ) = getimagesize( $obj );
        $position = self::imgPos( $objWidth, $objHeight, $waterWidth, $waterHeight, $pos );
//        imagecopymerge($objData, $waterData, $position['x'], $position['y'], 0, 0, $waterWidth, $waterHeight,$opacity);
        imagecopy( $objData, $waterData, $position['x'], $position['y'], 0, 0, $waterWidth, $waterHeight );
        //输出图片
        switch ( $objType ) {
            case 1://GIF
                imagegif( $objData, $det );
                break;
            case 2://JPG
                imagejpeg( $objData, $det, $quality );
                break;
            case 3://PNG
                imagepng( $objData, $det, $quality );
                break;
            default:
                imagejpeg( $objData, $det, $quality );
                break;
        }
        imagedestroy( $objData );
        imagedestroy( $waterData );
    }


    /**
     * 水印位置  1左上 2中上 3右上 4左中 5中中 6右中 7左下 8中下 9右下
     * @param $objWidth
     * @param $objHeight
     * @param $waterWidth
     * @param $waterHeight
     * @param int $pos
     * @return array
     */
    public static function imgPos( $objWidth, $objHeight, $waterWidth, $waterHeight, $pos = 9 ) {
        if ( $objWidth < $waterWidth || $objHeight < $waterHeight )  //判断水印与原图比较 如果水印的高或者宽比原图大 将返回假
            return false;
        switch ( $pos ) {
            case 1:
                $x = 10;
                $y = 10;
                break;
            case 2:
                $x = ceil( ( $objWidth - $waterWidth ) / 2 );
                $y = 10;
                break;
            case 3:
                $x = $objWidth - $waterWidth;
                $y = 10;
                break;
            case 4:
                $x = 10;
                $y = ceil( ( $objHeight - $waterHeight ) / 2 );
                break;
            case 5:
                $x = ceil( ( $objWidth - $waterWidth ) / 2 );
                $y = ceil( ( $objHeight - $waterHeight ) / 2 );
                break;
            case 6:
                $x = $objWidth - $waterWidth;
                $y = ceil( ( $objHeight - $waterHeight ) / 2 );
                break;
            case 7:
                $x = 10;
                $y = $objHeight - $waterHeight;
                break;
            case 8:
                $x = ceil( $objWidth - $waterWidth / 2 );
                $y = $objHeight - $waterHeight;
                break;
            case 9:
                $x = $objWidth - $waterWidth;
                $y = $objHeight - $waterHeight;
                break;
            case 0:
            default:
                $x = rand( 10, $objWidth - $waterWidth );
                $y = rand( 10, $objHeight - $waterHeight );
        }
        $position['x'] = $x;
        $position['y'] = $y;
        return $position;
    }
}