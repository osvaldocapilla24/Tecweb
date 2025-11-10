<?php
use TECWEB\MYAPI\Products;

require_once __DIR__ . '/myapi/Products.php';

$nombre = $_GET['nombre'] ?? '';
$id = $_GET['id'] ?? 0;

$productos = new Products('marketzone');
$productos->singleByName($nombre, $id);

$data = json_decode($productos->getData(), true);

$response = array(
    'exists' => !empty($data),
    'message' => !empty($data) ? 'El nombre del producto ya existe' : 'Nombre disponible'
);

echo json_encode($response, JSON_PRETTY_PRINT);
?>