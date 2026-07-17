<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ==========================
   Admin authentication guard
========================== */
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>管理者パネル | GoBuyNow</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
/* ===== Reset ===== */
*{box-sizing:border-box;margin:0;padding:0}

/* ===== Layout ===== */
body{
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
    background:#f4f6f9;
    color:#333;
}

.admin-header{
    background:#0d1117;
    color:#fff;
    padding:14px 24px;
    display:flex;
    align-items: right;
    justify-content:space-between;
}

.logo{
    font-size:20px;
    font-weight:700;
    letter-spacing:.5px;
}

.logo span{
    color:#58a6ff;
}

.admin-nav{
    display:flex;
    gap:18px;
    align-items:right;
}

.admin-nav a{
    color:#c9d1d9;
    text-decoration:none;
    font-size:14px;
    padding:8px 12px;
    border-radius:8px;
    transition:.2s;
    align-items:right;
}

.admin-nav a:hover,
.admin-nav a.active{
    background:#161b22;
    color:#fff;
}

.admin-right{
    display:flex;
    gap:14px;
    align-items:right;
}

.admin-name{
    font-size:14px;
    color:#c9d1d9;
}

.logout-btn{
    background:#dc3545;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
}

.logout-btn:hover{
    opacity:.9;
}

/* ===== Sub Nav ===== */
.sub-nav{
    background:#fff;
    border-bottom:1px solid #ddd;
    padding:10px 24px;
    display:flex;
    gap:18px;
    flex-wrap:wrap;
}

.sub-nav a{
    font-size:13px;
    text-decoration:none;
    color:#333;
    padding:6px 10px;
    border-radius:6px;
}

.sub-nav a:hover{
    background:#eef3ff;
    color:#1f6feb;
}
</style>
</head>

<body>

<!-- ================= Admin Top Header ================= -->
<header class="admin-header">

    <div class="logo">
        GoBuyNow <span>ADMIN</span>
    </div>

    <nav class="admin-nav">
        <a href="dashboard.php">ダッシュボード</a>
        <a href="products.php">商品一覧</a>
        <a href="add_product.php">商品追加</a>
        <a href="orders.php">注文管理</a>
        <a href="returns.php">返品管理</a>
        <a href="reviews.php">レビュー</a>    </nav>

    <div class="admin-right">
        <div class="admin-name">
            管理者：<?= htmlspecialchars($_SESSION['admin_name']) ?>
        </div>

        <form method="post" action="logout.php">
            <button class="logout-btn">ログアウト</button>
        </form>
    </div>

</header>

<!-- ================= Admin Sub Navigation ================= -->
<nav class="sub-nav">
   
</nav>
