<?php
/**
 * Contact Form Handler using PHPMailer (Composer autoload + env vars)
 *
 * Requirements:
 * - Install PHPMailer via Composer in the project root:
 *     cd /var/www/timeforcourage.co.uk
 *     composer require phpmailer/phpmailer
 *
 * - Configure environment variables for PHP-FPM / nginx:
 *     SMTP_HOST (default: smtp.gmail.com)
 *     SMTP_PORT (default: 587)
 *     SMTP_USER (required)
 *     SMTP_PASS (required)
 *     SMTP_SECURE (optional: tls or ssl, default: tls)
 *
 * This script will fail-fast (redirect with error) if Composer/credentials are missing.
 */

// Validate request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: index.html#contact?error=method_not_allowed");
    exit;
}

// Sanitize and validate input
$name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8') : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
$phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8') : '';
$message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8') : '';

// Validate required fields
if (empty($name) || !$email || empty($phone) || empty($message)) {
    header("Location: index.html#contact?error=missing_fields");
    exit;
}

// Load Composer autoload
$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    error_log('PHPMailer/autoload not found. Run composer require phpmailer/phpmailer');
    header("Location: index.html#contact?error=no_mailer");
    exit;
}
require $autoload;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Read SMTP config from environment
$smtpHost = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
$smtpPort = getenv('SMTP_PORT') ?: 587;
$smtpUser = getenv('SMTP_USER') ?: '';
$smtpPass = getenv('SMTP_PASS') ?: '';
$smtpSecure = getenv('SMTP_SECURE') ?: 'tls';

if (empty($smtpUser) || empty($smtpPass)) {
    error_log('SMTP credentials missing (SMTP_USER / SMTP_PASS).');
    header("Location: index.html#contact?error=no_smtp_credentials");
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = ($smtpSecure === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int)$smtpPort;
    $mail->CharSet    = 'UTF-8';
    $mail->setLanguage('en');

    // Recipients
    $mail->setFrom($smtpUser, 'Time For Courage Website');
    // Deliver to site owner (use the public-facing email)
    $recipient = $smtpUser; // change if you want a different recipient
    $mail->addAddress($recipient, 'Time For Courage');
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Message - Time For Courage';
    $mail->Body = "
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br($message) . "</p>
    ";
    $mail->AltBody = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\n\nMessage:\n{$message}";

    $mail->send();
    header("Location: index.html#contact?sent=1");
    exit;
} catch (Exception $e) {
    error_log("PHPMailer Error: {$mail->ErrorInfo} Exception: " . $e->getMessage());
    header("Location: index.html#contact?error=send_failed");
    exit;
}
