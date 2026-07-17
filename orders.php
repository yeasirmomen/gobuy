<?php
session_start();
require_once '../config.php';
include 'header.php';
if (!isset($conn) || !($conn instanceof mysqli)) {
    die("DB接続エラー");
}


// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
}

// Fetch orders with user names
$sql = "SELECT o.*, u.name AS user_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.id DESC"; // use 'id' instead of 'created_at'

$orders = $conn->query($sql);

// Check if query succeeded
if (!$orders) {
    die("Error fetching orders: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Orders</title>
<link rel="stylesheet" href="../assets/styles.css">
<style>
table { width:100%; border-collapse: collapse; margin-top:14px; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:center; }
.status { padding:6px 10px; border-radius:6px; color:#fff; font-weight:bold; }
.Pending { background:#f0ad4e; }
.Processing { background:#5bc0de; }
.Shipped { background:#0275d8; }
.Delivered { background:#5cb85c; }
.btn { padding:6px 12px; border-radius:6px; text-decoration:none; color:#fff; background:#1f6feb; cursor:pointer; }
.btn:hover { background:#155ab6; }
</style>
</head>
<body>
<div class="container">
<center>
<h1>Orders Management</h1>
</center>

<table border="1">
<tr>
<th>Order ID</th>
<th>User</th>
<th>Products</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while ($o = $orders->fetch_assoc()): ?>
<tr>
<td>#<?php echo $o['id']; ?></td>
<td><?php echo htmlspecialchars($o['user_name']); ?></td>
<td>
<?php
$items_sql = "SELECT oi.*, p.name FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE order_id = ".$o['id'];
$items = $conn->query($items_sql);
if ($items) {
    while($item = $items->fetch_assoc()) {
        echo htmlspecialchars($item['name'])." x".$item['quantity']."<br>";
    }
} else {
    echo "Error fetching items: ".$conn->error;
}
?>
</td>
<td>$<?php echo number_format($o['total_amount'],2); ?></td>
<td><span class="status <?php echo $o['status']; ?>"><?php echo $o['status']; ?></span></td>
<td>
<form method="post">
<input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
<select name="status">
<option <?php if($o['status']=='Pending') echo 'selected'; ?>>Pending</option>
<option <?php if($o['status']=='Processing') echo 'selected'; ?>>Processing</option>
<option <?php if($o['status']=='Shipped') echo 'selected'; ?>>Shipped</option>
<option <?php if($o['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
</select>
<button class="btn" type="submit" name="update_status">Update</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>
</body>
</html>
