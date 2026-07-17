<?php
// admin/create_admin.php — run once to create admin
include '../config.php';

$email = 'admin@gobuynow.com';
$password = 'admin123'; // change after first login
$hash = password_hash($password, PASSWORD_DEFAULT);
$name = 'Admin';

$stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hash);
if ($stmt->execute()) {
    echo "Admin created: $email / $password";
} else {
    echo "Error or already exists: " . $conn->error;
}
$stmt->close();
?>
