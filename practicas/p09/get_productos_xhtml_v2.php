<?php
header("Content-type: application/xhtml+xml; charset=UTF-8");

if (!isset($_GET['tope'])) {
    die('Parámetro "tope" no detectado...');
}
$tope = $_GET['tope'];
if (!is_numeric($tope)) {
    die('El parámetro "tope" debe ser numérico.');
}

@$link = new mysqli('localhost', 'root', '12345678a', 'marketzone');
if ($link->connect_errno) {
    die('Falló la conexión: '.$link->connect_error.'<br/>');
}

$sql = "SELECT * FROM productos WHERE unidades <= ? AND eliminado = 0";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $tope);
$stmt->execute();
$result = $stmt->get_result();

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
<title>Productos con Pocas Unidades</title>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous" />
</head>
<body class="p-3">
<h3>Productos con Unidades ≤ <?= htmlspecialchars($tope) ?></h3>
<br />

<?php if ($result->num_rows > 0): ?>
<table class="table table-bordered table-hover">
<thead class="thead-dark">
<tr>
<th>#</th><th>Nombre</th><th>Marca</th><th>Modelo</th><th>Precio</th>
<th>Unidades</th><th>Detalles</th><th>Imagen</th><th>Acciones</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<th scope="row"><?= $row['id']; ?></th>
<td><?= $row['nombre']; ?></td>
<td><?= $row['marca']; ?></td>
<td><?= $row['modelo']; ?></td>
<td>$<?= $row['precio']; ?></td>
<td><?= $row['unidades']; ?></td>
<td><?= $row['detalles']; ?></td>
<td><img src="<?= $row['imagen']; ?>" width="80" /></td>
<td>
<a href="formulario_productos_v2.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>No se encontraron productos con unidades ≤ <?= htmlspecialchars($tope) ?>.</p>
<?php endif; ?>
</body>
</html>
<?php
$stmt->close();
$link->close();
?>
