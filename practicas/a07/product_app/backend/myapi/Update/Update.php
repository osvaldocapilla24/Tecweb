<?php
namespace TECWEB\MYAPI\Update;

use TECWEB\MYAPI\DataBase;

class Update extends DataBase {
    
    public function __construct($db = 'marketzone', $user = 'root', $pass = '12345678a') {
        parent::__construct($user, $pass, $db);
    }
    
    public function edit($jsonOBJ) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'ERROR: No se pudo actualizar el producto'
        );
        
        if (isset($jsonOBJ->id)) {
            $sql = "UPDATE productos SET 
                    nombre = '{$jsonOBJ->nombre}', 
                    marca = '{$jsonOBJ->marca}',
                    modelo = '{$jsonOBJ->modelo}',
                    precio = {$jsonOBJ->precio},
                    detalles = '{$jsonOBJ->detalles}',
                    unidades = {$jsonOBJ->unidades},
                    imagen = '{$jsonOBJ->imagen}'
                    WHERE id = {$jsonOBJ->id}";
            
            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto actualizado correctamente";
            } else {
                $this->data['message'] = "ERROR: " . mysqli_error($this->conexion);
            }
        }
    }
}
?>