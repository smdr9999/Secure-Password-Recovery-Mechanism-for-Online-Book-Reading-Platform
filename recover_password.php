<?php
// Check if a session is not already active
//session_start();

// Include database connection
include('db.php');

// Get username from POST data
$to_Email = $_POST['email'];

// Check if username exists in the database (assuming username can be either email or phone)
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $to_Email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    
    //$toEmail = $_POST['email'];
    $subject = 'OTP Verification';
    $otp = generateOTP(); // Generate OTP
    $message = "Your OTP for verification is: $otp";
    $headers = 'From: smdr3646@gmail.com'; // Replace with a valid sender email address

    if (mail($to_Email, $subject, $message, $headers)) {
        // Set the OTP in session for verification
        session_start();
        $_SESSION['otp'] = $otp;
    echo 'success';
} else {
    echo 'error';
}
}
else{
    echo 'Not_found';
}
$stmt->close();
$conn->close();
function generateOTP() {
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $otp_length = 8; // You can change the length of OTP here if needed
    $otp = "";

    for ($i = 0; $i < $otp_length; $i++) {
        $otp .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $otp;
}
?>
