<?php
require_once __DIR__ . '/vendor/autoload.php';

use TECWEB\MYAPI\Delete\Delete;

$id = $_GET['id'] ?? 0;

$productos = new Delete('marketzone');
$productos->delete($id);

echo $productos->getData();
?>