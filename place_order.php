<?php
session_start();
require_once 'config.php';

/* =====================
   User check
===================== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* =====================
   Fetch cart items
===================== */
$cartSQL = "
SELECT c.product_id, c.quantity, p.price, p.stock
FROM cart c
JOIN products p ON c.product_id = p.id
WHERE c.user_id = $user_id
";
$cartRes = $conn->query($cartSQL);

if (!$cartRes || $cartRes->num_rows === 0) {
    die("カートが空です。");
}

/* =====================
   Calculate total
===================== */
$total_amount = 0;
$items = [];

while ($row = $cartRes->fetch_assoc()) {
    if ($row['stock'] < $row['quantity']) {
        die("在庫不足の商品があります。");
    }

    $total_amount += $row['price'] * $row['quantity'];
    $items[] = $row;
}

/* =====================
   Start transaction
===================== */
$conn->begin_transaction();

try {

    /* ---------- Save order ---------- */
    $orderStmt = $conn->prepare(
        "INSERT INTO orders (user_id, total_amount, status)
         VALUES (?, ?, 'Pending')"
    );
    $orderStmt->bind_param("id", $user_id, $total_amount);
    $orderStmt->execute();

    $order_id = $orderStmt->insert_id;
    $orderStmt->close();

    if (!$order_id) {
        throw new Exception("注文の作成に失敗しました");
    }

    /* ---------- Save order items ---------- */
    $itemStmt = $conn->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES (?, ?, ?, ?)"
    );

    $stockStmt = $conn->prepare(
        "UPDATE products SET stock = stock - ? WHERE id = ?"
    );

    foreach ($items as $item) {
        $itemStmt->bind_param(
            "iiid",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        );
        $itemStmt->execute();

        $stockStmt->bind_param(
            "ii",
            $item['quantity'],
            $item['product_id']
        );
        $stockStmt->execute();
    }

    $itemStmt->close();
    $stockStmt->close();

    /* ---------- CLEAR CART (IMPORTANT) ---------- */
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    /* ---------- Commit ---------- */
    $conn->commit();

    /* ---------- Thank You page ---------- */
    header("Location: thank_you.php");
    exit;

} catch (Exception $e) {

    $conn->rollback();
    die("注文処理エラー: " . $e->getMessage());
}
