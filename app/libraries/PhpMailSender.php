<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class PhpMailSender
{
    private $mail;
    private $charset;
    private $smtp_auth;
    private $smtp_secure;
    private $host;
    private $port;
    private $is_html;
    private $username;
    private $password;
    private $set_from;
    private $subject;
    private $body;
    private $receiver;
    private $message_sent_successfully;
    private $attachments;
    // private $error_message;

    public function __construct($host, $port, $username, $password)
    {
        $this->mail = new PHPMailer(true);
        $this->setHost($host);
        $this->setPort($port);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setAttachments();
    }

    public function setCharset($charset)
    {
        $this->mail->CharSet = $charset;
    }


    public function getCharset()
    {
        return $this->charset;
    }

    public function setSMTPAuth($smtp_auth)
    {
        $this->smtp_auth = $smtp_auth;
        $this->mail->SMTPAuth = $this->smtp_auth;
    }

    public function getSMTPAuth()
    {
        return $this->smtp_auth;
    }

    public function setSMTPSecure($smtp_secure)
    {
        $this->smtp_secure = $smtp_secure;
        $this->mail->SMTPSecure = $this->smtp_secure;
    }

    public function getSMTPSecure()
    {
        return $this->smtp_secure;
    }

    public function setIsHTML($is_html)
    {
        $this->is_html = $is_html;
        $this->mail->IsHTML($this->is_html);
    }

    public function getIsHTML()
    {
        return $this->is_html;
    }

    public function setIsSMTP()
    {
        $this->mail->IsSMTP();
    }

    public function getIsSMTP()
    {
        return $this->mail->IsSMTP();
    }

    public function setFrom($set_from)
    {
        $this->set_from = $set_from;
        $this->mail->SetFrom($this->set_from);
    }

    public function getFrom()
    {
        return $this->set_from;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        $this->mail->Subject = $this->subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
        $this->mail->Body = $this->body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        $this->mail->AddAddress($this->receiver);
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setHost($host)
    {
        $this->host = $host;
        $this->mail->Host = $this->host;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setPort($port)
    {
        $this->port = $port;
        $this->mail->Port = $port;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        $this->mail->Username = $this->username;
    }

    public function setPassword($password)
    {
        $this->password  = $password;
        $this->mail->Password = $this->password;
    }

    public function setMsgSentSuccess($message_sent_successfully)
    {
        $this->message_sent_successfully = $message_sent_successfully;
    }

    public function sendMail()
    {
        if ($this->mail->send()) {
            return true;
        }
        return false;
    }

    public function getError()
    {
        return $this->mail->ErrorInfo;
    }

    public function addAttach($attachment)
    {
        $this->mail->addAttachment($attachment);
    }

    private function setAttachments()
    {
        $this->attachments = [];
    }

    // public function setHost($host){
    //     $this->host = $host;
    // }

    // public function setCharset($charset)
    // {
    //     if (gettype($charset) != 'string') {
    //         $this->error_message = 'Charset argument must be a string';
    //         return $this->error_message;
    //     }

    //     if (strlen($charset) <= 1) {
    //         $this->error_message = 'Charset argument must be at least 2 characters';
    //         return $this->error_message;
    //     }
    //     $charset = htmlspecialchars($charset);
    //     $this->charset = $charset;
    // }


}