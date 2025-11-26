<?php
require '/var/www/timeforcourage.co.uk/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $phone = htmlspecialchars($_POST['phone']);

    $email_content = "Name: $name\nEmail: $email\nPhone: $phone\nMessage:\n$message";

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("hello@timeforcourage.co.uk", "Website Contact Form");
    $email->setSubject("New Contact Form Message");
    $email->addTo("hello.timeforcourage@gmail.com", "Your Name");
    $email->addContent("text/plain", $email_content);

    $sendgrid = new \SendGrid('YOUR_SENDGRID_API_KEY');

    try {
        $response = $sendgrid->send($email);
        if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
            echo "Message sent successfully!";
        } else {
            echo "Failed to send message. Status: " . $response->statusCode();
        }
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage();
    }
}
?>
