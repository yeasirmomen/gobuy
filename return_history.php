<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'config.php';
include 'header.php'; // ✅ HEADER ADDED

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.*, p.name AS product_name, o.status AS order_status
        FROM returns r 
        JOIN products p ON r.product_id = p.id
        JOIN orders o ON r.order_id = o.id
        WHERE r.user_id=? ORDER BY r.request_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$returns = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>My Return History</title>
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
   Container Glass
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
.Pending { background:#f0ad4e; }
.Approved { background:#5bc0de; }
.Rejected { background:#d9534f; }
.Received { background:#0275d8; }
.Refunded { background:#5cb85c; }

/* Cancel Button */
.CancelBtn{
    padding:6px 10px;
    background:#6b7280;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:700;
}
.CancelBtn:hover{
    background:#4b5563;
}

/* Image */
img{
    max-width:80px;
    border-radius:10px;
    border:1px solid rgba(255,255,255,0.15);
}

/* Responsive */
@media(max-width:768px){
    .container{padding:14px}
    table{font-size:13px}
}
</style>
</head>

<body>

<div class="bg-noise"></div>

<div class="container">
<h1>My Return History</h1>

<table>
<tr>
<th>Return ID</th>
<th>Product</th>
<th>Reason</th>
<th>Image</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($r = $returns->fetch_assoc()): ?>
<tr>
<td>#<?php echo $r['id']; ?></td>
<td><?php echo htmlspecialchars($r['product_name']); ?></td>
<td><?php echo htmlspecialchars($r['reason']); ?></td>
<td>
    <?php if($r['image']) echo "<img src='uploads/returns/".htmlspecialchars($r['image'])."'>"; ?>
</td>
<td>
    <span class="status <?php echo $r['status']; ?>">
        <?php echo $r['status']; ?>
    </span>
</td>
<td>
<?php if($r['status']=='Pending' && !$r['canceled']): ?>
    <form method="post" action="cancel_return.php">
        <input type="hidden" name="return_id" value="<?php echo $r['id']; ?>">
        <button class="CancelBtn" type="submit"
            onclick="return confirm('Are you sure you want to cancel this return request?');">
            Cancel
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
