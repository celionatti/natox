<?php

declare(strict_types=1);

/**
 * Mail.php is the file that sends emails from the framework
 * @copyright 2022
 * @author Celio Natti <amisuusman@gmail.com>
 * @version 1.0
 */

/**
 * The Mail class handles all emails sent from the framework
 * @author Celio Natti <amisuusman@gmail.com>
 * @version 1.0
 */

namespace NatoxCore;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailerException;

/**
 * Class Mail
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

 class Mail
 {
    private $mailHost;
    private $mailPort;
    private $mailUsername;
    private $mailPassword;
    private $mailer;

    public function __construct()
    {

        $this->mailHost = Config::get('MAIL_HOST');
        $this->mailPort = Config::get('MAIL_PORT');
        $this->mailUsername = Config::get('MAIL_USERNAME');
        $this->mailPassword = Config::get('MAIL_PASSWORD');

        $this->mailer = new PHPMailer();
        if (!$this->mailer) {
            throw new Exception(Errors::get('4000'), 4000);
            exit;
        }

        //Server settings
        $this->mailer->isSMTP();
        $this->mailer->Mailer = "smtp";
        $this->mailer->SMTPDebug = Config::get('APP_DEBUG') ? 1 : 0;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Port = $this->mailPort;
        if ($this->mailPort == 465) {
            $this->mailer->SMTPSecure = 'ssl';
        } else {
            $this->mailer->SMTPSecure = 'tls';
        }

        $this->mailer->Host = $this->mailHost;
        $this->mailer->Username = $this->mailUsername;
        $this->mailer->Password = $this->mailPassword;


        return $this;
    }


    public function sendMail($recipients, $emailContent, $from)
    {
        if (empty($recipients) || empty($emailContent) || empty($from)) {
            //throw new Exception(Errors::get('4001'), 4001);
            return false;
            exit;
        }

        $this->recipientEmail = $recipients['email'] ?? '';
        $this->recipientName = $recipients['name'] ?? '';
        $this->subject = $emailContent['subject'] ?? '';
        $this->body = $emailContent['body'] ?? '';
        $this->fromEmail = $from['email'] ?? '';
        $this->fromName = $from['name'] ?? '';

        $this->mailer->IsHTML(true);
        if (is_array($this->recipientEmail)) {
            foreach ($this->recipientEmail as $recipientEmail) {
                $this->mailer->AddAddress($recipientEmail);
            }
        } else {
            $this->mailer->AddAddress($this->recipientEmail, $this->recipientName);
        }
        $this->mailer->SetFrom($this->fromEmail, $this->fromName);
        //$this->mailer->AddReplyTo("replytoemail", "replyto name");
        //$this->mailer->AddCC("ccemail", "ccname");
        $this->mailer->Subject = $this->subject;
        $content = $this->body;

        $this->mailer->MsgHTML($content);
        if (!$this->mailer->Send()) {
            //throw new Exception(Errors::get('4001'), 4001);
            return false;
        }

        return true;
    }
 }