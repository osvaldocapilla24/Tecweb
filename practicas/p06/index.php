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
    <h2>Ejercicio 3</h2>
    <p>Utiliza un ciclo while para encontrar el primer número entero obtenido aleatoriamente,
    pero que además sea múltiplo de un número dado.</p>
    <form method="get">
        <label>N: <input type="number" name="n"></label>
        <select name="modo">
            <option value="while">while</option>
            <option value="dowhile">do-while</option>
        </select>
        <input type="submit" value="Buscar múltiplo">
    </form>
    <?php
        if (isset($_GET['n'])) {
            $n = intval($_GET['n']);
            if ($_GET['modo'] === 'while') {
                list($val, $intentos) = primerMultiplo_while($n);
            } else {
                list($val, $intentos) = primerMultiplo_dowhile($n);
            }
            echo "<p>Primer múltiplo encontrado: $val en $intentos intentos.</p>";
        }
    ?>
    <h2>Ejercicio 4</h2>
    <p>Crear un arreglo cuyos índices van de 97 a 122 y cuyos valores son las letras de la ‘a’
    a la ‘z’. Usa la función chr(n) que devuelve el caracter cuyo código ASCII es n para poner
    el valor en cada índice. Es decir:</p>
    <form method="get">
        <input type="hidden" name="ej4" value="1">
        <input type="submit" value="Mostrar Arreglo ASCII">
    </form>
    <?php
        if (isset($_GET['ej4'])) {
            echo arregloAscii();
        }
    ?>

    <h2>Ejercicio 5</h2>
    <p>Usar las variables $edad y $sexo en una instrucción if para identificar una persona de
    sexo “femenino”, cuya edad oscile entre los 18 y 35 años y mostrar un mensaje de
    bienvenida apropiado.</p>
    <form method="post">
    <input type="hidden" name="accion" value="ej5">
    <label>Edad: <input type="number" name="edad" required></label><br>
    <label>Sexo:
        <select name="sexo">
            <option value="femenino">Femenino</option>
            <option value="masculino">Masculino</option>
        </select>
    </label><br>
    <input type="submit" value="Verificar">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "ej5") {
    echo "<p><strong>Resultado:</strong> " . verificar_edad_sexo($_POST["edad"], $_POST["sexo"]) . "</p>";
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