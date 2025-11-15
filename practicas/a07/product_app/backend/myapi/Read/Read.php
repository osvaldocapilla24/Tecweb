<?php
namespace TECWEB\MYAPI\Read;

use TECWEB\MYAPI\DataBase;

class Read extends DataBase {
    
    public function __construct($db = 'marketzone', $user = 'root', $pass = '12345678a') {
        parent::__construct($user, $pass, $db);
    }
    
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
}
?>