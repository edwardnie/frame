<?php

class ToolsController extends Framework_BaseController {

    public function code() {
        $width = $this->inputDatas['width'] ? $this->inputDatas['width'] : 120;
        $height = $this->inputDatas['height'] ? $this->inputDatas['height'] : 40;
        $fontSize = $this->inputDatas['fontSize'] ? $this->inputDatas['fontSize'] : 18;
        Helper_Code::code( $width, $height, $fontSize );
    }

    public function checkCode() {
        @ob_clean();
        @session_start();
        $yzm = strip_tags( trim( $this->inputDatas['yzm'] ) );//code
        if ( strtolower( $_SESSION['auth_img_code'] ) != md5( strtolower( $yzm ) ) ) {
            return array( 'state' => 0 );
        }
        return array( 'state' => 1 );
    }
}