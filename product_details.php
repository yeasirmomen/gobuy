<?php
session_start();
require_once 'config.php';
require_once 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('商品が見つかりません');
}

$product_id = (int)$_GET['id'];

/* ================= PRODUCT ================= */
$pq = $conn->query("
SELECT p.*, c.name AS category_name
FROM products p
LEFT JOIN categories c ON p.category=c.id
WHERE p.id=$product_id
");
if ($pq->num_rows === 0) die('商品が存在しません');
$product = $pq->fetch_assoc();

/* ================= IMAGES ================= */
$images = fetch_product_images($conn, $product_id);

/* ✅ fallback if no product_images rows */
if (empty($images)) {
    if (!empty($product['image'])) {
        $images[] = ['image_path' => $product['image']];
    }
}

/* ✅ Final fallback image if still empty */
if (empty($images)) {
    $images[] = ['image_path' => 'no-image.png'];
}

/* ================= LIKE ================= */
$userLiked = false;
if (isset($_SESSION['user_id'])) {
    $uid = (int)$_SESSION['user_id'];
    $lr = $conn->query("SELECT id FROM product_likes WHERE product_id=$product_id AND user_id=$uid");
    if ($lr && $lr->num_rows > 0) $userLiked = true;
}

/* ================= COMMENTS ================= */
$comments = $conn->query("
SELECT r.comment, r.created_at,
COALESCE(u.name,'退会ユーザー') AS user_name
FROM product_reviews r
LEFT JOIN users u ON r.user_id=u.id
WHERE r.product_id=$product_id
ORDER BY r.id DESC
");

/* ================= SIMILAR ================= */
$similar = $conn->query("
SELECT id,name,price,image
FROM products
WHERE category=".$product['category']." AND id<>$product_id
AND COALESCE(is_hidden,0)=0
ORDER BY id DESC
LIMIT 4
");

include 'header.php';
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($product['name']) ?></title>

<style>
/* ✅ IMPORTANT FIX: background overlay should NOT block click */
body::before,
body::after,
.bg-noise{
    pointer-events:none !important;
}

.container{max-width:1200px;margin:20px auto;padding:0 16px; position:relative; z-index:10;}
.product-wrap{display:grid;grid-template-columns:1fr 1fr;gap:30px}

/* =========================
   Gallery
========================= */
.gallery{
    background:#fff;
    padding:16px;
    border-radius:12px;
    position:relative;
    z-index:20;
    overflow:visible;
}

.gallery img{
    width:100%;
    height:360px;
    object-fit:contain;
    display:block;
}

/* ✅ thumbs always visible */
.thumbs{
    display:flex;
    gap:8px;
    margin-top:12px;
    flex-wrap:wrap;
    overflow:visible;
    position:relative;
    z-index:25;
}

.thumbs img{
    width:60px;
    height:60px;
    cursor:pointer;
    border:2px solid #eee;
    border-radius:8px;
    object-fit:cover;
    background:#fff;
    transition:.2s;
}
.thumbs img:hover{
    transform:scale(1.06);
    border-color:#2563eb;
}

/* =========================
   Right panel
========================= */
.details{display:flex;flex-direction:column; position:relative; z-index:20;}
.details h1{font-size:22px;margin-bottom:6px;color:#fff}
.details div{color:rgba(255,255,255,0.85)}
.price{font-size:22px;font-weight:bold;color:#37d39b;margin:10px 0}

.btn{
    padding:10px 16px;
    border-radius:10px;
    border:none;
    background:linear-gradient(135deg, rgba(31,111,235,1), rgba(170,90,255,1));
    color:#fff;
    cursor:pointer;
    font-weight:800;
    transition:.25s ease;
}
.btn:hover{transform:translateY(-2px);filter:brightness(1.05)}

.like{
    background:none;border:none;font-size:22px;color:#bbb;
    cursor:pointer;margin-left:10px;transition:.2s;
}
.like.active{color:#ff4b4b}

.desc{margin-top:16px;line-height:1.7;color:rgba(255,255,255,0.85)}

/* =========================
   Review panel
========================= */
.comment-panel{
    margin-top:18px;
    border:1px solid rgba(255,255,255,0.12);
    border-radius:14px;
    padding:12px;
    background:rgba(20,22,32,0.70);
    backdrop-filter:blur(12px);
    -webkit-backdrop-filter:blur(12px);
    box-shadow:0 16px 45px rgba(0,0,0,.35);
}
.comment-panel h3{font-size:15px;margin:0 0 10px;color:#fff}
.comment-list{max-height:180px;overflow-y:auto;padding-right:6px}

.review{border-bottom:1px solid rgba(255,255,255,0.12);padding:8px 0}
.review:last-child{border-bottom:none}
.review small{color:rgba(255,255,255,0.55);font-size:11px}

textarea{
    width:100%;
    min-height:60px;
    padding:10px 12px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,0.14);
    font-size:13px;
    background:rgba(255,255,255,0.06);
    color:#fff;
    outline:none;
    box-sizing:border-box;
}
textarea::placeholder{color:rgba(255,255,255,0.45)}
.comment-btn{margin-top:8px;padding:8px 12px;font-size:13px}

/* =========================
   Similar Products
========================= */
.section{margin-top:40px}
.section h2{color:#fff;margin-bottom:14px}

.similar{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
    gap:16px;
    position:relative;
    z-index:20;
}

.similar .card{
    background:rgba(20,22,32,0.72);
    border:1px solid rgba(255,255,255,0.12);
    padding:12px;
    border-radius:14px;
    text-align:center;
    box-shadow:0 16px 45px rgba(0,0,0,.35);
    backdrop-filter:blur(12px);
    -webkit-backdrop-filter:blur(12px);
    transition:.25s ease;
    cursor:pointer;
}
.similar .card:hover{transform:translateY(-6px);box-shadow:0 22px 60px rgba(0,0,0,.45)}

.similar .card a{
    display:block;
    text-decoration:none;
    color:inherit;
}

.similar img{
    height:140px;
    object-fit:contain;
    width:100%;
    background:rgba(255,255,255,0.06);
    border-radius:12px;
    padding:6px;
}

.similar .pname{
    margin-top:8px;
    font-weight:700;
    font-size:14px;
    color:rgba(255,255,255,0.92);
}
.similar .pprice{
    margin-top:4px;
    font-weight:800;
    color:#37d39b;
}

@media(max-width:900px){
    .product-wrap{grid-template-columns:1fr}
    .gallery img{height:280px}
}
</style>
</head>

<body>

<div class="container">

<div class="product-wrap">

<!-- ========= LEFT : IMAGES ========= -->
<div class="gallery">

    <!-- ✅ main image always show -->
    <img id="mainImg"
         src="<?= normalized_image_url($images[0]['image_path']) ?>"
         onerror="this.src='no-image.png'">

    <!-- ✅ thumbs -->
    <div class="thumbs">
        <?php if (!empty($images)): ?>
            <?php foreach($images as $img): ?>
                <img src="<?= normalized_image_url($img['image_path']) ?>"
                     onerror="this.style.display='none'"
                     onclick="document.getElementById('mainImg').src=this.src">
            <?php endforeach; ?>
        <?php else: ?>
            <div style="color:#444;font-size:13px;">No thumbnails</div>
        <?php endif; ?>
    </div>

</div>

<!-- ========= RIGHT : DETAILS + COMMENTS ========= -->
<div class="details">

    <h1><?= htmlspecialchars($product['name']) ?></h1>
    <div><?= htmlspecialchars($product['category_name']) ?></div>
    <div class="price">¥<?= number_format($product['price']) ?></div>

    <div>
        <button class="btn" onclick="addToCart(<?= $product_id ?>)">カートに追加</button>
        <button class="like <?= $userLiked?'active':'' ?>" onclick="toggleLike(this,<?= $product_id ?>)">♥</button>
    </div>

    <div class="desc">
        <?= nl2br(htmlspecialchars($product['description'])) ?>
    </div>

    <div class="comment-panel">
        <h3>レビュー</h3>

        <div class="comment-list">
        <?php if ($comments->num_rows==0): ?>
            <p style="font-size:13px;color:rgba(255,255,255,0.7)">まだレビューはありません。</p>
        <?php endif; ?>

        <?php while($c=$comments->fetch_assoc()): ?>
            <div class="review">
                <strong><?= htmlspecialchars($c['user_name']) ?></strong><br>
                <small><?= $c['created_at'] ?></small>
                <div><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
            </div>
        <?php endwhile; ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
        <form method="post" action="submit_review.php">
            <textarea name="comment" required placeholder="コメントを書く"></textarea>
            <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <button class="btn comment-btn">投稿</button>
        </form>
        <?php endif; ?>
    </div>

</div>

</div>

<!-- ========= SIMILAR ========= -->
<div class="container section">
    <h2>関連商品</h2>

    <div class="similar">
    <?php while($s=$similar->fetch_assoc()): ?>
        <div class="card" onclick="location.href='product_details.php?id=<?= $s['id'] ?>'">
            <a href="product_details.php?id=<?= $s['id'] ?>">
                <img src="<?= normalized_image_url($s['image']) ?>" onerror="this.src='no-image.png'">
                <div class="pname"><?= htmlspecialchars($s['name']) ?></div>
                <div class="pprice">¥<?= number_format($s['price']) ?></div>
            </a>
        </div>
    <?php endwhile; ?>
    </div>
</div>

</div>

<script>
function addToCart(id){
    const f=new FormData();
    f.append('product_id',id);
    f.append('qty',1);
    f.append('ajax',1);

    fetch('add_to_cart.php',{method:'POST',body:f})
    .then(r=>r.json())
    .then(j=>{
        if(typeof updateCartBadge==='function'){
            updateCartBadge(j.cart_count);
        }
    });
}

function toggleLike(btn,id){
    fetch('toggle_like.php',{method:'POST',body:new URLSearchParams({product_id:id})})
    .then(()=>btn.classList.toggle('active'));
}
</script>

</body>
</html>
