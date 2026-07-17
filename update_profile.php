<?php
// update_profile.php — handle profile updates (name, address)
session_start();
require_once __DIR__ . '/config.php';

// ensure user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id_session = (int)$_SESSION['user_id'];

// validate POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profile.php");
    exit;
}

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
if ($user_id !== $user_id_session) {
    // mismatch — possible tampering
    $_SESSION['flash_msg'] = '不正なリクエストです。';
    header("Location: profile.php");
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';

// Basic validation
$errors = [];
if ($name === '') $errors[] = '名前を入力してください。';
if (mb_strlen($name) > 191) $errors[] = '名前は191文字以内で入力してください。';
if (mb_strlen($address) > 2000) $errors[] = '住所が長すぎます。';

if (!empty($errors)) {
    $_SESSION['flash_msg'] = implode(' ', $errors);
    header("Location: profile.php");
    exit;
}

// Update DB using prepared statement
$stmt = $conn->prepare("UPDATE users SET name = ?, address = ? WHERE id = ?");
if (!$stmt) {
    $_SESSION['flash_msg'] = 'DBエラー: ' . htmlspecialchars($conn->error);
    header("Location: profile.php");
    exit;
}
$stmt->bind_param("ssi", $name, $address, $user_id);
$ok = $stmt->execute();
if ($ok) {
    // update session name for immediate UX
    $_SESSION['user_name'] = $name;
    $_SESSION['flash_msg'] = 'プロフィールを更新しました。';
} else {
    $_SESSION['flash_msg'] = '更新に失敗しました: ' . htmlspecialchars($stmt->error);
}
$stmt->close();

// redirect back to profile
header("Location: profile.php");
exit;
