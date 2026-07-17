<?php
// add_to_cart.php  — robust JSON add-to-cart endpoint
// - session-based or DB-backed cart
// - enforces stock if available
// - always returns JSON for AJAX clients
// - safe for older PHP versions

session_start();
header('Vary: Accept, X-Requested-With');

// Make sure we return JSON in all cases for AJAX requests
function send_json($payload) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

// include DB if available
$useDB = false;
if (file_exists(__DIR__ . '/config.php')) {
    include_once __DIR__ . '/config.php'; // expects $conn (mysqli)
    if (isset($conn) && ($conn instanceof mysqli)) $useDB = true;
}

// detect AJAX
$isAjax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') $isAjax = true;
if (!$isAjax && isset($_POST['ajax']) && ($_POST['ajax'] === '1' || $_POST['ajax'] === 'true')) $isAjax = true;

// read inputs
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['qty']) ? (int)$_POST['qty'] : (isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1);
if ($quantity <= 0) $quantity = 1;
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

if ($product_id <= 0) {
    send_json(['success' => false, 'msg' => '無効な商品です。', 'cart_count' => 0]);
}

// get stock & is_hidden from DB if possible
$stock = null;
$is_hidden = 0;
if ($useDB) {
    $stmt = $conn->prepare("SELECT stock, COALESCE(is_hidden,0) AS is_hidden FROM products WHERE id = ?");
    if ($stmt === false) {
        send_json(['success' => false, 'msg' => 'DBエラー（prepare）', 'cart_count' => 0]);
    }
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $stock = isset($row['stock']) ? (int)$row['stock'] : null;
        $is_hidden = isset($row['is_hidden']) ? (int)$row['is_hidden'] : 0;
        if ($is_hidden !== 0) {
            send_json(['success' => false, 'msg' => 'この商品は現在購入できません。', 'cart_count' => 0]);
        }
    } else {
        send_json(['success' => false, 'msg' => '商品が見つかりません。', 'cart_count' => 0]);
    }
    $stmt->close();
}

// If stock known and zero => sold out
if ($stock !== null && $stock <= 0) {
    send_json(['success' => false, 'msg' => '売り切れです。', 'cart_count' => isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0]);
}

// Add to cart (DB-backed for logged user, session otherwise)
if ($user_id !== null && $useDB) {
    // DB cart table should be present: cart(user_id, product_id, quantity)
    // Insert or update
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    if ($stmt === false) {
        send_json(['success' => false, 'msg' => 'DBエラー（prepare2）', 'cart_count' => 0]);
    }
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $new_qty = (int)$row['quantity'] + $quantity;
        if ($stock !== null && $new_qty > $stock) $new_qty = $stock;
        $upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $upd->bind_param("ii", $new_qty, $row['id']);
        $upd->execute();
        $upd->close();
    } else {
        $ins = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?,?,?)");
        $ins->bind_param("iii", $user_id, $product_id, $quantity);
        $ins->execute();
        $ins->close();
    }
    $stmt->close();

    // compute cart count for user
    $countStmt = $conn->prepare("SELECT COALESCE(SUM(quantity),0) AS total FROM cart WHERE user_id = ?");
    $countStmt->bind_param("i", $user_id);
    $countStmt->execute();
    $cRes = $countStmt->get_result();
    $cart_count = 0;
    if ($cRes) {
        $crow = $cRes->fetch_assoc();
        $cart_count = (int)$crow['total'];
    }
    $countStmt->close();

    send_json(['success' => true, 'msg' => 'カートに追加されました。', 'cart_count' => $cart_count]);
}

// Session cart fallback
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart'] = [];
$existing = isset($_SESSION['cart'][$product_id]) ? (int)$_SESSION['cart'][$product_id] : 0;
$new_qty = $existing + $quantity;
if ($stock !== null && $new_qty > $stock) $new_qty = $stock;
$_SESSION['cart'][$product_id] = $new_qty;

// compute total count
$cart_count = 0;
foreach ($_SESSION['cart'] as $pid => $q) $cart_count += (int)$q;

send_json(['success' => true, 'msg' => 'カートに追加されました。', 'cart_count' => $cart_count]);
