<?php

namespace Email;

// Autoload required classes
require_once(dirname(__DIR__) . "/vendor/autoload.php");

// Get email credentials
require_once(dirname(__DIR__) . "/credentials.php");

// Use Credentials to get mail server credentials
use Creds\Credentials;

// Use PHPMailer namespace
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Email middleware to provide email functionality
 */
class Email
{
    public function __construct()
    {
    }

    /**
     * Compose an email and send
     */
    public function send($email, $subject, $body)
    {
        $mail = new PHPMailer();

        $mail->CharSet =  "utf-8";
        $mail->IsSMTP();
        // enable SMTP authentication
        $mail->SMTPAuth = true;
        // GMAIL username
        $mail->Username = Credentials::$cred_email;
        // GMAIL password
        $mail->Password = Credentials::$cred_password;
        $mail->SMTPSecure = "ssl";
        // sets GMAIL as the SMTP server
        $mail->Host = "smtp.gmail.com";
        // set the SMTP port for the GMAIL server
        $mail->Port = "465";
        $mail->From = Credentials::$cred_email;
        $mail->FromName = 'Colors Blog';
        $mail->AddAddress($email, 'Newsletter');
        $mail->Subject  =  $subject;
        $mail->IsHTML(true);
        $mail->Body = $body;
        if ($mail->Send())
            return true;
        else
            return false;
    }
}
