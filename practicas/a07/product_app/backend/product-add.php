<?php
require_once __DIR__ . '/vendor/autoload.php';

use TECWEB\MYAPI\Create\Create;

$producto = file_get_contents('php://input');

if (!empty($producto)) {
    $jsonOBJ = json_decode($producto);
    
    $productos = new Create('marketzone');
    $productos->add($jsonOBJ);
    
    echo $productos->getData();
}
?>