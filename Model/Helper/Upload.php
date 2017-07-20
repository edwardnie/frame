<?php

class Helper_Upload {

    public static function upload( $path = 'upload/', $name = 'file' ) {
        if ( $_FILES[$name]['size'] > ( 1024 * 8 ) * 1024 ) {
            exit( "file is over 8M" );
        }
        $upload = new upload( $name, ROOT_DIR . '/Themes/images/' . $path );
        $upload->upload( $upload->newName() );
        $filename = $upload->UpFile();
        return $filename;
    }
}

class upload {
    var $FormName; //文件域名称
    var $Directroy; //上传至目录
    var $MaxSize; //最大上传大小
    var $CanUpload; //是否可以上传
    var $doUpFile; //上传的文件名
    var $sm_File; //缩略图名称
    var $Error; //错误参数
    public static $_singletonObjects = array();

    public function __construct( $formName = '', $dirPath = '', $maxSize = 2097152 ) {
        // 初始化各种参数
        $this->FormName = $formName;
        $this->MaxSize = $maxSize;
        $this->CanUpload = true;
        $this->doUpFile = '';
        $this->sm_File = '';
        $this->Error = 0;

        if ( $formName == '' ) {
            $this->CanUpload = false;
            $this->Error = 1;
        }
        //目录不存在则创建目录
        if ( !is_dir( $dirPath ) ) {
            mkdir( $dirPath, 0777, 1 );
        }
        $this->Directroy = $dirPath . '/';
    }

    // 检查文件是否存在
    public function scanFile() {
        if ( $this->CanUpload ) {
            $scan = is_readable( $_FILES[$this->FormName]['name'] );
            if ( $scan )
                $this->Error = 2;
            return $scan;
        }
    }

    // 获取文件大小
    public function getSize( $format = 'B' ) {
        if ( $this->CanUpload ) {
            if ( $_FILES[$this->FormName]['size'] == 0 ) {
                $this->Error = 3;
                $this->CanUpload = false;
            }
            switch ( $format ) {
                case 'B':
                    return $_FILES[$this->FormName]['size'];
                    break;
                case 'K':
                    return ( $_FILES[$this->FormName]['size'] ) / ( 1024 );
                    break;
                case 'M':
                    return ( $_FILES[$this->FormName]['size'] ) / ( 1024 * 1024 );
                    break;
            }
        }
    }

    // 获取文件类型
    public function getExt() {
        if ( $this->CanUpload ) {
            $ext = $_FILES[$this->FormName]['name'];
            $extStr = explode( '.', $ext );
            $count = count( $extStr ) - 1;
        }
        return $extStr[$count];
    }

    // 获取文件名称
    public function getName() {
        if ( $this->CanUpload )
            return $_FILES[$this->FormName]['name'];
    }

    // 新建文件名
    public function newName() {
        if ( $this->CanUpload ) {
            $FullName = $_FILES[$this->FormName]['name'];
            $extStr = explode( '.', $FullName );
            $count = count( $extStr ) - 1;
            $ext = $extStr[$count];

            return md5( microtime( true ) . rand( 0, 100000000 ) ) . '.' . $ext;
        }
    }

    // 上传文件
    public function upload( $fileName = '' ) {
        if ( $this->CanUpload ) {
            if ( $_FILES[$this->FormName]['size'] == 0 ) {
                $this->Error = 3;
                $this->CanUpload = false;
                return $this->Error;
            }
        }

        if ( $this->CanUpload ) {
            if ( $fileName == '' )
                $fileName = $_FILES[$this->FormName]['name'];

            $doUpload = @copy( $_FILES[$this->FormName]['tmp_name'], $this->Directroy . $fileName );

            if ( $doUpload ) {
                $this->doUpFile = $fileName;
                chmod( $this->Directroy . $fileName, 0777 );
                return true;
            } else {
                $this->Error = 4;
                return $this->Error;
            }
        }
    }

    // 上传后的文件名
    public function UpFile() {
        if ( $this->doUpFile != '' )
            return $this->doUpFile;
        else
            $this->Error = 6;
    }

    // 上传文件的路径
    public function filePath() {
        if ( $this->doUpFile != '' )
            return $this->Directroy . $this->doUpFile;
        else
            $this->Error = 6;
    }
}