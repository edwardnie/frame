<?php
//phpinfo();


$tpl = "<xml>
<ToUserName><![CDATA[toUser]]></ToUserName>
<FromUserName><![CDATA[fromUser]]></FromUserName>
<CreateTime>12345678</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>2</ArticleCount>
<Articles>
<item>
<Title><![CDATA[title1]]></Title> 
<Description><![CDATA[description1]]></Description>
<PicUrl><![CDATA[picurl]]></PicUrl>
<Url><![CDATA[url]]></Url>
</item>
<item>
<Title><![CDATA[title]]></Title>
<Description><![CDATA[description]]></Description>
<PicUrl><![CDATA[picurl]]></PicUrl>
<Url><![CDATA[url]]></Url>
</item>
</Articles>
</xml>";




ini_set("display_errors", "On");
function add_material($filename) {
    $file_info = array('filename' => $filename, //图片相对于网站根目录的路径
        // $file_info = 'image/1.jpg', //图片相对于网站根目录的路径
        'content-type' => 'image/jpg', //文件类型
        //'filelength' => '71' //图文大小
    );
    //var_dump($file_info);
    $access_token ="xwgmNQiA0vzT4FivRME_pcmJrlbSR4nnuoyKqo2psx_Iui7I3Tl7Fd51qANKOcc_WitG-fik_23T_SVU291MZKAk7GOXDERtpwQuFHuRsltbbJA5OOvzL8NRzV7358qVBBBbAAAWWW";
    $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type=image";
    $ch1 = curl_init();
    $timeout = 5;
    $real_path = "{$file_info['filename']}";
    //$real_path=str_replace("/", "//", $real_path);
    $data = array("media" => "@{$real_path}", 'form-data' => $file_info);
    curl_setopt($ch1, CURLOPT_URL, $url);
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch1);
    curl_close($ch1);

    $result = json_decode($result, true);
    var_dump($result);
    return $result['media_id'];
}
$filename=$_GET['url'];
//echo $filename;
echo add_material($filename);