<?php
// Include database connection
include('db.php');

// Check if email, securityQuestionId, and securityAnswer are set in POST data
if (isset($_POST['email'], $_POST['securityQuestionId'], $_POST['securityAnswer'])) {
    $email = $_POST['email'];
    $securityQuestionId = $_POST['securityQuestionId'];
    $securityAnswer = $_POST['securityAnswer'];

    // Prepare and execute query to verify security answer based on email, question ID, and answer
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id = ? AND security_answer = ?");
    $stmt->bind_param("sss", $email, $securityQuestionId, $securityAnswer);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        echo 'success'; // Security answer verified successfully
    } else {
        echo 'error'; // Invalid security answer
    }

    $stmt->close();
} else {
    echo 'error'; // Required parameters not received
}
$conn->close();
?>
