<?php
require_once __DIR__ . '/vendor/autoload.php';

use TECWEB\MYAPI\Read\Read;

$nombre = $_GET['nombre'] ?? '';
$id = $_GET['id'] ?? 0;

$productos = new Read('marketzone');
$productos->singleByName($nombre, $id);

$data = json_decode($productos->getData(), true);

$response = array(
    'exists' => !empty($data),
    'message' => !empty($data) ? 'El nombre del producto ya existe' : 'Nombre disponible'
);

echo json_encode($response, JSON_PRETTY_PRINT);
?>