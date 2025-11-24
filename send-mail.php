<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/var/www/timeforcourage.co.uk/PHPMailer/src/Exception.php';
require '/var/www/timeforcourage.co.uk/PHPMailer/src/PHPMailer.php';
require '/var/www/timeforcourage.co.uk/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mail.leon.silva@gmail.com';
    $mail->Password = 'ebyt marm mozg cxnx';  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('timeforcourage@gmail.com', 'Website Contact Form');
    $mail->addAddress('timeforcourage@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Message';
    $mail->Body = "Name: {$_POST['name']}<br>Email: {$_POST['email']}<br>Phone: {$_POST['phone']}<br>Message:<br>{$_POST['message']}";

    $mail->send();
    echo 'Message sent';
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
