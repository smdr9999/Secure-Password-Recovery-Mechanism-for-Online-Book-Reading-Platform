<?php
session_start();

if (isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
    echo 'success';
} else {
    echo 'error';
}
?>
