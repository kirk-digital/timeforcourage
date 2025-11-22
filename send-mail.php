<?php
/**
 * Contact Form Handler
 * 
 * This script handles the contact form submission from the Time For Courage website.
 * It sanitizes input, sends an email using PHP's mail() function, and returns
 * a success message.
 * 
 * IMPORTANT NOTES:
 * - On local development, PHP's mail() function may not work unless you have
 *   a local mail server (MTA) configured. For testing locally, consider using
 *   MailHog or similar tools, or configure your local environment to send via
 *   SMTP.
 * - On most production hosting environments, mail() should work out of the box,
 *   but you may need to configure additional headers or use SMTP authentication.
 * - Replace YOUR_EMAIL@example.com with your actual email address.
 */

// Email configuration
$to = "timeforcourage@gmail.com"; // REPLACE THIS WITH YOUR EMAIL ADDRESS
$subject = "New Contact Form Submission - Time For Courage";

// Sanitize and validate input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : '';
    $message = isset($_POST['message']) ? filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        header("Location: index.html#contact?error=missing_fields");
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html#contact?error=invalid_email");
        exit;
    }

    // Prepare email body
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

    // Send email
    $mail_sent = @mail($to, $subject, $email_body, $headers);

    if ($mail_sent) {
        // Redirect with success message
        header("Location: index.html#contact?sent=1");
        exit;
    } else {
        // Redirect with error message
        header("Location: index.html#contact?error=send_failed");
        exit;
    }
} else {
    // Not a POST request, redirect to home
    header("Location: index.html");
    exit;
}
?>

