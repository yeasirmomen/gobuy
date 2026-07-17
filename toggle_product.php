<?php
require_once 'header.php';
require_once '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id<=0){
    header("Location: products.php");
    exit;
}

$conn->query("
    UPDATE products 
    SET is_hidden = IF(is_hidden=1,0,1)
    WHERE id = $id
");

header("Location: products.php");
exit;
