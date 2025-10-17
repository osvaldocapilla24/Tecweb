<?php
    include_once __DIR__.'/database.php';
    
    $data = array();
    
    if( isset($_POST['search']) ) {
        $search = $_POST['search'];
        
        // BÚSQUEDA EN TODOS LOS CAMPOS INCLUYENDO ID
        $query = "SELECT * FROM productos WHERE (id LIKE '%{$search}%' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
        
        if ( $result = $conexion->query($query) ) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $producto = array();
                foreach($row as $key => $value) {
                    $producto[$key] = $value;
                }
                $data[] = $producto;
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($conexion));
        }
        $conexion->close();
    }
    
    echo json_encode($data, JSON_PRETTY_PRINT);
?>