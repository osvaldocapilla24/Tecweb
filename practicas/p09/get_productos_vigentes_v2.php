<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php
$data = array();
@$link = new mysqli('localhost', 'root', '12345678a', 'marketzone');

if ($link->connect_errno) {
    die('Falló la conexión: '.$link->connect_error.'<br/>');
}

if ($result = $link->query("SELECT * FROM productos WHERE eliminado = 0")) {
    $row = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($row as $num => $registro) {
        foreach ($registro as $key => $value) {
            $data[$num][$key] = utf8_encode($value);
        }
    }
    $result->free();
}

$link->close();
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Productos Vigentes</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
<div class="container mt-4">
<h3>Productos Vigentes</h3>
<br/>

<?php if (!empty($row)) : ?>
<table class="table table-bordered table-hover">
<thead class="thead-dark">
<tr>
<th>#</th><th>Nombre</th><th>Marca</th><th>Modelo</th><th>Precio</th>
<th>Unidades</th><th>Detalles</th><th>Imagen</th><th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach ($row as $value) : ?>
<tr>
<th scope="row"><?= $value['id'] ?></th>
<td><?= $value['nombre'] ?></td>
<td><?= $value['marca'] ?></td>
<td><?= $value['modelo'] ?></td>
<td>$<?= $value['precio'] ?></td>
<td><?= $value['unidades'] ?></td>
<td><?= $value['detalles'] ?></td>
<td><img src="<?= $value['imagen'] ?>" width="80" /></td>
<td>
<a href="formulario_productos_v2.php?id=<?= $value['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else : ?>
<h4>No hay productos vigentes para mostrar.</h4>
<?php endif; ?>
</div>
</body>
</html>
