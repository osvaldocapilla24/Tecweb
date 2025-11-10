<?php
use TECWEB\MYAPI\Products;

require_once __DIR__ . '/myapi/Products.php';

$id = $_GET['id'] ?? 0;

$productos = new Products('marketzone');
$productos->single($id);

echo $productos->getData();
?>