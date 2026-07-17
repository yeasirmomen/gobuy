<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (empty($_POST['product_id']) || empty(trim($_POST['comment']))) {
    header("Location: product_details.php?id=".$_POST['product_id']);
    exit;
}

$product_id = (int)$_POST['product_id'];
$user_id    = (int)$_SESSION['user_id'];
$comment    = trim($_POST['comment']);

$stmt = $conn->prepare("
    INSERT INTO product_reviews (product_id, user_id, comment, created_at)
    VALUES (?, ?, ?, NOW())
");
$stmt->bind_param("iis", $product_id, $user_id, $comment);
$stmt->execute();
$stmt->close();

header("Location: product_details.php?id=".$product_id."#comments");
exit;
