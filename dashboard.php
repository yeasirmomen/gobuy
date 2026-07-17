<?php
session_start();
require_once '../config.php';
include 'header.php';

/* ===== Basic counts ===== */
$countProducts = $conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()['c'];
$countOrders   = $conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c'];
$countReviews  = $conn->query("SELECT COUNT(*) c FROM product_reviews WHERE is_hidden=0")->fetch_assoc()['c'];

/* ===== Priority queries ===== */
$lowStock = $conn->query("SELECT COUNT(*) c FROM products WHERE stock BETWEEN 1 AND 5")->fetch_assoc()['c'];
$outStock = $conn->query("SELECT COUNT(*) c FROM products WHERE stock = 0")->fetch_assoc()['c'];
$hiddenProducts = $conn->query("SELECT COUNT(*) c FROM products WHERE is_hidden=1")->fetch_assoc()['c'];

$pendingOrders = $conn->query("SELECT COUNT(*) c FROM orders WHERE status='pending'")->fetch_assoc()['c'];
?>

<div class="dashboard-container">

<h1>📊 管理ダッシュボード</h1>

<!-- ================= Quick Stats ================= -->
<div class="stats-grid">
    <div class="box"><h3>商品数</h3><p><?= $countProducts ?></p></div>
    <div class="box"><h3>注文数</h3><p><?= $countOrders ?></p></div>
    <div class="box"><h3>表示中レビュー</h3><p><?= $countReviews ?></p></div>
</div>

<!-- ================= HIGH PRIORITY ================= -->
<h2 class="priority high">🔴 最優先対応</h2>
<div class="priority-grid">

    <a class="task urgent" href="products.php?filter=lowstock">
        ⚠ 在庫少の商品<br>
        <span><?= $lowStock ?> 件</span>
    </a>

    <a class="task urgent" href="products.php?filter=outstock">
        ❌ 在庫切れ商品<br>
        <span><?= $outStock ?> 件</span>
    </a>

    <a class="task urgent" href="products.php?filter=hidden">
        ⛔ 非表示商品<br>
        <span><?= $hiddenProducts ?> 件</span>
    </a>

</div>

<!-- ================= MEDIUM PRIORITY ================= -->
<h2 class="priority medium">🟡 近日対応</h2>
<div class="priority-grid">

    <a class="task medium" href="orders.php?status=pending">
        📝 未処理注文<br>
        <span><?= $pendingOrders ?> 件</span>
    </a>

</div>

<!-- ================= NORMAL ================= -->
<h2 class="priority normal">🟢 通常業務</h2>
<div class="priority-grid">

    <a class="task normal" href="products.php">📦 商品管理</a>
    <a class="task normal" href="categories.php">🗂 カテゴリ管理</a>
    <a class="task normal" href="reviews.php">⭐ レビュー管理</a>
    <a class="task normal" href="users.php">👤 ユーザー管理</a>
    <a class="task normal" href="orders.php">🛒 注文管理</a>

</div>

<style>
body{background:#f1f5f9;font-family:system-ui}
.dashboard-container{
    max-width:1200px;
    margin:30px auto;
    padding:0 20px;
}
h1{margin-bottom:20px}

/* ===== Stats ===== */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:18px;
    margin-bottom:30px;
}
.box{
    background:#fff;
    padding:20px;
    border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}
.box p{font-size:26px;font-weight:800}

/* ===== Priority ===== */
.priority{
    margin:28px 0 10px;
    font-size:20px;
}
.priority.high{color:#b91c1c}
.priority.medium{color:#d97706}
.priority.normal{color:#166534}

.priority-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:16px;
}

.task{
    background:#fff;
    padding:18px;
    border-radius:14px;
    text-decoration:none;
    color:#111;
    font-weight:600;
    box-shadow:0 6px 16px rgba(0,0,0,.06);
    transition:.25s;
    display:flex;
    flex-direction:column;
    gap:6px;
}
.task span{
    font-size:18px;
    font-weight:800;
}
.task:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 22px rgba(0,0,0,.12);
}

.task.urgent{border-left:6px solid #dc2626}
.task.medium{border-left:6px solid #f59e0b}
.task.normal{border-left:6px solid #16a34a}
</style>

</div>
</body>
</html>
