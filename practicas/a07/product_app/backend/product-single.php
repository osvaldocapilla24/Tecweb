<?php
require_once __DIR__ . '/vendor/autoload.php';

use TECWEB\MYAPI\Read\Read;

$id = $_GET['id'] ?? 0;

$productos = new Read('marketzone');
$productos->single($id);

echo $productos->getData();
?>