<?php
    include_once __DIR__.'/database.php';

    $nombre = $_GET['nombre'] ?? '';
    $id = $_GET['id'] ?? 0; // Para excluir el producto actual en caso de edición

    $data = array('exists' => false);

    if (!empty($nombre)) {
        // Buscar si el nombre existe (excluyendo el producto actual si se está editando)
        $sql = "SELECT id FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0";
        
        if ($id > 0) {
            $sql .= " AND id != {$id}";
        }
        
        if ($result = $conexion->query($sql)) {
            if ($result->num_rows > 0) {
                $data['exists'] = true;
                $data['message'] = 'El nombre del producto ya existe';
            } else {
                $data['exists'] = false;
                $data['message'] = 'Nombre disponible';
            }
            $result->free();
        }
    }

    $conexion->close();
    echo json_encode($data, JSON_PRETTY_PRINT);
?>