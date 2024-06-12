<?php
// Include database connection
include('db.php');

// Check if email is set in POST data
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Prepare and execute query to fetch security question based on email
    $stmt = $conn->prepare("SELECT id, security_question FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $questionData = array(
            'id' => $row['id'],
            'question' => $row['security_question']
        );
        echo json_encode($questionData);
    } else {
        echo 'error'; // Email not found or multiple records found (should be unique)
    }

    $stmt->close();
} else {
    echo 'error'; // Email parameter not received
}
$conn->close();
?>
