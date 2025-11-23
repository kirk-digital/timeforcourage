<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed");
}

$name = htmlspecialchars($_POST["name"]);
$email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
$phone = htmlspecialchars($_POST["phone"]);
$message = htmlspecialchars($_POST["message"]);

if (!$email) {
    exit("Invalid email.");
}

$to = "timeforcourage@gmail.com";
$subject = "New Contact Form Message";
$body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
$headers = "From: $email";

if (mail($to, $subject, $body, $headers)) {
    echo "Success";
} else {
    echo "Email sending failed.";
}
