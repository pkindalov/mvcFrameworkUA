<?php
    function sendMail($params)
    {
        list (
                'mail_obj' => $mail, 
                'subject' => $subject, 
                'body' => $body, 
                'receiver' => $receiver,
                'attachments' => $attachments
                ) = $params;
        try {

            $mail->setIsSMTP();
            $mail->setSMTPAuth(SMTP_AUTH);
            $mail->setSMTPSecure(SMTP_SECURE);
            $mail->setIsHTML(IS_HTML);
            $mail->setFrom(SET_FROM);
            $mail->setSubject($subject);
            $mail->setBody($body);
            $mail->setReceiver($receiver);
            $mail->setMsgSentSuccess('Message sent successfully!');

            if(count($attachments) > 0)
            {
                foreach ($attachments as $file) {
                    // $mail->addAttachment('/var/tmp/file.tar.gz');  
                    $mail->addAttach($file);  
                }
            }

            if($mail->sendMail())
            {
                return ['success' => true, 'msg' => $mail->setMsgSentSuccess];
            }
            return ['success' => false, 'msg' => 'Something goes wrong'];

        } catch (Exception $ex) {
            $msg = $mail->getError() ? $mail->getError() : ($ex->getMessage() ? $ex->getMessage() : 'Unknown error');
            return ['success' => false, 'msg' => $msg];
        }
    }