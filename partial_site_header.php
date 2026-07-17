<?php
// partials/site_header.php (Header only)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cart count from session
$cart_count = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $cart_count += (int)$qty;
    }
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
.site-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 16px;
    background:#fff;
    border-bottom:1px solid #eee;
    position:sticky;
    top:0;
    z-index:1000;
}
.logo img{height:56px}

.header-middle{
    flex:1;
    display:flex;
    justify-content:center;
    padding:0 20px;
}
.header-search{
    width:100%;
    max-width:420px;
    display:flex;
}
.header-search input{
    flex:1;
    padding:8px 10px;
    border:1px solid #ccc;
    border-radius:6px 0 0 6px;
}
.header-search button{
    padding:8px 14px;
    border:none;
    background:#1f6feb;
    color:#fff;
    border-radius:0 6px 6px 0;
    cursor:pointer;
}

.nav-right{
    display:flex;
    align-items:center;
    gap:14px;
}
.nav-right a{
    text-decoration:none;
    color:#333;
    font-size:14px;
    font-weight:500;
}
.nav-right a:hover{color:#1f6feb}

.btn-outline{
    border:1px solid #ddd;
    padding:6px 12px;
    border-radius:8px;
}
.btn{
    background:#1f6feb;
    color:#fff;
    padding:6px 12px;
    border-radius:8px;
}

.cart-btn{
    position:relative;
    padding:8px 12px;
    border-radius:8px;
    background:#f7f7f7;
    border:1px solid #ddd;
    cursor:pointer;
    display:flex;
    align-items:center;
    gap:6px;
}
.cart-badge{
    position:absolute;
    top:-6px;
    right:-6px;
    background:#e53935;
    color:#fff;
    border-radius:50%;
    min-width:20px;
    height:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:600;
}
.cart-count-hidden{display:none}

@media(max-width:768px){
    .header-middle{display:none}
}
</style>

<header class="site-header">

    <div class="logo">
        <a href="menu.php">
            <img src="logo.png" alt="GoBuyNow">
        </a>
    </div>

    <div class="header-middle">
        <form class="header-search" method="get" action="menu.php">
            <input type="search" name="q" placeholder="商品を検索…" required>
            <button type="submit">検索</button>
        </form>
    </div>

    <nav class="nav-right">

        <?php if ($current_page !== 'menu.php'): ?>
            <a href="menu.php">商品一覧</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="liked_products.php">❤️ お気に入り</a>
            <a href="my_orders.php">注文履歴</a>
        <?php endif; ?>

        <button class="cart-btn" onclick="location.href='cart.php'">
            🛒 カート
            <?php if ($cart_count > 0): ?>
                <span id="cartBadge" class="cart-badge"><?php echo (int)$cart_count; ?></span>
            <?php else: ?>
                <span id="cartBadge" class="cart-badge cart-count-hidden">0</span>
            <?php endif; ?>
        </button>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php" class="btn-outline">マイページ</a>
            <a href="logout.php" class="btn-outline">ログアウト</a>
        <?php else: ?>
            <a href="login.html" class="btn">ログイン</a>
        <?php endif; ?>

        <a href="contact.php" class="btn-outline">お問い合わせ</a>

    </nav>

</header>

<script>
function updateCartBadge(count){
    var badge = document.getElementById('cartBadge');
    if (!badge) return;
    if (count > 0){
        badge.classList.remove('cart-count-hidden');
        badge.textContent = count;
    } else {
        badge.classList.add('cart-count-hidden');
        badge.textContent = '0';
    }
}
</script>
