<?php
include 'config.php';

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$sql = "INSERT INTO messages (name, email, message)
        VALUES ('$name', '$email', '$message')";

mysqli_query($conn, $sql);

header("Location: contact_thankyou.php");
exit;
