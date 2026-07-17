<?php
session_start();
require_once 'config.php';

/* =========================
   Security: user must be logged in
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* =========================
   Helper: safe redirect
========================= */
function back_to_cart() {
    header("Location: cart.php");
    exit;
}

/* =========================
   REMOVE ITEM
========================= */
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {

    $cart_id = (int)$_GET['remove'];

    // Ensure cart item belongs to this user
    $stmt = $conn->prepare(
        "DELETE FROM cart WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();

    back_to_cart();
}

/* =========================
   UPDATE QUANTITY
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['cart_id'], $_POST['quantity'])) {
        back_to_cart();
    }

    $cart_id  = (int)$_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];

    // Quantity validation
    if ($quantity < 1) {
        $quantity = 1;
    }
    if ($quantity > 99) {
        $quantity = 99; // sane upper limit
    }

    // Update only if it belongs to this user
    $stmt = $conn->prepare(
        "UPDATE cart 
         SET quantity = ? 
         WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    $stmt->execute();

    back_to_cart();
}

/* =========================
   Fallback
========================= */
back_to_cart();
