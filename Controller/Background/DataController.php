<?php

class DataController extends Framework_BaseController {

    public function jsonData(){
        $txt = $this->inputDatas['txt'];
        $arr = array();
        for($i=0;$i<strlen($txt);$i++){
            $arr[$i]['name'] = $txt.'_name_'.$i;
            $arr[$i]['age'] = $i;
            $arr[$i]['chips'] = $i*$i*1234;
        }
        return $arr;
    }

    public function data(){
        $data = $this->inputDatas;
        file_put_contents('json',json_encode($data)."\r\n",FILE_APPEND);
        echo 1;
    }

} 
