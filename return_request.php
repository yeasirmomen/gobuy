<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
include 'config.php';
require_once 'inc/functions.php';
include 'header.php';

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = $_POST['reason'];
    $image = '';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $img_name = time().'_'.basename($_FILES['image']['name']);
        $target = 'uploads/returns/'.$img_name;

        if (!file_exists('uploads/returns')) {
            mkdir('uploads/returns', 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $img_name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO returns (order_id, product_id, user_id, reason, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $order_id, $product_id, $user_id, $reason, $image);

    if ($stmt->execute()) {
        $msg = "✅ 返品申請が送信されました。";
    } else {
        $msg = "❌ 返品申請エラー: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>返品申請</title>

<style>
/* =========================
   Menu Style Background
========================= */
body{
    margin:0;
    min-height:100vh;
    background:#070A12;
    position:relative;
    overflow-x:hidden;
    color:#fff;
    font-family:system-ui,Segoe UI,Arial;
}

/* Moving glow blobs */
body::before,
body::after{
    content:"";
    position:fixed;
    width:520px;
    height:520px;
    border-radius:50%;
    filter:blur(120px);
    opacity:0.55;
    z-index:-2;
    animation: blobMove 10s ease-in-out infinite alternate;
    pointer-events:none;
}

/* Blue glow */
body::before{
    top:-120px;
    left:-160px;
    background:radial-gradient(circle, rgba(80,120,255,0.9), transparent 60%);
}

/* Purple glow */
body::after{
    bottom:-160px;
    right:-200px;
    background:radial-gradient(circle, rgba(170,90,255,0.9), transparent 60%);
    animation-duration:12s;
}

@keyframes blobMove{
    0%   { transform: translate(0px,0px) scale(1); }
    50%  { transform: translate(120px,80px) scale(1.1); }
    100% { transform: translate(-60px,120px) scale(0.95); }
}

/* Noise overlay */
.bg-noise{
    position:fixed;
    inset:0;
    pointer-events:none;
    z-index:-1;
    opacity:0.06;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)' opacity='.35'/%3E%3C/svg%3E");
}

/* =========================
   Return Form Card
========================= */
.page-wrap{
    max-width:520px;
    margin:28px auto;
    padding:0 14px;
}

.card{
    background: rgba(20, 22, 32, 0.78);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 22px 60px rgba(0,0,0,.45);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
}

h2{
    margin:0 0 12px;
    font-size:20px;
    text-align:center;
}

label{
    display:block;
    margin-top:12px;
    margin-bottom:6px;
    font-weight:700;
    font-size:14px;
    color: rgba(255,255,255,0.9);
}

select, input[type="file"]{
    width:100%;
    padding:10px 12px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,0.14);
    background: rgba(255,255,255,0.06);
    color:#fff;
    outline:none;
}

select option{
    color:#111;
}

.btn-submit{
    width:100%;
    margin-top:16px;
    padding:12px 14px;
    border:none;
    border-radius:12px;
    cursor:pointer;
    font-weight:800;
    color:#fff;
    background: linear-gradient(135deg, rgba(239,68,68,1), rgba(170,90,255,1));
    transition:.25s ease;
}

.btn-submit:hover{
    transform: translateY(-2px);
    filter: brightness(1.05);
    box-shadow: 0 18px 35px rgba(239,68,68,0.22);
}

.msg{
    margin:10px 0 14px;
    padding:12px 12px;
    border-radius:14px;
    font-weight:800;
    text-align:center;
    background: rgba(31,111,235,0.12);
    border: 1px solid rgba(31,111,235,0.22);
}
</style>
</head>

<body>

<div class="bg-noise"></div>

<div class="page-wrap">
    <div class="card">

        <h2>返品申請</h2>

        <?php if($msg): ?>
            <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">

            <label>返品理由</label>
            <select name="reason" required>
                <option value="誤った商品が届いた">誤った商品が届いた</option>
                <option value="商品が破損している">商品が破損している</option>
                <option value="付属品が不足している">付属品が不足している</option>
                <option value="説明と違う">説明と違う</option>
                <option value="色・サイズが違う">色・サイズが違う</option>
                <option value="不良品（動作しない）">不良品（動作しない）</option>
                <option value="期限切れの商品">期限切れの商品</option>
                <option value="品質が期待以下">品質が期待以下</option>
                <option value="梱包に問題がある">梱包に問題がある</option>
                <option value="配送が遅い">配送が遅い</option>
                <option value="注文ミス">注文ミス</option>
                <option value="重複注文">重複注文</option>
                <option value="他店の方が安かった">他店の方が安かった</option>
                <option value="気が変わった">気が変わった</option>
                <option value="その他">その他</option>
            </select>

            <label>画像アップロード（任意）</label>
            <input type="file" name="image">

            <button class="btn-submit" type="submit">返品申請を送信</button>
        </form>

    </div>
</div>

</body>
</html>
