<?php
/**
 * Contact Form Handler with PHPMailer
 * 
 * This script uses PHPMailer to send emails via Gmail SMTP.
 * 
 * INSTALLATION REQUIRED:
 * On your server, run: composer require phpmailer/phpmailer
 * This will install PHPMailer in a vendor/ directory.
 */

// Check if PHPMailer is installed via Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    // Fallback: try manual installation path
    if (file_exists(__DIR__ . '/PHPMailer/src/Exception.php')) {
        require __DIR__ . '/PHPMailer/src/Exception.php';
        require __DIR__ . '/PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/PHPMailer/src/SMTP.php';
    } else {
        // If PHPMailer not found, use simple mail() function as fallback
        header('Content-Type: application/json');
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $name = htmlspecialchars($_POST["name"] ?? '');
        $email = filter_var($_POST["email"] ?? '', FILTER_VALIDATE_EMAIL);
        $phone = htmlspecialchars($_POST["phone"] ?? '');
        $message = htmlspecialchars($_POST["message"] ?? '');

        if (!$email || empty($name) || empty($phone) || empty($message)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        $to = "timeforcourage@gmail.com";
        $subject = "New Contact Form Message - Time For Courage";
        $body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $subject, $body, $headers)) {
            header("Location: index.html#contact?sent=1");
        } else {
            header("Location: index.html#contact?error=send_failed");
        }
        exit;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validate request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: index.html#contact?error=method_not_allowed");
    exit;
}

// Sanitize and validate input
$name = htmlspecialchars($_POST["name"] ?? '', ENT_QUOTES, 'UTF-8');
$email = filter_var($_POST["email"] ?? '', FILTER_VALIDATE_EMAIL);
$phone = htmlspecialchars($_POST["phone"] ?? '', ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($_POST["message"] ?? '', ENT_QUOTES, 'UTF-8');

// Validate required fields
if (empty($name) || !$email || empty($phone) || empty($message)) {
    header("Location: index.html#contact?error=missing_fields");
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hello.timeforcourage@gmail.com';
    $mail->Password = 'ebyt marm mozg cxnx';  // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Recipients
    $mail->setFrom('hello.timeforcourage@gmail.com', 'Time For Courage Website');
    $mail->addAddress('timeforcourage@gmail.com', 'Time For Courage');
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
    error_log("PHPMailer Error: {$mail->ErrorInfo}");
    header("Location: index.html#contact?error=send_failed");
    exit;
}
?>
