<?php
// Conexión a la base de datos
$link = new mysqli('localhost', 'root', '12345678a', 'marketzone');

if ($link->connect_errno) {
    die("Error al conectar con la base de datos: " . $link->connect_error);
}

// Obtener datos del formulario
$id       = $_POST['id'];
$nombre   = $_POST['nombre'];
$marca    = $_POST['marca'];
$modelo   = $_POST['modelo'];
$precio   = $_POST['precio'];
$detalles = $_POST['detalles'];
$unidades = $_POST['unidades'];
$imagen   = $_POST['imagen'];

if (empty($imagen)) {
    $imagen = "img/default.png";
}

// Actualizar el producto
$sql = "UPDATE productos 
        SET nombre='$nombre', marca='$marca', modelo='$modelo', precio=$precio,
            detalles='$detalles', unidades=$unidades, imagen='$imagen'
        WHERE id=$id";

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Actualización de producto</title>
</head>
<body>
<h2>Resultado de la actualización</h2>

<?php
if ($link->query($sql)) {
    echo "<p><strong>El producto fue actualizado correctamente.</strong></p>";
    echo "<ul>";
    echo "<li><b>ID:</b> $id</li>";
    echo "<li><b>Nombre:</b> $nombre</li>";
    echo "<li><b>Marca:</b> $marca</li>";
    echo "<li><b>Modelo:</b> $modelo</li>";
    echo "<li><b>Precio:</b> $precio</li>";
    echo "<li><b>Detalles:</b> $detalles</li>";
    echo "<li><b>Unidades:</b> $unidades</li>";
    echo "<li><b>Imagen:</b> $imagen</li>";
    echo "</ul>";
} else {
    echo "<p style='color:red;'>Ocurrió un error al actualizar el producto.</p>";
    echo "<p>Detalle del error: " . $link->error . "</p>";
}

$link->close();
?>
</body>
</html>
