<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendBookIssueMail(
    $email,
    $memberId,
    $rank,
    $memberName,
    $booknumber,
    $bookname,
    $issueDate,
    $returnDate)
{

    
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = '192.168.100.80';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ahqlibrary';
        $mail->Password   = 'Aww@#52200!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAutoTLS = false;
        // $mail->SMTPSecure = '';
        $mail->Port       = 587;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->setFrom('ahqlibrary@army.lk', 'Library');
        $mail->addAddress($email, $memberName);

        $mail->isHTML(true);
        $mail->Subject = "Book Issue Notification";
        // $mail->SMTPDebug = 3;
        $mail->Debugoutput = 'html';
        $mail->CharSet = 'UTF-8';
        
        
        $mail->Body = "
        <b>  $rank $memberName($memberId),</b><br><br>

        Your  book has been issued successfully.<br><br>

        <b>Book Details</b><br><br>

        <b>Book ID :</b> $booknumber <br>
        <b>Book Name :</b> $bookname <br>
        <b>Issue Date :</b> $issueDate <br>
        <b>Return Date :</b> $returnDate <br><br>

        Please return the book on or before the due date to avoid overdue charges.<br><br>

        If you have already returned the book, please disregard this message.<br><br>

        Thank you.<br><br>

        <b>AHQ Library</b>";

        $mail->send();

        return true;

    } catch (Exception $e) {
        // return false;
         die("Mail Error: " . $mail->ErrorInfo);
    }
}

