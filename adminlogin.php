<?php
session_start();
include '../config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email=?");
    $stmt->bind_param("s",$email); $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows==1) {
        $a = $res->fetch_assoc();
        if (password_verify($password, $a['password'])) {
            $_SESSION['admin_id'] = $a['id'];
            $_SESSION['admin_name'] = $a['name'];
            header("Location: dashboard.php"); exit;
        } else $msg = "Invalid password.";
    } else $msg = "Admin not found.";
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Login</title>
<link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<div class="container" style="max-width:420px">
  <h2>Admin Login</h2>
  <?php if($msg) echo "<p style='color:red'>$msg</p>"; ?>
  <form method="post">
    <input name="email" type="email" placeholder="Email" required style="width:100%;padding:10px;margin:6px 0">
    <input name="password" type="password" placeholder="Password" required style="width:100%;padding:10px;margin:6px 0">
    <button class="btn" type="submit">Login</button>
  </form>
</div>
</body>
</html>
