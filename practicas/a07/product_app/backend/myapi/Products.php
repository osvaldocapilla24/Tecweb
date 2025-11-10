<?php
namespace TECWEB\MYAPI;

require_once __DIR__ . '/DataBase.php';

class Products extends DataBase {
    private $data;
    
    public function __construct($db, $user = 'root', $pass = '12345678a') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }
    
    // Lista todos los productos
    public function list() {
        $this->data = array();
        
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->data[$num][$key] = utf8_encode($value);
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . mysqli_error($this->conexion));
        }
    }
    
    // Busca productos
    public function search($search) {
        $this->data = array();
        
        $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
        
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->data[$num][$key] = utf8_encode($value);
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . mysqli_error($this->conexion));
        }
    }
    
    // Obtiene un producto por ID
    public function single($id) {
        $this->data = array();
        
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE id = {$id}")) {
            $row = $result->fetch_assoc();
            
            if (!is_null($row)) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = utf8_encode($value);
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . mysqli_error($this->conexion));
        }
    }
    
    // Obtiene un producto por nombre
    public function singleByName($name, $excludeId = 0) {
        $this->data = array();
        
        $sql = "SELECT * FROM productos WHERE nombre = '{$name}' AND eliminado = 0";
        
        if ($excludeId > 0) {
            $sql .= " AND id != {$excludeId}";
        }
        
        if ($result = $this->conexion->query($sql)) {
            $row = $result->fetch_assoc();
            
            if (!is_null($row)) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = utf8_encode($value);
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . mysqli_error($this->conexion));
        }
    }
    
    // Agrega un producto
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
    
    // Elimina un producto (lógicamente)
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
    
    // Edita un producto
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
    
    // Devuelve los datos en JSON
    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>