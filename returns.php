<?php
session_start();
require_once '../config.php';
include 'header.php';
// Handle status update
if (isset($_POST['update_return'])) {
    $return_id = (int)$_POST['return_id'];
    $new_status = $_POST['status'];
    $admin_note = isset($_POST['admin_note']) ? $_POST['admin_note'] : '';

    $stmt = $conn->prepare("UPDATE returns SET status=?, admin_note=? WHERE id=?");
    $stmt->bind_param("ssi", $new_status, $admin_note, $return_id);
    $stmt->execute();
}

// Fetch all return requests
$sql = "SELECT r.*, u.name AS user_name, p.name AS product_name 
        FROM returns r
        JOIN users u ON r.user_id = u.id
        JOIN products p ON r.product_id = p.id
        ORDER BY r.id DESC";
$returns = $conn->query($sql);
if (!$returns) die("Error fetching returns: ".$conn->error);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Return Requests</title>
<link rel="stylesheet" href="../assets/styles.css">
<style>
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:center; }
.status { padding:6px 10px; border-radius:6px; color:#fff; font-weight:bold; }
.Pending { background:#f0ad4e; }
.Approved { background:#5cb85c; }
.Rejected { background:#d9534f; }
.Received { background:#0275d8; }
.Refunded { background:#5bc0de; }
input, select, textarea { padding:6px; margin:4px 0; width:100%; box-sizing:border-box; }
.btn { padding:6px 12px; border-radius:6px; text-decoration:none; color:#fff; background:#1f6feb; cursor:pointer; }
.btn:hover { background:#155ab6; }
form { margin:0; }
</style>
</head>
<body>
<div class="container">
<h1><center>Return Requests</center></h1>
<a class="btn" href="dashboard.php">← Back to Dashboard</a>

<table border="1">
<tr>
<th>Return ID</th>
<th>User</th>
<th>Product</th>
<th>Reason</th>
<th>Images</th>
<th>Status</th>
<th>Admin Note</th>
<th>Action</th>
</tr>

<?php while ($r = $returns->fetch_assoc()): ?>
<tr>
<td>#<?php echo $r['id']; ?></td>
<td><?php echo htmlspecialchars($r['user_name']); ?></td>
<td><?php echo htmlspecialchars($r['product_name']); ?></td>
<td><?php echo htmlspecialchars($r['reason']); ?></td>
<td>
<?php 
if (!empty($r['images'])) {
    $imgs = explode(',', $r['images']);
    foreach($imgs as $img) {
        echo '<img src="../uploads/'.htmlspecialchars($img).'" width="60" style="margin:2px;">';
    }
} else {
    echo '-';
}
?>
</td>
<td><span class="status <?php echo $r['status']; ?>"><?php echo $r['status']; ?></span></td>
<td><?php echo isset($r['admin_note']) ? htmlspecialchars($r['admin_note']) : ''; ?></td>
<td>
<form method="post">
<input type="hidden" name="return_id" value="<?php echo $r['id']; ?>">
<select name="status">
<option <?php if($r['status']=='Pending') echo 'selected'; ?>>Pending</option>
<option <?php if($r['status']=='Approved') echo 'selected'; ?>>Approved</option>
<option <?php if($r['status']=='Rejected') echo 'selected'; ?>>Rejected</option>
<option <?php if($r['status']=='Received') echo 'selected'; ?>>Received</option>
<option <?php if($r['status']=='Refunded') echo 'selected'; ?>>Refunded</option>
</select>
<textarea name="admin_note" placeholder="Add note"><?php echo isset($r['admin_note']) ? htmlspecialchars($r['admin_note']) : ''; ?></textarea>
<button class="btn" type="submit" name="update_return">Update</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>
</body>
</html>
