<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'config.php';
include 'header.php';

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$sql = "SELECT * FROM orders WHERE user_id=? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>My Orders</title>
<link rel="stylesheet" href="assets/styles.css">

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
   Container (Glass)
========================= */
.container{
    max-width:1100px;
    margin:20px auto;
    padding:20px;
    background:rgba(20,22,32,0.70);
    border:1px solid rgba(255,255,255,0.10);
    border-radius:18px;
    box-shadow:0 20px 60px rgba(0,0,0,.45);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
}

h1{
    text-align:center;
    margin:0 0 14px;
    font-size:28px;
}

/* Top action bar (only right side button) */
.top-actions{
    display:flex;
    justify-content:flex-end;
    align-items:center;
    margin-bottom:12px;
}

/* =========================
   Premium "View My Returns" Button
========================= */
a.return-link{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 18px;
    border-radius:999px;
    text-decoration:none;
    font-weight:800;
    font-size:14px;

    color:#fff;
    background:linear-gradient(135deg, rgba(31,111,235,0.95), rgba(170,90,255,0.95));
    border:1px solid rgba(255,255,255,0.18);
    box-shadow:0 14px 30px rgba(31,111,235,0.18);
    transition:.25s ease;
}

a.return-link:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 45px rgba(170,90,255,0.25);
    filter:brightness(1.08);
}

a.return-link:active{
    transform:translateY(0px);
}

/* =========================
   Table Premium
========================= */
table{
    width:100%;
    border-collapse: collapse;
    margin-top:12px;
    overflow:hidden;
    border-radius:14px;
}

th, td{
    padding:12px;
    border-bottom:1px solid rgba(255,255,255,0.12);
    text-align:center;
    color:rgba(255,255,255,0.92);
}

th{
    background:rgba(255,255,255,0.08);
    font-weight:800;
}

tr:hover td{
    background:rgba(255,255,255,0.04);
}

/* Status */
.status{
    padding:6px 10px;
    border-radius:8px;
    color:#fff;
    font-weight:bold;
    display:inline-block;
}
.Pending{ background:#f0ad4e; }
.Processing{ background:#5bc0de; }
.Shipped{ background:#0275d8; }
.Delivered{ background:#5cb85c; }

/* Buttons */
.ReturnBtn{
    padding:6px 10px;
    background:#ef4444;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:700;
}
.ReturnBtn:hover{ background:#dc2626; }

.CancelBtn{
    padding:6px 10px;
    background:#6b7280;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:700;
}
.CancelBtn:hover{ background:#4b5563; }

/* Responsive */
@media(max-width:768px){
    .container{padding:14px}
    table{font-size:13px}
    .top-actions{
        justify-content:flex-start;
    }
}
</style>
</head>

<body>

<div class="bg-noise"></div>

<div class="container">
<h1>My Orders</h1>

<!-- ✅ Right side premium button -->
<div class="top-actions">
    <a class="return-link" href="return_history.php">→ View My Returns</a>
</div>

<table>
<tr>
<th>Order ID</th>
<th>Products</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($order = $orders->fetch_assoc()): ?>
<tr>
<td>#<?php echo $order['id']; ?></td>

<td>
<?php
$items_sql = "SELECT oi.*, p.name FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE order_id = ".$order['id'];
$items = $conn->query($items_sql);
$items_arr = [];
if ($items) {
    while($item = $items->fetch_assoc()) {
        echo htmlspecialchars($item['name'])." x".$item['quantity']."<br>";
        $items_arr[] = $item;
    }
}
?>
</td>

<td>$<?php echo number_format($order['total_amount'], 2); ?></td>

<td>
    <span class="status <?php echo $order['status']; ?>">
        <?php echo $order['status']; ?>
    </span>
</td>

<td>
<?php if($order['status']=='Delivered'): ?>
    <?php foreach($items_arr as $item): 
        // Check if within 5 days
        $eligible = false;
        $created = new DateTime($order['order_date']);
        $now = new DateTime();
        $diff = $now->diff($created)->days;
        if($diff <= 5) $eligible = true;

        // Check if return already requested
        $check = $conn->query("SELECT * FROM returns WHERE order_id=".$order['id']." AND product_id=".$item['product_id']." AND user_id=$user_id AND canceled=0");
        $already = $check->num_rows > 0;
    ?>
        <?php if($eligible && !$already): ?>
        <form method="get" action="return_request.php" style="margin-bottom:6px;">
            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
            <button class="ReturnBtn" type="submit">
                Request Return for <?php echo htmlspecialchars($item['name']); ?>
            </button>
        </form>
        <?php elseif($already): ?>
        <span style="color:rgba(255,255,255,0.65);font-size:12px;">Return Requested</span><br>
        <?php endif; ?>
    <?php endforeach; ?>

<?php elseif($order['status']=='Pending'): ?>
    <form method="get" action="cancel_order.php">
        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
        <button class="CancelBtn" type="submit"
            onclick="return confirm('Are you sure you want to cancel this order?');">
            Cancel Order
        </button>
    </form>
<?php endif; ?>
</td>

</tr>
<?php endwhile; ?>
</table>

</div>
</body>
</html>
