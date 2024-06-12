<?php
session_start(); // Start session at the beginning

// Include database connection
include('db.php');

// Get form data
$username = $_POST['username'];
$password = md5($_POST['password']); // Hash the password

// Prepare and bind the statement
$stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password_hash=?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Login successful
    $_SESSION['username'] = $username; // Store username in session if needed
    echo 'success';
} else {
    // Login failed
    echo 'failure';
}

$stmt->close();
$conn->close();
?>
