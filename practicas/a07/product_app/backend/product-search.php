<?php
use TECWEB\MYAPI\Products;

require_once __DIR__ . '/myapi/Products.php';

$search = $_GET['search'] ?? '';

$productos = new Products('marketzone');
$productos->search($search);

echo $productos->getData();
?>