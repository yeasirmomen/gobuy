<?php
require_once 'header.php';
require_once '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id<=0){
    header("Location: products.php");
    exit;
}

/* delete images */
$res = $conn->query("SELECT image FROM products WHERE id=$id");
if($row = $res->fetch_assoc()){
    if(!empty($row['image'])){
        @unlink("../uploads/".$row['image']);
    }
}

$conn->query("DELETE FROM products WHERE id=$id");

header("Location: products.php");
exit;
