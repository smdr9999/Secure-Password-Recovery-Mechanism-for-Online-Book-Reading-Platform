<?php
session_start(); // Start session at the beginning

// Include database connection
include('db.php');

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = md5($_POST['password']); // Hash the password
$confirmPassword = md5($_POST['confirm_password']); // Hash the confirm password

// Compare password and confirm password
if ($password !== $confirmPassword) {
    echo "Passwords do not match.";
    exit();
}

// Insert data into users table
$stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
    // Redirect to secquestion.html upon successful registration
    $_SESSION['email'] = $email;
    header("Location: secquestion.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
