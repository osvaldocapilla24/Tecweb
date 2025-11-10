<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE EL ID DEL PRODUCTO
    $id = $_GET['id'];

    // SE REALIZA LA QUERY DE BÚSQUEDA
    if ( $result = $conexion->query("SELECT * FROM productos WHERE id = {$id}") ) {
        // SE OBTIENEN LOS RESULTADOS
        $row = $result->fetch_assoc();

        if(!is_null($row)) {
            // SE CODIFICAN A UTF-8 LOS DATOS
            foreach($row as $key => $value) {
                $data[$key] = utf8_encode($value);
            }
            // SE ENVÍA EL PRODUCTO COMO JSON
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            // No se encontró el producto
            echo json_encode(array('status' => 'error', 'message' => 'Producto no encontrado'));
        }
        $result->free();
    } else {
        die('Query Error: '.mysqli_error($conexion));
    }
    $conexion->close();
?>