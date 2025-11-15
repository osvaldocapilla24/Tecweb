<?php
require_once __DIR__ . '/vendor/autoload.php';

use TECWEB\MYAPI\Read\Read;

$search = $_GET['search'] ?? '';

$productos = new Read('marketzone');
$productos->search($search);

echo $productos->getData();
?>