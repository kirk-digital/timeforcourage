<?php
/**
 * Contact Form Handler using PHPMailer
 * 
 * This version uses PHPMailer with Gmail SMTP for reliable email delivery.
 * 
 * INSTALLATION:
 * 1. Download PHPMailer: 
 *    cd /var/www/timeforcourage.co.uk
 *    wget https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
 *    unzip master.zip
 *    mv PHPMailer-master PHPMailer
 *    rm master.zip
 * 
 * 2. That's it! This file will automatically use it.
 */

// Try to load PHPMailer
$phpmailer_path = __DIR__ . '/PHPMailer/src/Exception.php';
if (file_exists($phpmailer_path)) {
    require __DIR__ . '/PHPMailer/src/Exception.php';
    require __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require __DIR__ . '/PHPMailer/src/SMTP.php';
    $use_phpmailer = true;
} else {
    // Fallback to simple mail() if PHPMailer not found
    $use_phpmailer = false;
}

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

if ($use_phpmailer) {
    // Use PHPMailer with Gmail SMTP
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

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
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom('hello.timeforcourage@gmail.com', 'Time For Courage Website');
        $mail->addAddress('hello.timeforcourage@gmail.com', 'Time For Courage');
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
} else {
    // Fallback: Use PHP mail() function
    $to = "hello.timeforcourage@gmail.com";
    $subject = "New Contact Form Message - Time For Courage";
    
    $email_body = "New contact form submission from Time For Courage website:\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Phone: " . $phone . "\n\n";
    $email_body .= "Message:\n" . $message . "\n";
    
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    
    $mail_sent = @mail($to, $subject, $email_body, $headers);
    
    if ($mail_sent) {
        header("Location: index.html#contact?sent=1");
        exit;
    } else {
        error_log("Failed to send email from contact form. Name: $name, Email: $email");
        header("Location: index.html#contact?error=send_failed");
        exit;
    }
}
?>
