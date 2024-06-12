<?php
session_start(); // Start session at the beginning

// Include database connection
include('db.php');

// Get form data
$email = $_SESSION['email']; // Assuming you store the email in the session during signup

if ($_POST['question'] === 'custom') {
    $question = $_POST['custom_question'];
} else {
    $question = $_POST['question'];
}
$answer = $_POST['answer'];

// Update data in users table with security question and answer
$stmt = $conn->prepare("UPDATE users SET security_question = ?, security_answer = ? WHERE email = ?");
$stmt->bind_param("sss", $question, $answer, $email);

if ($stmt->execute()) {
    // Redirect to index.html upon successful submission
    header("Location: index.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
