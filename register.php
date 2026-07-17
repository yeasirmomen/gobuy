<?php
include 'config.php';

if (isset($_POST['email'])) {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Check password match
    if ($password !== $confirm) {
        die("Passwords do not match!");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $sql = "INSERT INTO users (name, email, password) 
            VALUES ('$name', '$email', '$hashedPassword')";

    if (mysqli_query($conn, $sql)) {
        echo "Registration successful! <a href='login.html'>Login Now</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
