<?php
session_start();
require 'config.php';
require 'inc/functions.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) die("ログインが必要です");

$uid = (int)$_SESSION['user_id'];
$res = $conn->query(
"SELECT p.* FROM product_likes l
 JOIN products p ON l.product_id=p.id
 WHERE l.user_id=$uid"
);
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>お気に入り商品</title>

<!-- GSAP animation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
/* =========================
   Dribbble Video Style Background
========================= */
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

/* =========================
   Page Title
========================= */
.page-title{
    text-align:center;
    color:#fff;
    margin:18px 0 10px;
    font-size:22px;
    font-weight:700;
}

/* =========================
   Products Grid
========================= */
.products-grid{
    display:grid;
    grid-template-columns:repeat(4,210px);
    gap:12px;
    justify-content:center;
    perspective:1200px;
    padding: 10px 0 40px;
}

/* Premium Card */
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
    will-change:transform;

    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

/* glow overlay */
.card::before{
    content:"";
    position:absolute;
    inset:-2px;
    background:
        radial-gradient(circle at 30% 20%, rgba(31,111,235,.35), transparent 55%),
        radial-gradient(circle at 80% 90%, rgba(170,90,255,.28), transparent 60%);
    opacity:0.75;
    pointer-events:none;
}

/* shine on hover */
.card::after{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg, transparent 0%, rgba(255,255,255,.18) 20%, transparent 40%);
    transform:translateX(-120%);
    transition:transform .6s ease;
    pointer-events:none;
    opacity:0;
}

.card:hover{
    transform:translateY(-10px) rotateX(6deg) rotateY(-6deg);
    box-shadow:0 22px 60px rgba(0,0,0,.45);
}
.card:hover::after{
    transform:translateX(120%);
    opacity:.45;
}

/* image */
.card img{
    height:135px;
    width:100%;
    object-fit:contain;
    background: rgba(255,255,255,0.06);
    border-radius:12px;
    cursor:pointer;

    transition:transform .35s ease, filter .35s ease;
    transform:translateZ(30px);
}
.card:hover img{
    transform:translateZ(45px) scale(1.06);
    filter:drop-shadow(0 10px 18px rgba(0,0,0,.35));
}

.card a{
    text-decoration:none;
}

/* title */
.card h4{
    font-size:14px;
    height:34px;
    overflow:hidden;
    margin:6px 0 2px;
    transform:translateZ(25px);
    color:#fff;
}

/* price */
.price{
    font-weight:bold;
    color:#37d39b;
    margin:6px 0 8px;
    transform:translateZ(25px);
}

/* button */
.btn{
    margin-top:auto;
    padding:7px;
    background: linear-gradient(135deg, rgba(31,111,235,1), rgba(170,90,255,1));
    color:#fff;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:700;

    transition:transform .25s ease, box-shadow .25s ease, filter .25s ease;
}
.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 28px rgba(31,111,235,.25);
    filter:brightness(1.05);
}

/* float */
@keyframes floatCard{
    0%{transform:translateY(0px);}
    50%{transform:translateY(-4px);}
    100%{transform:translateY(0px);}
}
.card.float{
    animation:floatCard 4.5s ease-in-out infinite;
}

/* responsive */
@media(max-width:1200px){
    .products-grid{grid-template-columns:repeat(3,210px)}
}
@media(max-width:900px){
    .products-grid{grid-template-columns:repeat(2,200px)}
}
</style>
</head>

<body>

<div class="bg-noise"></div>

<h2 class="page-title">お気に入り商品</h2>

<div class="products-grid">
<?php while($p=$res->fetch_assoc()):
    $imgs = fetch_product_images($conn,$p['id']);
    $img = !empty($imgs) ? $imgs[0]['image_path'] : $p['image'];
?>
    <div class="card">
        <a href="product_details.php?id=<?= $p['id'] ?>">
            <img src="<?= normalized_image_url($img) ?>" class="product-image" alt="">
        </a>

        <h4><?= htmlspecialchars($p['name']) ?></h4>
        <div class="price">¥<?= number_format($p['price']) ?></div>

        <?php if ((int)$p['stock'] > 0): ?>
            <button class="btn" onclick="addToCartFromLiked(<?= (int)$p['id'] ?>, this)">
                カートに追加
            </button>
        <?php else: ?>
            <div style="color:#ff5a5a;font-weight:bold;margin-top:auto;">売り切れ</div>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
</div>

<script>
/* =========================
   Bottom → Top Reveal
========================= */
window.addEventListener("load", () => {
    if (typeof gsap !== "undefined") {
        gsap.from(".card", {
            opacity: 0,
            y: 70,
            scale: 0.95,
            duration: 0.8,
            stagger: 0.08,
            ease: "power3.out"
        });
    }
});

/* =========================
   Parallax Hover Motion
========================= */
document.querySelectorAll(".card").forEach((card, i) => {
    if (i % 3 === 0) card.classList.add("float");

    card.addEventListener("mousemove", (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const midX = rect.width / 2;
        const midY = rect.height / 2;

        const rotateY = ((x - midX) / midX) * 8;
        const rotateX = -((y - midY) / midY) * 6;

        card.style.transform = `translateY(-10px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
    });

    card.addEventListener("mouseleave", () => {
        card.style.transform = "";
    });
});

/* =====================
   Add to cart animation (LIKED)
===================== */
function addToCartFromLiked(pid, btn){
    const card = btn.closest('.card');
    const img = card.querySelector('.product-image');
    const rect = img.getBoundingClientRect();

    const clone = img.cloneNode(true);
    clone.style.position='fixed';
    clone.style.left=rect.left+'px';
    clone.style.top=rect.top+'px';
    clone.style.width=rect.width+'px';
    clone.style.height=rect.height+'px';
    clone.style.transition='all .8s cubic-bezier(.2,.8,.2,1)';
    clone.style.zIndex=9999;
    document.body.appendChild(clone);

    const fd = new FormData();
    fd.append('product_id',pid);
    fd.append('qty',1);
    fd.append('ajax',1);

    fetch('add_to_cart.php',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(j=>{
        if(j && j.success){
            const cart=document.getElementById('cartButton');
            if(cart){
                const c=cart.getBoundingClientRect();
                clone.style.left=c.left+'px';
                clone.style.top=c.top+'px';
                clone.style.width='20px';
                clone.style.height='20px';
                clone.style.opacity='0.2';
                setTimeout(()=>clone.remove(),800);
            }
            if(typeof updateCartBadge==='function'){
                updateCartBadge(j.cart_count);
            }
        }else{
            clone.remove();
        }
    })
    .catch(()=>clone.remove());
}
</script>

</body>
</html>
