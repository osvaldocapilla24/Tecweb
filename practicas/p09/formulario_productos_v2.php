<?php
// ----- PROCESAR FORMULARIO EN PHP -----
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conectarse a la base de datos
    $conn = new mysqli("localhost", "root", "12345678a", "marketzone");
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Recibir valores del formulario
    $nombre = trim($_POST["nombre"]);
    $marca = trim($_POST["marca"]);
    $modelo = trim($_POST["modelo"]);
    $precio = floatval($_POST["precio"]);
    $detalles = trim($_POST["detalles"]);
    $unidades = intval($_POST["unidades"]);
    $imagen = trim($_POST["imagen"]);
    if ($imagen === "") $imagen = "img/default.png";

    // Validaciones en el servidor (por seguridad)
    if ($nombre === "" || strlen($nombre) > 100) {
        $mensaje = "❌ El nombre es requerido y debe tener máximo 100 caracteres.";
    } elseif ($marca === "") {
        $mensaje = "❌ Debes seleccionar una marca.";
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $modelo) || strlen($modelo) > 25) {
        $mensaje = "❌ El modelo debe ser alfanumérico y tener máximo 25 caracteres.";
    } elseif ($precio <= 99.99) {
        $mensaje = "❌ El precio debe ser mayor a 99.99.";
    } elseif (strlen($detalles) > 250) {
        $mensaje = "❌ Los detalles deben tener máximo 250 caracteres.";
    } elseif ($unidades < 0) {
        $mensaje = "❌ Las unidades deben ser mayores o iguales a 0.";
    } else {
        // Insertar en la base de datos
        $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen)
                VALUES ('$nombre', '$marca', '$modelo', '$precio', '$detalles', '$unidades', '$imagen')";
        if ($conn->query($sql)) {
            $mensaje = "✅ Reloj guardado correctamente.";
        } else {
            $mensaje = "❌ Error al guardar: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Reloj (PHP)</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    label { font-weight: bold; }
    input, select { width: 250px; padding: 5px; margin-top: 4px; }
    .error { color: red; font-size: 0.9em; margin-top: 3px; display: none; }
    .input-error { border: 1px solid red; background-color: #ffecec; }
    #resultado { margin-top: 15px; font-weight: bold; }
    .exito { color: green; }
    .fallo { color: red; }
    input[type="submit"] {
      background-color: #0078D4;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      cursor: pointer;
    }
    input[type="submit"]:hover { background-color: #005ea2; }
  </style>
</head>
<body>

  <h2>Registrar nuevo reloj</h2>

  <form id="formProducto" method="POST" novalidate>
    <label>Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" maxlength="100" value="<?php echo $_POST['nombre'] ?? ''; ?>"><br>
    <div class="error" id="errorNombre"></div><br>

    <label>Marca:</label><br>
    <select id="marca" name="marca">
      <option value="">-- Selecciona una marca --</option>
      <option value="Casio">Casio</option>
      <option value="Rolex">Rolex</option>
      <option value="Seiko">Seiko</option>
      <option value="Citizen">Citizen</option>
      <option value="Fossil">Fossil</option>
    </select><br>
    <div class="error" id="errorMarca"></div><br>

    <label>Modelo:</label><br>
    <input type="text" id="modelo" name="modelo" maxlength="25" value="<?php echo $_POST['modelo'] ?? ''; ?>"><br>
    <div class="error" id="errorModelo"></div><br>

    <label>Precio:</label><br>
    <input type="number" id="precio" name="precio" step="0.01" value="<?php echo $_POST['precio'] ?? ''; ?>"><br>
    <div class="error" id="errorPrecio"></div><br>

    <label>Detalles:</label><br>
    <input type="text" id="detalles" name="detalles" maxlength="250" placeholder="Color, tipo de correa, material..." value="<?php echo $_POST['detalles'] ?? ''; ?>"><br>
    <div class="error" id="errorDetalles"></div><br>

    <label>Unidades:</label><br>
    <input type="number" id="unidades" name="unidades" min="0" value="<?php echo $_POST['unidades'] ?? ''; ?>"><br>
    <div class="error" id="errorUnidades"></div><br>

    <label>Imagen:</label><br>
    <input type="text" id="imagen" name="imagen" placeholder="img/default.png" value="<?php echo $_POST['imagen'] ?? ''; ?>"><br><br>

    <input type="submit" value="Guardar Reloj">
  </form>

  <?php if ($mensaje): ?>
    <p id="resultado" class="<?php echo strpos($mensaje, '✅') !== false ? 'exito' : 'fallo'; ?>">
      <?php echo $mensaje; ?>
    </p>
  <?php endif; ?>

  <script>
    // Validación visual en cliente
    document.getElementById('formProducto').addEventListener('submit', function(e) {
      document.querySelectorAll('.error').forEach(el => el.style.display = 'none');
      document.querySelectorAll('input, select').forEach(el => el.classList.remove('input-error'));

      let valido = true;

      const nombre = document.getElementById('nombre');
      const marca = document.getElementById('marca');
      const modelo = document.getElementById('modelo');
      const precio = parseFloat(document.getElementById('precio').value);
      const detalles = document.getElementById('detalles');
      const unidades = parseInt(document.getElementById('unidades').value);

      const regexModelo = /^[a-zA-Z0-9]+$/;

      if (nombre.value.trim() === "" || nombre.value.length > 100) {
        valido = false;
        nombre.classList.add('input-error');
        const err = document.getElementById('errorNombre');
        err.textContent = "El nombre es requerido y debe tener máximo 100 caracteres.";
        err.style.display = "block";
      }

      if (marca.value === "") {
        valido = false;
        marca.classList.add('input-error');
        const err = document.getElementById('errorMarca');
        err.textContent = "Debes seleccionar una marca.";
        err.style.display = "block";
      }

      if (!regexModelo.test(modelo.value.trim()) || modelo.value.length > 25) {
        valido = false;
        modelo.classList.add('input-error');
        const err = document.getElementById('errorModelo');
        err.textContent = "El modelo debe ser alfanumérico y tener máximo 25 caracteres.";
        err.style.display = "block";
      }

      if (isNaN(precio) || precio <= 99.99) {
        valido = false;
        document.getElementById('precio').classList.add('input-error');
        const err = document.getElementById('errorPrecio');
        err.textContent = "El precio debe ser mayor a 99.99.";
        err.style.display = "block";
      }

      if (detalles.value.length > 250) {
        valido = false;
        detalles.classList.add('input-error');
        const err = document.getElementById('errorDetalles');
        err.textContent = "Los detalles deben tener máximo 250 caracteres.";
        err.style.display = "block";
      }

      if (isNaN(unidades) || unidades < 0) {
        valido = false;
        document.getElementById('unidades').classList.add('input-error');
        const err = document.getElementById('errorUnidades');
        err.textContent = "Las unidades deben ser un número mayor o igual a 0.";
        err.style.display = "block";
      }

      if (!valido) e.preventDefault();
    });
  </script>

</body>
</html>
