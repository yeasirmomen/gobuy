<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
include 'config.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Check if order is pending
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=? AND status='Pending'");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Cannot cancel: order not found or not pending.");
}

// Restore stock for each item
$items = $conn->query("SELECT * FROM order_items WHERE order_id=".$order_id);
while ($item = $items->fetch_assoc()) {
    $conn->query("UPDATE products SET stock = stock + ".$item['quantity']." WHERE id=".$item['product_id']);
}

// Delete order items and order
$conn->query("DELETE FROM order_items WHERE order_id=".$order_id);
$conn->query("DELETE FROM orders WHERE id=".$order_id);

// Redirect with message
header("Location: my_orders.php?msg=Order cancelled successfully");
exit;
?>
