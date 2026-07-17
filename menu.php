<?php
session_start();
require_once 'config.php';
require_once 'inc/functions.php';
include 'header.php';

/* =========================
   Categories (Tree)
========================= */
$cats = [];
$catRes = $conn->query("SELECT * FROM categories ORDER BY name");
while ($c = $catRes->fetch_assoc()) {
    $c['children'] = [];
    $cats[$c['id']] = $c;
}
foreach ($cats as $id => $c) {
    if (!empty($c['parent_id']) && isset($cats[$c['parent_id']])) {
        $cats[$c['parent_id']]['children'][] = &$cats[$id];
    }
}
$categoryTree = [];
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

/* =====================
   BEST SELLER (Top 5)
   Real system: orders + order_items
   ✅ FIXED: product_images primary image support
===================== */
$bestSellers = $conn->query("
    SELECT 
        p.id,
        p.name,
        p.price,
        COALESCE(pi.image_path, p.image) AS best_image,
        SUM(oi.quantity) AS sold_qty
    FROM order_items oi
    JOIN orders o ON o.id = oi.order_id
    JOIN products p ON p.id = oi.product_id
    LEFT JOIN product_images pi 
        ON pi.product_id = p.id AND pi.is_primary = 1
    WHERE COALESCE(p.is_hidden,0)=0
      AND o.status IN ('Completed','Delivered','Paid')
    GROUP BY p.id
    ORDER BY sold_qty DESC
    LIMIT 5
");

/* =====================
   Filters (Category + Search)
===================== */
$where = ["COALESCE(p.is_hidden,0)=0"];

if (!empty($_GET['category_id'])) {
    $where[] = "p.category=".(int)$_GET['category_id'];
}

/* Search */
$q = '';
if (!empty($_GET['q'])) {
    $q = trim($_GET['q']);
    $safe = $conn->real_escape_string($q);
    $where[] = "(p.name LIKE '%$safe%' OR p.description LIKE '%$safe%')";
}

$whereSQL = "WHERE ".implode(" AND ", $where);

/* =====================
   Pagination (12 per page)
===================== */
$perPage = 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

$countSql = "SELECT COUNT(*) AS total FROM products p $whereSQL";
$countRes = $conn->query($countSql);
$totalRows = ($countRes && $countRes->num_rows) ? (int)$countRes->fetch_assoc()['total'] : 0;
$totalPages = ($totalRows > 0) ? (int)ceil($totalRows / $perPage) : 1;

/* =====================
   Products
===================== */
$sql = "
SELECT p.*, c.name AS category_name
FROM products p
LEFT JOIN categories c ON p.category=c.id
$whereSQL
ORDER BY p.id DESC
LIMIT $perPage OFFSET $offset
";
$products = $conn->query($sql);
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>商品一覧</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
body{
    margin:0;
    min-height:100vh;
    background:#070A12;
    position:relative;
    overflow-x:hidden;
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

/* Noise overlay */
.bg-noise{
    position:fixed;
    inset:0;
    pointer-events:none;
    z-index:-1;
    opacity:0.06;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)' opacity='.35'/%3E%3C/svg%3E");
}

/* Layout */
.shop-layout{
    display:grid;
    grid-template-columns:260px 1fr;
    gap:18px;
    max-width:1400px;
    margin:20px auto;
    padding:0 16px;
}

/* Sidebar Glass */
.sidebar{
    background: rgba(255,255,255,0.14);
    border-radius: 16px;
    padding: 14px;
    height: calc(100vh - 140px);
    overflow: auto;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.22);
    box-shadow: 0 12px 30px rgba(0,0,0,.25);
}
.sidebar h3{color:#fff;margin:10px 0 10px;font-size:15px}
.sidebar ul{list-style:none;padding-left:0;margin:0}
.sidebar li{margin:6px 0}
.sidebar a{
    text-decoration:none;
    color: rgba(255,255,255,0.92);
    font-size: 14px;
    transition: 0.2s;
}
.sidebar a:hover{
    color:#ffffff;
    text-shadow: 0 0 10px rgba(255,255,255,0.25);
}
.toggle{
    cursor:pointer;
    margin-right:6px;
    font-weight:bold;
    color: rgba(255,255,255,0.9);
}
.spacer{display:inline-block;width:12px}
.sub{display:none;margin-left:14px}
.sub a{
    display:inline-block;
    padding:4px 8px;
    border-radius:10px;
}
.sub a:hover{
    background: rgba(255,255,255,0.12);
}

/* 🔥 Best Seller Box */
.best-box{
    padding:12px;
    border-radius:16px;
    background: rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.12);
    margin-bottom:14px;
}
.best-title{
    font-weight:900;
    margin:0 0 10px;
    font-size:14px;
    display:flex;
    align-items:center;
    gap:8px;
    color:#fff;
}
.best-item{
    display:flex;
    gap:10px;
    align-items:center;
    padding:8px;
    border-radius:14px;
    text-decoration:none;
    border: 1px solid rgba(255,255,255,0.10);
    background: rgba(255,255,255,0.06);
    margin-bottom:8px;
    transition:.25s ease;
}
.best-item:hover{
    transform: translateY(-2px);
    box-shadow: 0 18px 40px rgba(0,0,0,.30);
}
.best-item img{
    width:42px;
    height:42px;
    border-radius:12px;
    object-fit:contain;
    background: rgba(255,255,255,0.06);
}
.best-name{
    font-size:13px;
    font-weight:800;
    color:#fff;
    line-height:1.2;
}
.best-meta{
    font-size:11px;
    opacity:.75;
    margin-top:3px;
    color:#fff;
}

/* Products Grid */
.products-grid{
    display:grid;
    grid-template-columns:repeat(4,210px);
    gap:12px;
    justify-content:center;
    perspective:1200px;
}

/* Card */
.card{
    background: rgba(20, 22, 32, 0.72);
    border: 1px solid rgba(255,255,255,0.10);
    color: rgba(255,255,255,0.92);
    padding:8px;
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,.35);
    display:flex;
    flex-direction:column;
    position:relative;
    overflow:hidden;
    transform-style:preserve-3d;
    transition:transform .35s ease, box-shadow .35s ease;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
.card img{
    height:135px;
    object-fit:contain;
    background: rgba(255,255,255,0.06);
    border-radius:12px;
    cursor:pointer;
    transition:transform .35s ease, filter .35s ease;
}
.card:hover{
    transform:translateY(-10px);
    box-shadow:0 22px 60px rgba(0,0,0,.45);
}
.card:hover img{
    transform:scale(1.06);
    filter:drop-shadow(0 10px 18px rgba(0,0,0,.35));
}
.card h4{
    font-size:14px;
    height:34px;
    overflow:hidden;
    margin:6px 0 2px;
}
.rating-row{
    display:flex;
    align-items:center;
    gap:6px;
    flex-wrap:wrap;
}
.stars{
    font-size:13px;
    letter-spacing:1px;
    color:#f5b301;
}
.meta{
    font-size:11px;
    color: rgba(255,255,255,0.70);
}
.price{
    font-weight:bold;
    color:#37d39b;
    margin:6px 0 6px;
}
.btn{
    margin-top:auto;
    padding:8px;
    background: rgba(0,0,0,0.35);
    border: 1px solid rgba(255,255,255,0.16);
    color:#fff;
    border-radius:12px;
    cursor:pointer;
    font-weight:800;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    transition:transform .25s ease, box-shadow .25s ease;
}
.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 28px rgba(0,0,0,.35);
}

/* pagination */
.pagination{
    margin:18px auto 0;
    display:flex;
    justify-content:center;
    gap:8px;
    flex-wrap:wrap;
}
.pagination a{
    text-decoration:none;
    padding:8px 12px;
    border-radius:12px;
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.18);
    color:#fff;
    backdrop-filter: blur(10px);
}
.pagination a.active{
    background: linear-gradient(135deg, rgba(31,111,235,1), rgba(170,90,255,1));
    border:none;
}

/* footer */
.site-footer{
    margin-top:26px;
    padding:16px 12px;
    text-align:center;
    color: rgba(255,255,255,0.75);
}
.site-footer .footer-glass{
    display:inline-block;
    padding:10px 18px;
    border-radius:16px;
    background: rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.16);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    box-shadow:0 10px 30px rgba(0,0,0,.35);
}

@media(max-width:1200px){
    .products-grid{grid-template-columns:repeat(3,210px)}
}
@media(max-width:900px){
    .shop-layout{grid-template-columns:1fr}
    .products-grid{grid-template-columns:repeat(2,200px)}
}
</style>
</head>

<body>

<div class="bg-noise"></div>

<div class="shop-layout">

<!-- Sidebar -->
<aside class="sidebar">

    <!-- 🔥 Best Seller on TOP -->
    <div class="best-box">
        <div class="best-title">🔥 Best Seller (Top 5)</div>

        <?php if($bestSellers && $bestSellers->num_rows > 0): ?>
            <?php while($b = $bestSellers->fetch_assoc()): ?>
                <?php
                    $bimg = !empty($b['best_image']) ? normalized_image_url($b['best_image']) : 'uploads/placeholder.png';
                ?>
                <a class="best-item" href="product_details.php?id=<?= (int)$b['id'] ?>">
                    <img src="<?= htmlspecialchars($bimg) ?>" alt="">
                    <div>
                        <div class="best-name"><?= htmlspecialchars($b['name']) ?></div>
                        <div class="best-meta">⭐ 5.0 • Sold <?= (int)$b['sold_qty'] ?> • ¥<?= number_format($b['price']) ?></div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="font-size:12px;opacity:.75;color:#fff;">まだ販売データがありません。</div>
        <?php endif; ?>
    </div>

    <h3>カテゴリ</h3>
    <ul>
        <?php foreach ($categoryTree as $c) renderCategory($c); ?>
    </ul>

</aside>

<!-- Products -->
<main>

<div class="products-grid">

<?php while ($p = $products->fetch_assoc()):

    $img = '';

    $stmt = $conn->prepare("
        SELECT image_path 
        FROM product_images 
        WHERE product_id = ?
        ORDER BY is_primary DESC, id ASC
        LIMIT 1
    ");
    if($stmt){
        $pid = (int)$p['id'];
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->bind_result($imgPath);
        if ($stmt->fetch()) $img = $imgPath;
        $stmt->close();
    }

    if (!$img && !empty($p['image'])) $img = $p['image'];

    $reviews = rand(10, 250);
    $sold    = rand(50, 900);
?>

<div class="card">
    <img src="<?= htmlspecialchars(normalized_image_url($img)) ?>"
         class="product-image"
         onclick="location.href='product_details.php?id=<?= (int)$p['id'] ?>'">

    <h4><?= htmlspecialchars($p['name']) ?></h4>

    <div class="rating-row">
        <div class="stars">★★★★★</div>
        <div class="meta">(<?= $reviews ?> reviews • <?= $sold ?> sold)</div>
    </div>

    <div class="price">¥<?= number_format($p['price']) ?></div>

    <?php if ((int)$p['stock'] > 0): ?>
        <button class="btn" onclick="addToCartFromMenu(<?= (int)$p['id'] ?>, this)">カートに追加</button>
    <?php else: ?>
        <div style="color:#ff5a5a;font-weight:bold">売り切れ</div>
    <?php endif; ?>
</div>

<?php endwhile; ?>

</div>

<!-- Pagination -->
<div class="pagination">
<?php
$params = $_GET;
for ($i=1; $i <= $totalPages; $i++):
    $params['page'] = $i;
    $link = 'menu.php?' . http_build_query($params);
?>
    <a href="<?= htmlspecialchars($link) ?>" class="<?= ($i==$page)?'active':'' ?>">
        <?= $i ?>
    </a>
<?php endfor; ?>
</div>

</main>

</div>

<footer class="site-footer">
  <div class="footer-glass">
    © 2026 GoBuyNow. All Rights Reserved.
  </div>
</footer>

<script>
/* Category Toggle */
document.querySelectorAll('.toggle').forEach(t=>{
    t.onclick=()=>{
        const ul=t.parentNode.querySelector('.sub');
        if(!ul)return;
        ul.style.display = ul.style.display==='block'?'none':'block';
        t.textContent = ul.style.display==='block'?'−':'＋';
    };
});

/* Add to cart (Fix double add) */
function addToCartFromMenu(pid, btn){
    if(btn.dataset.loading === "1") return;
    btn.dataset.loading = "1";

    const oldText = btn.innerHTML;
    btn.innerHTML = "追加中...";
    btn.style.opacity = "0.75";
    btn.style.pointerEvents = "none";

    const fd = new FormData();
    fd.append('product_id',pid);
    fd.append('qty',1);
    fd.append('ajax',1);

    fetch('add_to_cart.php',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(j=>{
        if(j && j.success){
            if(typeof updateCartBadge==='function'){
                updateCartBadge(j.cart_count);
            }
            btn.innerHTML = "追加済み ✓";
            setTimeout(()=>btn.innerHTML=oldText, 1000);
        }else{
            alert("カート追加失敗しました");
            btn.innerHTML = oldText;
        }
    })
    .catch(()=>{
        alert("Network Error");
        btn.innerHTML = oldText;
    })
    .finally(()=>{
        btn.dataset.loading = "0";
        btn.style.opacity = "1";
        btn.style.pointerEvents = "auto";
    });
}
</script>

</body>
</html>
