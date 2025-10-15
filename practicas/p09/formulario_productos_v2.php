<?php
$link = new mysqli('localhost', 'root', '12345678a', 'marketzone');
if ($link->connect_errno) {
    die('Error de conexión: ' . $link->connect_error);
}

$id = $_GET['id'] ?? null; // ID del producto que vas a editar

$producto = null;
if ($id) {
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    $stmt->close();
}
$link->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= $producto ? 'Editar Reloj' : 'Nuevo Reloj' ?> - TimeZone</title>
<script>
function validarFormulario() {
  const nombre = document.getElementById("nombre").value.trim();
  const marca = document.getElementById("marca").value;
  const modelo = document.getElementById("modelo").value.trim();
  const precio = parseFloat(document.getElementById("precio").value);
  const detalles = document.getElementById("detalles").value.trim();
  const unidades = parseInt(document.getElementById("unidades").value);
  const imagen = document.getElementById("imagen").value.trim();

  if (nombre === "" || nombre.length > 100) { alert("El nombre del reloj es requerido."); return false; }
  if (marca === "") { alert("Selecciona una marca de reloj."); return false; }
  const regexModelo = /^[a-zA-Z0-9]+$/;
  if (!regexModelo.test(modelo) || modelo.length > 25) { alert("Modelo inválido."); return false; }
  if (isNaN(precio) || precio <= 99.99) { alert("El precio debe ser mayor a $99.99."); return false; }
  if (detalles.length > 250) { alert("Detalles demasiado largos."); return false; }
  if (isNaN(unidades) || unidades < 0) { alert("Unidades debe ser ≥ 0."); return false; }
  if (imagen === "") { document.getElementById("imagen").value = "img/default.png"; }
  return true;
}
</script>
</head>
<body>
<h2><?= $producto ? 'Editar Reloj #' . htmlspecialchars($producto['id']) : 'Nuevo Reloj' ?></h2>
<form action="<?= $producto ? 'update_producto.php' : 'create_producto.php' ?>" method="POST" onsubmit="return validarFormulario()">
<?php if ($producto): ?>
<input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
<?php endif; ?>
<label>Nombre del reloj:</label><br>
<input type="text" id="nombre" name="nombre" value="<?= $producto ? htmlspecialchars($producto['nombre']) : '' ?>"><br><br>
<label>Marca:</label><br>
<select id="marca" name="marca">
  <option value="">Seleccione una marca</option>
  <option value="Casio" <?= $producto && $producto['marca'] == 'Casio' ? 'selected' : '' ?>>Casio</option>
  <option value="Rolex" <?= $producto && $producto['marca'] == 'Rolex' ? 'selected' : '' ?>>Rolex</option>
  <option value="Seiko" <?= $producto && $producto['marca'] == 'Seiko' ? 'selected' : '' ?>>Seiko</option>
  <option value="Citizen" <?= $producto && $producto['marca'] == 'Citizen' ? 'selected' : '' ?>>Citizen</option>
  <option value="Fossil" <?= $producto && $producto['marca'] == 'Fossil' ? 'selected' : '' ?>>Fossil</option>
</select><br><br>
<label>Modelo:</label><br>
<input type="text" id="modelo" name="modelo" value="<?= $producto ? htmlspecialchars($producto['modelo']) : '' ?>"><br><br>
<label>Precio:</label><br>
<input type="number" id="precio" name="precio" step="0.01" value="<?= $producto ? htmlspecialchars($producto['precio']) : '' ?>"><br><br>
<label>Detalles:</label><br>
<textarea id="detalles" name="detalles"><?= $producto ? htmlspecialchars($producto['detalles']) : '' ?></textarea><br><br>
<label>Unidades:</label><br>
<input type="number" id="unidades" name="unidades" value="<?= $producto ? htmlspecialchars($producto['unidades']) : '' ?>"><br><br>
<label>Imagen:</label><br>
<input type="text" id="imagen" name="imagen" value="<?= $producto ? htmlspecialchars($producto['imagen']) : '' ?>"><br><br>
<input type="submit" value="<?= $producto ? 'Guardar Cambios' : 'Crear Producto' ?>">
</form>
</body>
</html>
