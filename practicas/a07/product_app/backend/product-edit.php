<?php
require_once __DIR__ . '/vendor/autoload.php';

use TECWEB\MYAPI\Update\Update;

$producto = file_get_contents('php://input');

if (!empty($producto)) {
    $jsonOBJ = json_decode($producto);
    
    $productos = new Update('marketzone');
    $productos->edit($jsonOBJ);
    
    echo $productos->getData();
}
?>