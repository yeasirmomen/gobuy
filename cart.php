<?php
session_start();
require_once 'config.php';
require_once 'inc/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* =========================
   Load categories (OLD PHP SAFE)
========================= */
$cats = array();
$catRes = $conn->query("SELECT * FROM categories ORDER BY name");
while ($c = $catRes->fetch_assoc()) {
    $c['children'] = array(); // ✅ old php safe
    $cats[$c['id']] = $c;
}

foreach ($cats as $id => $c) {
    if (!empty($c['parent_id']) && isset($cats[$c['parent_id']])) {
        $cats[$c['parent_id']]['children'][] = $cats[$id];
    }
}

$categoryTree = array();
foreach ($cats as $id => $c) {
    if (empty($c['parent_id'])) $categoryTree[] = $c;
}

/* =========================
   Category render helper
========================= */
function renderCategory($cat) {
    echo '<li>';
    if (!empty($cat['children'])) {
        echo '<span class="toggle">＋</span>';
    } else {
        echo '<span class="spacer"></span>';
    }
    echo '<a href="menu.php?category_id='.$cat['id'].'">'.htmlspecialchars($cat['name']).'</a>';

    if (!empty($cat['children'])) {
        echo '<ul class="sub">';
        foreach ($cat['children'] as $ch) renderCategory($ch);
        echo '</ul>';
    }
    echo '</li>';
}

/* =========================
   Load user delivery info (SAFE)
========================= */
$u_name = '';
$u_phone = '';
$u_postal = '';
$u_address = '';

$uStmt = $conn->prepare("SELECT name, phone, postal_code, address FROM users WHERE id=? LIMIT 1");
if ($uStmt) {
    $uStmt->bind_param("i", $user_id);
    $uStmt->execute();
    $uRes = $uStmt->get_result();

    if ($uRes && $uRes->num_rows > 0) {
        $u = $uRes->fetch_assoc();
        $u_name    = isset($u['name']) ? $u['name'] : '';
        $u_phone   = isset($u['phone']) ? $u['phone'] : '';
        $u_postal  = isset($u['postal_code']) ? $u['postal_code'] : '';
        $u_address = isset($u['address']) ? $u['address'] : '';
    }
    $uStmt->close();
}

/* =========================
   Load cart items
========================= */
$sql = "
SELECT 
    c.id AS cart_id,
    c.quantity,
    p.id AS product_id,
    p.name,
    p.price,
    p.image
FROM cart c
JOIN products p ON c.product_id = p.id
WHERE c.user_id = $user_id
ORDER BY c.id DESC
";
$res = $conn->query($sql);

$total = 0;

/* =========================
   Estimate Delivery Date (2-5 days)
========================= */
$from = date("Y-m-d", strtotime("+2 days"));
$to   = date("Y-m-d", strtotime("+5 days"));

include 'header.php';
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ショッピングカート | GoBuyNow</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
body{
    margin:0;
    min-height:100vh;
    background:#070A12;
    position:relative;
    overflow-x:hidden;
    font-family:"Segoe UI", Arial, sans-serif;
}

/* glow blobs */
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
body::before{
    top:-120px;
    left:-160px;
    background:radial-gradient(circle, rgba(80,120,255,0.9), transparent 60%);
}
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

/* noise overlay */
.bg-noise{
    position:fixed;
    inset:0;
    pointer-events:none;
    z-index:-1;
    opacity:0.06;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)' opacity='.35'/%3E%3C/svg%3E");
}

.page-grid {
    max-width: 1400px;
    margin: 20px auto;
    display: grid;
    grid-template-columns: 260px 1fr 340px;
    gap: 18px;
    padding: 0 16px;
}

/* Sidebar */
.cat-box {
    background: rgba(255,255,255,0.14);
    border-radius: 16px;
    padding: 14px;
    max-height: calc(100vh - 140px);
    overflow-y: auto;

    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);

    border: 1px solid rgba(255,255,255,0.22);
    box-shadow: 0 12px 30px rgba(0,0,0,.25);
}
.cat-box h3{ margin:0 0 10px; color:#fff; }
.cat-box ul{ list-style:none; padding-left:0; margin:0; }
.cat-box li{ margin:6px 0; }
.cat-box a{
    text-decoration:none;
    color: rgba(255,255,255,0.92);
    font-size: 14px;
}
.toggle{
    cursor:pointer;
    margin-right:6px;
    font-weight:bold;
    color: rgba(255,255,255,0.9);
}
.spacer{display:inline-block;width:12px}
.sub{display:none;margin-left:14px}

/* Cart box */
.cart-box{
    background: rgba(20, 22, 32, 0.72);
    border: 1px solid rgba(255,255,255,0.10);
    color: rgba(255,255,255,0.92);

    padding: 18px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,.35);

    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
.cart-title{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:10px;
}
.cart-title h2{ margin:0; font-size:20px; }
.small-note{
    font-size:12px;
    color: rgba(255,255,255,0.65);
}

/* Delivery address */
.address-box{
    margin-top:12px;
    margin-bottom:14px;
    padding:14px;
    border-radius:16px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
}
.address-box h3{
    margin:0 0 10px;
    font-size:14px;
    color:#fff;
}
.address-row{
    display:flex;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
    font-size:13px;
    color: rgba(255,255,255,0.75);
}
.addr-btn{
    padding:9px 12px;
    border-radius:14px;
    text-decoration:none;
    font-weight:800;
    font-size:13px;
    color:#fff;
    background: rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.16);
    transition:.25s ease;
}
.addr-btn:hover{
    transform: translateY(-1px);
    box-shadow: 0 18px 40px rgba(0,0,0,.25);
}
.addr-muted{
    font-size:12px;
    opacity:.7;
    margin-top:6px;
}

/* Cart item */
.cart-item{
    display:grid;
    grid-template-columns: 120px 1fr 170px;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid rgba(255,255,255,0.10);
}
.cart-item:last-child{border-bottom:none}

.cart-item img{
    width: 120px;
    height: 120px;
    object-fit: contain;
    border-radius: 14px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
}

.cart-info h3{
    margin:0 0 6px;
    font-size:15px;
    line-height:1.25;
}
.cart-info p{
    margin: 4px 0;
    font-size: 13px;
    color: rgba(255,255,255,0.72);
}
.price-strong{
    color:#37d39b;
    font-weight:800;
}

.cart-actions{
    display:flex;
    flex-direction:column;
    gap:8px;
    align-items:flex-end;
}
.qty-row{
    display:flex;
    align-items:center;
    gap:8px;
}
.cart-actions input{
    width: 70px;
    padding: 8px 10px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.16);
    background: rgba(0,0,0,0.25);
    color:#fff;
    outline:none;
}

.action-btn{
    width: 100%;
    padding: 9px 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 800;
    border: 1px solid rgba(255,255,255,0.16);
    cursor:pointer;
    transition: 0.25s ease;

    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}
.btn-update{
    background: rgba(31,111,235,0.25);
    color:#fff;
}
.btn-update:hover{
    transform: translateY(-1px);
    box-shadow: 0 16px 35px rgba(31,111,235,0.18);
}
.btn-remove{
    background: rgba(239,68,68,0.18);
    color:#fff;
    text-decoration:none;
    text-align:center;
}
.btn-remove:hover{
    transform: translateY(-1px);
    box-shadow: 0 16px 35px rgba(239,68,68,0.18);
}

/* Summary */
.summary-box{
    background: rgba(20, 22, 32, 0.72);
    border: 1px solid rgba(255,255,255,0.10);
    color: rgba(255,255,255,0.92);

    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,.35);

    position: sticky;
    top: 18px;

    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
.summary-box h3{ margin:0 0 12px; font-size:16px; }
.summary-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
    font-size:13px;
    color: rgba(255,255,255,0.75);
}
.summary-total{
    font-size:18px;
    font-weight:900;
    color:#fff;
    margin-top:12px;
    padding-top:12px;
    border-top:1px solid rgba(255,255,255,0.10);
}
.checkout-btn{
    display:block;
    margin-top:14px;
    padding:12px;
    border-radius:14px;
    text-align:center;
    font-weight:900;
    text-decoration:none;
    color:#fff;

    background: linear-gradient(135deg, rgba(31,111,235,1), rgba(170,90,255,1));
    box-shadow: 0 18px 40px rgba(31,111,235,0.18);
    transition: 0.25s ease;
}
.checkout-btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 22px 55px rgba(170,90,255,0.20);
}

@media(max-width:1100px){
    .page-grid { grid-template-columns: 1fr; }
    .summary-box { position:static; }
    .cart-item{ grid-template-columns: 110px 1fr; }
    .cart-actions{ align-items:flex-start; }
}
</style>
</head>

<body>
<div class="bg-noise"></div>

<div class="page-grid">

<!-- Categories -->
<aside class="cat-box">
    <h3>カテゴリ</h3>
    <ul>
        <?php foreach ($categoryTree as $c) renderCategory($c); ?>
    </ul>
</aside>

<!-- Cart -->
<main class="cart-box">
    <div class="cart-title">
        <h2>🛒 ショッピングカート</h2>
        <div class="small-note">数量を変更して「更新」を押してください</div>
    </div>

    <!-- Delivery -->
    <div class="address-box">
        <h3>📦 配送先情報</h3>

        <div class="address-row">
            <div>
                <div><b>お名前:</b> <?php echo htmlspecialchars($u_name); ?></div>
                <div><b>電話番号:</b> <?php echo htmlspecialchars($u_phone); ?></div>
                <div><b>郵便番号:</b> <?php echo htmlspecialchars($u_postal); ?></div>
                <div><b>住所:</b> <?php echo nl2br(htmlspecialchars($u_address)); ?></div>

                <div class="addr-muted">
                    ⏱ お届け予定日: <b><?php echo $from; ?></b> ～ <b><?php echo $to; ?></b><br>
                    ※ 配送先は「マイページ」で変更できます。
                </div>
            </div>

            <div>
                <a class="addr-btn" href="profile.php">住所を変更</a>
            </div>
        </div>
    </div>

    <?php if (!$res || $res->num_rows == 0): ?>
        <div style="padding:16px;border-radius:14px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);">
            <p style="margin:0;color:#fff;">カートに商品がありません。</p>
            <a href="menu.php" style="display:inline-block;margin-top:10px;padding:10px 14px;border-radius:14px;text-decoration:none;font-weight:800;color:#fff;background:rgba(0,0,0,0.25);border:1px solid rgba(255,255,255,0.14);">買い物を続ける</a>
        </div>
    <?php else: ?>

        <?php while ($row = $res->fetch_assoc()): ?>
        <?php
            $img = '';

            $imgRes = $conn->query(
                "SELECT image_path FROM product_images 
                 WHERE product_id=".(int)$row['product_id']." 
                 ORDER BY is_primary DESC, id ASC LIMIT 1"
            );

            if ($imgRes && $imgRes->num_rows) {
                $tmp = $imgRes->fetch_assoc();
                $img = normalized_image_url($tmp['image_path']);
            } elseif (!empty($row['image'])) {
                $img = normalized_image_url($row['image']);
            } else {
                $img = 'uploads/placeholder.png';
            }

            $sub = $row['price'] * $row['quantity'];
            $total += $sub;
        ?>

        <div class="cart-item">
            <img src="<?php echo htmlspecialchars($img); ?>" alt="product">

            <div class="cart-info">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p>価格: <span class="price-strong">¥<?php echo number_format($row['price']); ?></span></p>
                <p>小計: <span class="price-strong">¥<?php echo number_format($sub); ?></span></p>
            </div>

            <div class="cart-actions">
                <form method="post" action="update_cart.php" class="updateForm">
                    <input type="hidden" name="cart_id" value="<?php echo (int)$row['cart_id']; ?>">
                    <div class="qty-row">
                        <input type="number" name="quantity" value="<?php echo (int)$row['quantity']; ?>" min="1">
                        <button class="action-btn btn-update" type="submit">更新</button>
                    </div>
                </form>

                <a class="action-btn btn-remove"
                   href="update_cart.php?remove=<?php echo (int)$row['cart_id']; ?>"
                   onclick="return confirm('この商品を削除しますか？');">
                   削除
                </a>
            </div>
        </div>

        <?php endwhile; ?>
    <?php endif; ?>
</main>

<!-- Summary -->
<aside class="summary-box">
    <h3>注文概要</h3>

    <div class="summary-row">
        <span>商品合計</span>
        <span>¥<?php echo number_format($total); ?></span>
    </div>

    <div class="summary-row">
        <span>送料</span>
        <span>¥0</span>
    </div>

    <div class="summary-total summary-row">
        <span>お支払い金額</span>
        <span>¥<?php echo number_format($total); ?></span>
    </div>

    <a class="checkout-btn" href="checkout.php">購入手続きへ</a>
</aside>

</div>

<script>
document.querySelectorAll('.toggle').forEach(function(t){
    t.onclick = function(){
        var ul = t.parentNode.querySelector('.sub');
        if(!ul) return;
        ul.style.display = (ul.style.display === 'block') ? 'none' : 'block';
        t.textContent = (ul.style.display === 'block') ? '−' : '＋';
    };
});

window.addEventListener("load", function(){
    if (typeof gsap !== "undefined") {
        gsap.from(".cat-box", {opacity:0, x:-30, duration:0.6, ease:"power2.out"});
        gsap.from(".cart-box", {opacity:0, y:40, duration:0.7, ease:"power3.out"});
        gsap.from(".summary-box", {opacity:0, x:30, duration:0.7, ease:"power3.out"});
        gsap.from(".cart-item", {opacity:0, y:30, duration:0.6, stagger:0.08, ease:"power2.out"});
        gsap.from(".address-box", {opacity:0, y:25, duration:0.6, ease:"power2.out"});
    }
});

document.querySelectorAll(".updateForm").forEach(function(form){
    form.addEventListener("submit", function(){
        var btn = form.querySelector("button");
        if(btn){
            btn.disabled = true;
            btn.textContent = "更新中...";
        }
    });
});
</script>

</body>
</html>
