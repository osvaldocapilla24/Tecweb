<?php
// ==============================
// formulario_productos_v2.php
// ==============================

// Conexión a la base de datos
$link = mysqli_connect("localhost", "root", "12345678a", "marketzone");

// Verificar conexión
if ($link === false) {
    die("❌ ERROR: No pudo conectarse con la BD. " . mysqli_connect_error());
}

// Obtener ID del producto (por GET)
$id = $_GET['id'] ?? '';

if ($id == '') {
    die("⚠️ No se especificó el producto a editar.");
}

// Consultar producto por ID
$sql = "SELECT * FROM productos WHERE id = $id";
$result = mysqli_query($link, $sql);

// Verificar si existe
if (!$result || mysqli_num_rows($result) == 0) {
    die("⚠️ No se encontró el producto con ID = $id");
}

$producto = mysqli_fetch_assoc($result);
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto</title>
  <script>
    function validarFormulario() {
      const nombre = document.forms["formEditar"]["nombre"].value.trim();
      const marca = document.forms["formEditar"]["marca"].value.trim();
      const modelo = document.forms["formEditar"]["modelo"].value.trim();
      const precio = parseFloat(document.forms["formEditar"]["precio"].value);
      const detalles = document.forms["formEditar"]["detalles"].value.trim();
      const unidades = parseInt(document.forms["formEditar"]["unidades"].value);
      const imagen = document.forms["formEditar"]["imagen"].value.trim();

      if (nombre === "" || nombre.length > 100) {
        alert("❌ El nombre es requerido y debe tener máximo 100 caracteres.");
        return false;
      }

      if (marca === "" || marca.length > 50) {
        alert("❌ La marca es requerida y debe tener máximo 50 caracteres.");
        return false;
      }

      const regexModelo = /^[a-zA-Z0-9]+$/;
      if (modelo === "" || !regexModelo.test(modelo) || modelo.length > 25) {
        alert("❌ El modelo debe ser alfanumérico y de máximo 25 caracteres.");
        return false;
      }

      if (isNaN(precio) || precio <= 99.99) {
        alert("❌ El precio debe ser mayor a 99.99.");
        return false;
      }

      if (detalles.length > 250) {
        alert("❌ Los detalles deben tener máximo 250 caracteres.");
        return false;
      }

      if (isNaN(unidades) || unidades < 0) {
        alert("❌ Las unidades deben ser un número mayor o igual a 0.");
        return false;
      }

      if (imagen === "") {
        document.forms["formEditar"]["imagen"].value = "img/default.png";
      }

      return true;
    }
  </script>
</head>
<body>
  <h2>Editar Producto</h2>

  <form name="formEditar" 
        action="update_producto.php" 
        method="post" 
        onsubmit="return validarFormulario()">

    <!-- Campo oculto con el ID -->
    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>" required><br><br>

    <label>Marca:</label><br>
    <input type="text" name="marca" value="<?php echo $producto['marca']; ?>" required><br><br>

    <label>Modelo:</label><br>
    <input type="text" name="modelo" value="<?php echo $producto['modelo']; ?>" required><br><br>

    <label>Precio:</label><br>
    <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required><br><br>

    <label>Detalles (opcional):</label><br>
    <input type="text" name="detalles" value="<?php echo $producto['detalles']; ?>"><br><br>

    <label>Unidades:</label><br>
    <input type="number" name="unidades" value="<?php echo $producto['unidades']; ?>" required><br><br>

    <label>Imagen (opcional):</label><br>
    <input type="text" name="imagen" value="<?php echo $producto['imagen']; ?>"><br><br>

    <input type="submit" value="Actualizar Producto">
  </form>
</body>
</html>
