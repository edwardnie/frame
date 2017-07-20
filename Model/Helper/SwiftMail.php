<?php

/**
 * Class Helper_SwiftMail
 * 给多个人发邮件
 */

class Helper_SwiftMail {

    /**
     * @param $subject
     * @param $body
     * @param array $to  array(array('email@126.com' => 'email'),array('email@126.com' => 'email'))
     * @return bool
     */
    public static function sendMail($subject,$body,$to = array()){
        require_once ROOT_DIR.'Lib/swiftmailer/swift_required.php';
        $emailConfig = Common::getConfig('email_config');
        $transport = Swift_SmtpTransport::newInstance($emailConfig['host'],$emailConfig['port'] );
        $transport->setUsername($emailConfig['user']);
        $transport->setPassword($emailConfig['passwd']);
        $message = Swift_Message::newInstance();
        $message->setTo($to);
        $message->setSubject($subject);
        $message->setBody($body);
        $message->setFrom($emailConfig['user'], $emailConfig['name']);
        $mailer = Swift_Mailer::newInstance($transport);
        $mailer->send($message, $failedRecipients);
        if($failedRecipients){
            return false;
        }
        return true;
    }
}