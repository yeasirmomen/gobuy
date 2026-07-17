<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return_id = (int)$_POST['return_id'];
    $user_id = $_SESSION['user_id'];

    // Mark return as canceled
    $stmt = $conn->prepare("UPDATE returns SET canceled=1, status='Rejected' WHERE id=? AND user_id=? AND status='Pending'");
    $stmt->bind_param("ii", $return_id, $user_id);
    if($stmt->execute()) {
        header("Location: return_history.php");
        exit;
    } else {
        echo "Error canceling return: ".$conn->error;
    }
}
?>
