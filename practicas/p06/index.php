<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 6</title>
</head>
<body>
    <?php include 'src/funciones.php'; ?>
    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
   
    <h2>Ejercicio 2</h2>
    <p>Crea un programa para la generación repetitiva de 3 números aleatorios hasta obtener una
    secuencia compuesta por:</p>
    <form method="get">
        <input type="hidden" name="ej2" value="1">
        <input type="submit" value="Generar Secuencia">
    </form>
    <?php
        if (isset($_GET['ej2'])) {
            $matriz = generarSecuenciaImparParImpar();
            echo "<table border='1'><tr><th>A</th><th>B</th><th>C</th></tr>";
            foreach ($matriz as $fila) {
                echo "<tr><td>{$fila[0]}</td><td>{$fila[1]}</td><td>{$fila[2]}</td></tr>";
            }
            echo "</table>";
            echo "<p>Generados " . (count($matriz)*3) . " números en " . count($matriz) . " iteraciones.</p>";
        }
    ?>



    <h2>Ejemplo de POST</h2>
    <form action="http://localhost/tecweb/practicas/p04/index.php" method="post">
        Name: <input type="text" name="name"><br>
        E-mail: <input type="text" name="email"><br>
        <input type="submit">
    </form>
    <br>
    <?php
        if(isset($_POST["name"]) && isset($_POST["email"]))
        {
            echo $_POST["name"];
            echo '<br>';
            echo $_POST["email"];
        }
    ?>
</body>
</html>