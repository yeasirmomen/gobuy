<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user = (int)$_SESSION['user_id'];
$items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id=$user");

$total = 0;
$cartItems = [];

while ($i = mysqli_fetch_assoc($items)) {
    $p = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT name, price FROM products WHERE id=".(int)$i['product_id'])
    );
    $subtotal = $p['price'] * $i['quantity'];
    $total += $subtotal;

    $cartItems[] = [
        'name' => $p['name'],
        'price' => $p['price'],
        'qty' => $i['quantity'],
        'sub' => $subtotal
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout | GoBuyNow</title>

<!-- GSAP animation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
*{ box-sizing:border-box; }

/* =========================
   Menu Like Background
========================= */
body{
    margin:0;
    min-height:100vh;
    font-family:"Segoe UI", Arial, sans-serif;
    background:#070A12;
    color: rgba(255,255,255,0.92);
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
   Top Header Glass
========================= */
.topbar{
    position:sticky;
    top:0;
    z-index:1000;
    padding:14px 22px;

    background: rgba(2,6,23,.65);
    border-bottom: 1px solid rgba(255,255,255,.08);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
}
.topbar-inner{
    max-width:1200px;
    margin:0 auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
}
.brand{
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none;
    color:#fff;
}
.brand img{
    height:46px;
}
.brand span{
    font-weight:900;
    letter-spacing:.6px;
    font-size:16px;
    opacity:.95;
}
.secure-badge{
    font-size:12px;
    padding:8px 12px;
    border-radius:999px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.14);
    color: rgba(255,255,255,0.85);
}

/* =========================
   Main Checkout Card (Glass)
========================= */
.checkout-container{
    max-width: 1100px;
    margin: 24px auto;
    padding: 0 16px;
}

.checkout-card{
    background: rgba(20, 22, 32, 0.72);
    border: 1px solid rgba(255,255,255,0.10);
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,.35);

    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    overflow:hidden;
    position:relative;
}

/* soft glow inside */
.checkout-card::before{
    content:"";
    position:absolute;
    inset:-2px;
    background:
        radial-gradient(circle at 30% 20%, rgba(31,111,235,.30), transparent 55%),
        radial-gradient(circle at 80% 90%, rgba(170,90,255,.22), transparent 60%);
    opacity:0.85;
    pointer-events:none;
}

.checkout-head{
    position:relative;
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:12px;
    margin-bottom: 12px;
}
.checkout-head h2{
    margin:0;
    font-size:22px;
}
.checkout-head p{
    margin:0;
    font-size:12px;
    color: rgba(255,255,255,0.65);
}

/* =========================
   Table Premium
========================= */
.table-wrap{
    position:relative;
    overflow:auto;
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.10);
    background: rgba(0,0,0,0.18);
}

table{
    width:100%;
    border-collapse: collapse;
    min-width: 650px;
}

th{
    text-align:left;
    padding:14px;
    font-weight:800;
    font-size:13px;
    color: rgba(255,255,255,0.95);
    background: rgba(255,255,255,0.06);
    border-bottom: 1px solid rgba(255,255,255,0.10);
}

td{
    padding:14px;
    font-size:13px;
    color: rgba(255,255,255,0.85);
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

tr:hover td{
    background: rgba(255,255,255,0.03);
}

td strong{
    color:#fff;
}

/* =========================
   Total + Checkout Button
========================= */
.bottom-row{
    position:relative;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    margin-top: 14px;
    flex-wrap:wrap;
}

.total-box{
    font-size:16px;
    font-weight:900;
}
.total-box span{
    color:#37d39b;
}

.btn-checkout{
    padding:12px 16px;
    border:none;
    cursor:pointer;
    border-radius: 14px;
    font-weight:900;
    font-size:14px;
    color:#fff;

    background: linear-gradient(135deg, rgba(31,111,235,1), rgba(170,90,255,1));
    box-shadow: 0 18px 40px rgba(31,111,235,0.18);
    transition: 0.25s ease;
}

.btn-checkout:hover{
    transform: translateY(-2px);
    box-shadow: 0 22px 55px rgba(170,90,255,0.20);
}

.btn-checkout:disabled{
    opacity:.65;
    cursor:not-allowed;
    transform:none;
}

/* empty cart */
.empty-box{
    position:relative;
    padding:16px;
    border-radius:14px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.10);
}

/* responsive */
@media(max-width:768px){
    .secure-badge{display:none;}
}
</style>
</head>

<body>

<div class="bg-noise"></div>

<!-- Header -->
<div class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="menu.php">
            <img src="logo.png" alt="GoBuyNow">
            <span>GoBuyNow</span>
        </a>
        <div class="secure-badge">🔒 Secure Checkout</div>
    </div>
</div>

<!-- Checkout -->
<div class="checkout-container">
    <div class="checkout-card">

        <div class="checkout-head">
            <div>
                <h2>🛒 Checkout</h2>
                <p>Review your items before placing the order</p>
            </div>
            <p>Fast • Safe • Premium</p>
        </div>

        <?php if (count($cartItems) === 0): ?>
            <div class="empty-box">
                Your cart is empty.
            </div>
        <?php else: ?>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price (¥)</th>
                    <th>Qty</th>
                    <th>Subtotal (¥)</th>
                </tr>

                <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                    <td>¥<?= number_format($item['price']) ?></td>
                    <td><?= (int)$item['qty'] ?></td>
                    <td>¥<?= number_format($item['sub']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="bottom-row">
            <div class="total-box">
                Total: <span>¥<?= number_format($total) ?></span>
            </div>

            <form action="place_order.php" method="post" id="placeOrderForm">
                <button class="btn-checkout" id="placeOrderBtn" type="submit">
                    Place Order
                </button>
            </form>
        </div>

        <?php endif; ?>

    </div>
</div>

<script>
/* Smooth reveal animation */
window.addEventListener("load", () => {
    if (typeof gsap !== "undefined") {
        gsap.from(".topbar", {opacity:0, y:-20, duration:0.6, ease:"power2.out"});
        gsap.from(".checkout-card", {opacity:0, y:40, duration:0.8, ease:"power3.out"});
        gsap.from("tr", {opacity:0, y:18, duration:0.6, stagger:0.05, ease:"power2.out"});
    }
});

/* Prevent double submit */
document.getElementById("placeOrderForm")?.addEventListener("submit", ()=>{
    const btn = document.getElementById("placeOrderBtn");
    if(btn){
        btn.disabled = true;
        btn.textContent = "Placing...";
    }
});
</script>

</body>
</html>
