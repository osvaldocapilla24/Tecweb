<?php
namespace TECWEB\MYAPI\Create;

use TECWEB\MYAPI\DataBase;

class Create extends DataBase {
    
    public function __construct($db = 'marketzone', $user = 'root', $pass = '12345678a') {
        parent::__construct($user, $pass, $db);
    }
    
    public function add($jsonOBJ) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'ERROR: No se pudo insertar el producto'
        );
        
        if (isset($jsonOBJ->nombre)) {
            $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) 
                    VALUES ('{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', 
                    {$jsonOBJ->precio}, '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}')";
            
            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto agregado correctamente";
            } else {
                $this->data['message'] = "ERROR: " . mysqli_error($this->conexion);
            }
        }
    }
}
?>