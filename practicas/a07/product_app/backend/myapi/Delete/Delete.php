<?php
namespace TECWEB\MYAPI\Delete;

use TECWEB\MYAPI\DataBase;

class Delete extends DataBase {
    
    public function __construct($db = 'marketzone', $user = 'root', $pass = '12345678a') {
        parent::__construct($user, $pass, $db);
    }
    
    public function delete($id) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'ERROR: No se pudo eliminar el producto'
        );
        
        if (!empty($id)) {
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
            
            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto eliminado correctamente";
            } else {
                $this->data['message'] = "ERROR: " . mysqli_error($this->conexion);
            }
        }
    }
}
?>