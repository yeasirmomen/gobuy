<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['liked'=>false]);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];

$q = $conn->query("SELECT id FROM product_likes WHERE user_id=$user_id AND product_id=$product_id");

if ($q && $q->num_rows > 0) {
    $conn->query("DELETE FROM product_likes WHERE user_id=$user_id AND product_id=$product_id");
    echo json_encode(['liked'=>false]);
} else {
    $stmt = $conn->prepare("INSERT INTO product_likes (user_id, product_id) VALUES (?,?)");
    $stmt->bind_param("ii",$user_id,$product_id);
    $stmt->execute();
    echo json_encode(['liked'=>true]);
}
