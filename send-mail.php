<?php
/**
 * Contact Form Handler
 * 
 * Simple PHP mail() function implementation - no PHPMailer required
 * This works on most servers without additional dependencies.
 */

// Set proper headers
header('Content-Type: text/html; charset=UTF-8');

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

// Email configuration
$to = "hello.timeforcourage@gmail.com";
$subject = "New Contact Form Message - Time For Courage";

// Prepare email body (plain text version)
$email_body = "New contact form submission from Time For Courage website:\n\n";
$email_body .= "Name: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Phone: " . $phone . "\n\n";
$email_body .= "Message:\n" . $message . "\n";

// Email headers
$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "MIME-Version: 1.0\r\n";

// Send email
$mail_sent = @mail($to, $subject, $email_body, $headers);

if ($mail_sent) {
    // Success - redirect with success message
    header("Location: index.html#contact?sent=1");
    exit;
} else {
    // Failed - redirect with error message
    error_log("Failed to send email from contact form. Name: $name, Email: $email");
    header("Location: index.html#contact?error=send_failed");
    exit;
}
?>
