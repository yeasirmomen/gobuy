<?php
session_start();
include '../config.php';

if (!$conn) {
    die("DB connection error: " . $conn->connect_error);
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Added name field here
    $stmt = $conn->prepare("SELECT id, name, email, password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $admin = $res->fetch_assoc();

        // Since DB uses plain-text passwords (0000, Y1234)
        if ($password === $admin['password']) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];

            // NEW: Save name in session
            $_SESSION['admin_name'] = $admin['name'];

            header("Location: dashboard.php");
            exit;

        } else {
            $msg = "Invalid password.";
        }

    } else {
        $msg = "Admin not found.";
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Login</title>
<link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<div class="container" style="max-width:420px">
    <h2>Admin Login</h2>

    <?php if ($msg): ?>
        <p style="color:red"><?= $msg ?></p>
    <?php endif; ?>

    <form method="post">
        <input name="email" type="email" placeholder="Email" required style="width:100%;padding:10px;margin:6px 0">
        <input name="password" type="password" placeholder="Password" required style="width:100%;padding:10px;margin:6px 0">
        <button class="btn" type="submit">Login</button>
    </form>
</div>
</body>
</html>
