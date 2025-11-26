<?php
// If you used Composer for PHPMailer:
require __DIR__ . '/vendor/autoload.php';

// If you installed PHPMailer manually, require the 3 files instead:
// require __DIR__ . '/src/PHPMailer/src/Exception.php';
// require __DIR__ . '/src/PHPMailer/src/PHPMailer.php';
// require __DIR__ . '/src/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Simple sanitisation (improve as needed)
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : false;
$phone   = isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : '';
$message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';

if (!$email || !$name || !$message) {
    http_response_code(400);
    exit('Missing required fields');
}

$sendgridApiKey = getenv('SENDGRID_API_KEY');
if (!$sendgridApiKey) {
    error_log('SENDGRID_API_KEY not set in environment');
    http_response_code(500);
    exit('Server misconfiguration - API key missing');
}

$mail = new PHPMailer(true);

try {
    // Enable for debug while testing; set to 0 or remove in production
    // $mail->SMTPDebug = 2;
    // $mail->Debugoutput = 'html';

    $mail->isSMTP();
    $mail->Host       = 'smtp.sendgrid.net';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'apikey';              // literal username "apikey"
    $mail->Password   = $sendgridApiKey;       // API key from environment
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // From address should be a verified sender or a domain you control
    $mail->setFrom('hello@timeforcourage.co.uk', 'TimeForCourage Contact');
    $mail->addAddress('hello.timeforcourage@gmail.com', 'Site Owner');

    $mail->isHTML(false);
    $mail->Subject = 'New contact form message';
    $mail->Body    = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";

    $mail->send();
    echo 'Message sent';
} catch (Exception $e) {
    // Better to log errors than reveal to the public in production
    error_log('Mail error: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo 'Message could not be sent.';
}
