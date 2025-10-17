<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    
    // SE CREA EL ARREGLO DE RESPUESTA
    $response = array();
    
    if(!empty($producto)) {
        // SE TRANSFORMA EL STRING DEL JSON A OBJETO
        $jsonOBJ = json_decode($producto);
        
        // VALIDAR QUE SE RECIBIERON DATOS VÁLIDOS
        if (!$jsonOBJ) {
            $response['status'] = 'error';
            $response['message'] = 'No se recibieron datos válidos';
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
        
        // VALIDAR QUE VENGAN LOS CAMPOS REQUERIDOS
        if (empty($jsonOBJ->nombre) || empty($jsonOBJ->marca) || empty($jsonOBJ->modelo) || 
            !isset($jsonOBJ->precio) || !isset($jsonOBJ->unidades)) {
            $response['status'] = 'error';
            $response['message'] = 'Faltan campos requeridos';
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
        
        // VALIDACIONES DEL LADO DEL SERVIDOR (POR SEGURIDAD)
        
        // a. Validar nombre (máximo 100 caracteres)
        if (strlen($jsonOBJ->nombre) > 100) {
            $response['status'] = 'error';
            $response['message'] = 'El nombre debe tener 100 caracteres o menos';
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
        
        // c. Validar modelo (alfanumérico, máximo 25 caracteres)
        if (strlen($jsonOBJ->modelo) > 25) {
            $response['status'] = 'error';
            $response['message'] = 'El modelo debe tener 25 caracteres o menos';
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
        
        // d. Validar precio (mayor a 99.99)
        if ($jsonOBJ->precio <= 99.99) {
            $response['status'] = 'error';
            $response['message'] = 'El precio debe ser mayor a 99.99';
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
        
        // e. Validar detalles (máximo 250 caracteres si existe)
        if (isset($jsonOBJ->detalles) && strlen($jsonOBJ->detalles) > 250) {
            $response['status'] = 'error';
            $response['message'] = 'Los detalles deben tener 250 caracteres o menos';
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
        
        // f. Validar unidades (mayor o igual a 0)
        if ($jsonOBJ->unidades < 0) {
            $response['status'] = 'error';
            $response['message'] = 'Las unidades deben ser mayor o igual a 0';
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
        
        // ESCAPAR DATOS PARA EVITAR INYECCIÓN SQL
        $nombre = $conexion->real_escape_string($jsonOBJ->nombre);
        $marca = $conexion->real_escape_string($jsonOBJ->marca);
        $modelo = $conexion->real_escape_string($jsonOBJ->modelo);
        $precio = $conexion->real_escape_string($jsonOBJ->precio);
        $unidades = $conexion->real_escape_string($jsonOBJ->unidades);
        $detalles = isset($jsonOBJ->detalles) && $jsonOBJ->detalles !== '' ? $conexion->real_escape_string($jsonOBJ->detalles) : 'NA';
        $imagen = isset($jsonOBJ->imagen) && $jsonOBJ->imagen !== '' ? $conexion->real_escape_string($jsonOBJ->imagen) : 'img/default.png';
        
        // VALIDAR SI EL PRODUCTO YA EXISTE
        // Se valida por (nombre + marca) O (marca + modelo) donde eliminado = 0
        $queryValidacion = "SELECT * FROM productos WHERE 
                           ((nombre = '{$nombre}' AND marca = '{$marca}') OR 
                            (marca = '{$marca}' AND modelo = '{$modelo}')) 
                           AND eliminado = 0";
        
        $result = $conexion->query($queryValidacion);
        
        if ($result->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = 'El producto ya existe en la base de datos';
            echo json_encode($response, JSON_PRETTY_PRINT);
            $result->free();
            $conexion->close();
            exit;
        }
        
        // SI NO EXISTE, PROCEDER CON LA INSERCIÓN
        $queryInsertar = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                          VALUES ('{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";
        
        if ($conexion->query($queryInsertar)) {
            $response['status'] = 'success';
            $response['message'] = 'Producto agregado exitosamente';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al insertar el producto: ' . $conexion->error;
        }
        
        $conexion->close();
        
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No se recibió información del producto';
    }
    
    // SE DEVUELVE LA RESPUESTA EN FORMATO JSON
    echo json_encode($response, JSON_PRETTY_PRINT);
?>