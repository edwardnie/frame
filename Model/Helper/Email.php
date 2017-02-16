<?php

class Helper_Email {

    /**
     * 发邮件
     * @param string $to 收件人
     * @param string $subject 主题
     * @param string $message 内容
     * @param string $reply 回复人
     * @param string $file 附件内容
     * @return boolean
     */
    public static function send($to, $subject, $message, $reply = NULL, $file = '', $fileName = '', $fileType = 'text/plain') {
        $emailConfig = Common::getConfig('email');
        $mail = new sendMail();
        $mail->setSmtp(true, $emailConfig['host'], $emailConfig['user'], $emailConfig['passwd'], $emailConfig['port']);

        if (!$reply)
            $reply = $emailConfig['user'];

        $mail->setFrom($reply, $reply, $reply);
        $mail->setReply($reply);
        $mail->setTo($to);
        if ($file)
            $mail->addFile($file, $fileName, $fileType);

        $mail->setMail($subject, $message);
        return $mail->send();
    }

}

class sendMail {

    private $smtp, $post, $check, $user, $pass; //SMTP信息
    private $from, $fromMail, $fromName;  //邮件发送者信息
    private $to, $toMail, $toName; //邮件接受者信息
    private $replyEmail; //回复邮箱信息
    private $subject, $message, $is_html;  //邮件内容
    private $attach = array();  //附件存储
    private $serverName = "MAIL SERVER";   //发送服务器名字
    private $socketTimeout = 60;  //Socket连接超时时间

    /*     * *****************************************************************
     * @function myBase64Encode，重载Base64编码方式(私有函数)
     * 因为邮件内容的base64编码需要每76个字符就换行。(其实不换行也可以的)
     * ****************************************************************** */

    private function myBase64Encode($str) {
        $length = 76;
        $str = base64_encode($str);
        if (strlen($str) <= $length)
            return $str;
        $mystr = '';
        while (strlen($str) >= 1) {
            $mystr.=substr($str, 0, $length) . "\r\n";
            $str = substr($str, $length);
        }
        return $mystr;
    }

    /*     * *****************************************************************
     * @function setSmtp设置SMTP信息
     * @bool check:服务器是否需要身份验证
     * @string smtp:SMTP服务器信息,可以使用tcp:// , ssl:// , tls:// 打头，默认tcp.
     * @string user:SMTP用户名
     * @string pass:SMTP密码
     * @int post:SMTP服务器端口,默认25
     * ***************************************************************** */

    public function setSmtp($ckeck = false, $smtp = null, $user = null, $pass = null, $port = 25) {
        $this->check = $ckeck;
        $this->smtp = $smtp;
        $this->user = $user;
        $this->pass = $pass;
        $this->post = intval($port);
    }

    /*     * *****************************************************************************
     * @function setFrom设置发信人信息
     * @string from:发信人邮箱，需要要在SMTP服务器中授权的
     * @string fromMail:在收件人邮箱的邮件详情上显示的发信人邮箱(为空采用$from变量)
     * @string fromName:屏幕显示的发信人昵称(为空采用$fromMail的@前面字符)
     * ***************************************************************************** */

    public function setFrom($from, $fromMail = null, $fromName = null) {
        $this->from = $from;
        if (empty($fromMail))
            $this->fromMail = $from;
        else
            $this->fromMail = $fromMail;
        if (empty($fromName))
            $this->fromName = substr($this->fromMail, 0, strpos($this->fromMail, '@'));
        else
            $this->fromName = $fromName;
    }

    /**
     * 设置回复邮箱
     * @var string $replyEmail
     */
    public function setReply($replyEmail) {
        if ($replyEmail) {
            $this->replyEmail = $replyEmail;
        }
    }

    /*     * **********************************************************************************
     * @function setTo设置收信人信息
     * @string to:真实的收信人邮箱
     * @string toMail:在真实收件人邮箱的邮件详情上显示的收信人邮箱(为空自动采用$to内容)
     * @string toName:在屏幕显示出来的收信人昵称(为空自动采用$toMail的@前面的内容)
     * ********************************************************************************** */

    public function setTo($to, $toMail = null, $toName = null) {
        $this->to = $to;
        if (empty($toMail))
            $this->toMail = $to;
        else
            $this->toMail = $toMail;
        if (empty($toName))
            $this->toName = substr($this->toMail, 0, strpos($this->toMail, '@'));
        else
            $this->toName = $toName;
    }

    /*     * ***************************************************
     * @function setMail设置邮件信息
     * @string subject:邮件主题
     * @string message:邮件内容
     * @bool is_html:邮件格式,是否为HTML类型,反之为TEXT类型
     * *************************************************** */

    public function setMail($subject = null, $message = null, $is_html = true) {
        $this->subject = $subject;
        $this->message = $message;
        $this->is_html = $is_html;
    }

    /*     * *****************************************
     * @function setServerName设置服务器名称
     * @string serverName:服务器名称
     * ***************************************** */

    public function setServerName($ServerName) {
        $this->serverName = $ServerName;
    }

    /*     * *****************************************
     * @function addFile增加邮件附件内容
     * @string content:附件内容流
     * @string name:附件名称
     * @string type:附件MIME类型
     * ***************************************** */

    public function addFile($content, $name = null, $type = "application/octet-stream") {
        $this->attach[] = array(
            'type' => $type,
            'name' => empty($name) ? 'Attachment_' . rand() : $name,
            'content' => $content
        );
    }

    /*     * *****************************************
     * @function send发送邮件
     * 成功则return false
     * 失败就return "code:description"
     * ***************************************** */

    public function send() {

        //连接服务器
        $timeOut = empty($this->socketTimeout) ? 60 : $this->socketTimeout;
        if (function_exists('fsockopen'))
            $fp = @fsockopen($this->smtp, $this->post, $errno, $errstr, $timeOut);
        else if (function_exists('pfsockopen'))
            $fp = @pfsockopen($this->smtp, $this->post, $errno, $errstr, $timeOut);
        else if (function_exists('stream_socket_client'))
            $fp = @stream_socket_client($this->smtp . ':' . $this->post, $errno, $errstr, $timeOut);
        else
            return "0:all socket function isnot exists";
        if (!$fp)
            return "0:open server socket error," . $errstr . "({$errno})";
        stream_set_blocking($fp, true);
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '220')
            return "1:$lastmessage";

        //Hello
        if ($this->check)
            $lastact = "EHLO " . str_replace(' ', '-', $this->serverName) . "\r\n";
        else
            $lastact = "HELO " . str_replace(' ', '-', $this->serverName) . "\r\n";
        fputs($fp, $lastact);
        $lastmessage == fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '220')
            return "2:$lastmessage";

        do
            $lastmessage = fgets($fp, 512);
        while (!empty($lastmessage) && (substr($lastmessage, 3, 1) == "-"));

        //服务器需要身份验证
        if ($this->check) {
            //发送验证请求
            $lastact = "AUTH LOGIN" . "\r\n";
            fputs($fp, $lastact);
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != "334")
                return "3:$lastmessage";
            //输入用户账号
            $lastact = base64_encode($this->user) . "\r\n";
            fputs($fp, $lastact);
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != "334")
                return "4:$lastmessage";
            //输入用户密码
            $lastact = base64_encode($this->pass) . "\r\n";
            fputs($fp, $lastact);
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != "235")
                return "5:$lastmessage";
        }

        //发送MAIL FROM信息
        $lastact = "MAIL FROM: <" . $this->from . ">\r\n";
        fputs($fp, $lastact);
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '250')
            return "6:$lastmessage";

        //发送RCPT TO信息
        $lastact = "RCPT TO: <" . $this->to . "> \r\n";
        fputs($fp, $lastact);
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '250')
            return "7:$lastmessage";

        //发送DATA信息
        $lastact = "DATA" . "\r\n";
        fputs($fp, $lastact);
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '354')
            return "8:$lastmessage";

        //设置本邮件分段信息标志
        $boundary = md5(time() . rand());

        /*         * Head头部开始*********************************************************************** */

        $head = '';
        //设置回复地址
        if (!empty($this->replyEmail)) {
            $head.="Reply-To: <{$this->replyEmail}>\r\n";
        }
        //处理Subject主题头
        $head.="Subject: " . "=?UTF-8?B?" . base64_encode($this->subject) . "?=" . "\r\n";
        //处理From To头,屏幕显示的发件人
        $head.="From: {$this->fromName} <{$this->fromMail}>\r\n";
        $head.="To: {$this->toName} <{$this->toMail}>\r\n";
        //处理MIME-Version头
        $head.="MIME-Version: 1.0\r\n";
        //处理分段标志
        $head.="Content-Type: multipart/mixed;boundary=\"{$boundary}\"" . "\r\n";
        //换行一下你就知道
        $head.="\r\n";

        /*         * Head头部结束，邮件信息内容处理开始****************************************************** */

        //加个分割线
        $head_temp = "--" . $boundary . "\r\n";
        //加入邮件Mime类型和UTF8编码声明
        if ($this->is_html)
            $head_temp.="Content-Type: text/html; charset=UTF-8" . "\r\n";
        else
            $head_temp.="Content-Type: text/plain; charset=UTF-8" . "\r\n";
        //加入内容是Base64编码的声明
        $head_temp.="Content-Transfer-Encoding: base64" . "\r\n";
        //加上邮件主题内容
        $message = $head_temp . "\r\n" . $this->myBase64Encode($this->message) . "\r\n" . "\r\n";

        /*         * 邮件信息内容处理结束,附件内容处理开始*************************************************** */

        $mailAttach = '';
        foreach ($this->attach as $curAttach) {
            //加个分割线
            $head_temp = "--" . $boundary . "\r\n";
            //加入编码和类型头
            $head_temp.="Content-Type: {$curAttach['type']}; name=\"{$curAttach['name']}\"" . "\r\n";
            $head_temp.="Content-Transfer-Encoding: base64" . "\r\n";
            $head_temp.="Content-Disposition: attachment;filename=\"{$curAttach['name']}\"" . "\r\n";
            ;
            //加上附件主体内容
            $mailAttach.=$head_temp . "\r\n" . $this->myBase64Encode($curAttach['content']) . "\r\n" . "\r\n";
        }

        /*         * *********************************附件内容处理结束********************************** */

        //拼凑全部内容
        $mailContent = $head . $message . $mailAttach;
        //加入结束分割线和邮件结束的点
        $mailContent.="\r\n" . "--" . $boundary . "--" . "\r\n";
        $mailContent.="\r\n" . "." . "\r\n";

        //发送信息
        fputs($fp, $mailContent);
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '250')
            return "8:$lastmessage";

        $lastact = "QUIT" . "\r\n";
        fputs($fp, $lastact);
        fclose($fp);
        return true;
    }

}

