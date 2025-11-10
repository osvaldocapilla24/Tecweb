<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    $data = array(
        'status'  => 'error',
        'message' => 'ERROR: No se pudo actualizar el producto'
    );

    if(!empty($producto)) {
        // SE TRANSFORMA EL STRING DEL JSON A OBJETO
        $jsonOBJ = json_decode($producto);
        
        // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
        $id = $jsonOBJ->id;
        $nombre = $jsonOBJ->nombre;
        $marca = $jsonOBJ->marca;
        $modelo = $jsonOBJ->modelo;
        $precio = $jsonOBJ->precio;
        $detalles = $jsonOBJ->detalles;
        $unidades = $jsonOBJ->unidades;
        $imagen = $jsonOBJ->imagen;

        // SE CREA EL QUERY PARA ACTUALIZAR
        $sql = "UPDATE productos SET 
                nombre = '{$nombre}', 
                marca = '{$marca}',
                modelo = '{$modelo}',
                precio = {$precio},
                detalles = '{$detalles}',
                unidades = {$unidades},
                imagen = '{$imagen}'
                WHERE id = {$id}";

        if($conexion->query($sql)){
            $data['status'] = "success";
            $data['message'] = "Producto actualizado correctamente";
        } else {
            $data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($conexion);
        }
    }

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
    $conexion->close();
?>