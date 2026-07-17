<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) exit;

$pid = intval($_POST['product_id']);
$uid = intval($_SESSION['user_id']);
$rating = intval($_POST['rating']);
$comment = trim($_POST['comment']);

$stmt = $conn->prepare(
"INSERT INTO product_reviews(product_id,user_id,rating,comment)
 VALUES(?,?,?,?)
 ON DUPLICATE KEY UPDATE
 rating=VALUES(rating), comment=VALUES(comment)"
);
$stmt->bind_param("iiis",$pid,$uid,$rating,$comment);
$stmt->execute();

header("Location: product_details.php?id=".$pid);
