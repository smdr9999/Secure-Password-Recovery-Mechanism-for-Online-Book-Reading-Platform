
<?php

session_start(); // Start session at the beginning

// Check if OTP and email are set in session and POST data
if (!isset($_SESSION['otp'])) {
    echo 'error';
    exit();
}

// Get OTP and email from POST data
$otp = $_POST['otp'];

// Compare entered OTP with stored OTP in session
if ($otp == $_SESSION['otp']) {
    // OTP and email verified successfully
    echo 'success';
} else {
    // Invalid OTP or email
    echo 'error';
}

// Clear the OTP and verified email from session after verification
unset($_SESSION['otp']);
?>
