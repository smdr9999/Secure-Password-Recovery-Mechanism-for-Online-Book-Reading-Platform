<?php
set_time_limit(1000); // Set maximum execution time to 300 seconds (5 minutes)
session_start();

$toEmail = $_POST['email']; // Get the verified email from session
$subject = 'Password Recovery';

// Execute Python script to get the password
$python_output = shell_exec('python verify.py ' . escapeshellarg($toEmail));

if ($python_output !== null) {
    // Password found, send email
    $password = trim($python_output);
    $message = "Your Password for Online Book Reading Platform is: $password";
    $headers = 'From: smdr3646@gmail.com'; // Replace with a valid sender email address

    if (mail($toEmail, $subject, $message, $headers)) {
        echo 'success'; // Email sent successfully
    } else {
        echo 'error'; // Error sending email
    }
} else {
    echo 'error'; // Error executing Python script or password not found
}
?>
