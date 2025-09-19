 <?php
    if(isset($_GET['numero']))
    {
        $num = $_GET['numero'];
        if ($num%5==0 && $num%7==0)
        {
            echo '<h3>R= El número '.$num.' SÍ es múltiplo de 5 y 7.</h3>';
        }
        else
        {
            echo '<h3>R= El número '.$num.' NO es múltiplo de 5 y 7.</h3>';
        }
    }

    function generarSecuenciaImparParImpar() 
    {
        $matriz = [];
        do {
            $a = rand(100, 999);
            $b = rand(100, 999);
            $c = rand(100, 999);
            $matriz[] = [$a, $b, $c];
        } while (!($a % 2 != 0 && $b % 2 == 0 && $c % 2 != 0));

        return $matriz;
    }

    function primerMultiplo_while($n) 
    {
        $contador = 0;
        while (true) {
            $contador++;
            $x = rand(1, 1000);
            if ($x % $n == 0) {
            return [$x, $contador];
            }
        }
    }

    // Ejercicio 3 con do-while
    function primerMultiplo_dowhile($n) {
    $contador = 0;
        do {
        $contador++;
        $x = rand(1, 1000);
        } while ($x % $n != 0);
        return [$x, $contador];
    }
    //ejercicio4
    function arregloAscii() {
    $html = "<h3>Arreglo ASCII (97..122) — letras a..z</h3>";
    $html .= "<table border='1' cellpadding='6' cellspacing='0'>";
    $html .= "<thead><tr><th>Código</th><th>Carácter</th></tr></thead><tbody>";
    for ($code = 97; $code <= 122; $code++) {
        $html .= "<tr><td>{$code}</td><td>" . htmlspecialchars(chr($code)) . "</td></tr>";
    }
    $html .= "</tbody></table>";
    return $html;
    }

    function verificar_edad_sexo($edad, $sexo) {
    if (!is_numeric($edad)) return "Edad inválida.";
    $edad = intval($edad);
    $sexo = strtolower(trim($sexo));

    if ($sexo === 'femenino' || $sexo === 'f') {
        if ($edad >= 18 && $edad <= 35) {
            return "Bienvenida, usted está en el rango de edad permitido.";
        } else {
            return "Lo siento, su edad no está en el rango permitido (18–35).";
        }
    } else {
        return "No cumple el requisito: no es persona de sexo femenino.";
    }
    }
    
    function registro_parque_vehicular() {
    $reg = [];

    $reg['AAA0001'] = ['Auto'=>['marca'=>'HONDA','modelo'=>2020,'tipo'=>'camioneta'],'Propietario'=>['nombre'=>'Alfonso Esparza','ciudad'=>'Puebla','direccion'=>'C.U. Jardines']];
    $reg['BBB0002'] = ['Auto'=>['marca'=>'MAZDA','modelo'=>2019,'tipo'=>'sedan'],'Propietario'=>['nombre'=>'María Molina','ciudad'=>'Puebla','direccion'=>'97 Oriente']];
    $reg['CCC0003'] = ['Auto'=>['marca'=>'NISSAN','modelo'=>2018,'tipo'=>'hatchback'],'Propietario'=>['nombre'=>'Juan Perez','ciudad'=>'CDMX','direccion'=>'Insurgentes 123']];
    $reg['DDD0004'] = ['Auto'=>['marca'=>'TOYOTA','modelo'=>2021,'tipo'=>'sedan'],'Propietario'=>['nombre'=>'Ana Lopez','ciudad'=>'Guadalajara','direccion'=>'Calle Falsa 45']];
    $reg['EEE0005'] = ['Auto'=>['marca'=>'FORD','modelo'=>2017,'tipo'=>'camioneta'],'Propietario'=>['nombre'=>'Luis García','ciudad'=>'Monterrey','direccion'=>'Centro 12']];
    $reg['FFF0006'] = ['Auto'=>['marca'=>'KIA','modelo'=>2022,'tipo'=>'hatchback'],'Propietario'=>['nombre'=>'Rosa Martínez','ciudad'=>'Puebla','direccion'=>'Zaragoza 7']];
    $reg['GGG0007'] = ['Auto'=>['marca'=>'CHEVROLET','modelo'=>2016,'tipo'=>'sedan'],'Propietario'=>['nombre'=>'Pedro Sánchez','ciudad'=>'Toluca','direccion'=>'Av Central 3']];
    $reg['HHH0008'] = ['Auto'=>['marca'=>'VOLKSWAGEN','modelo'=>2015,'tipo'=>'hatchback'],'Propietario'=>['nombre'=>'Lucia Romo','ciudad'=>'Puebla','direccion'=>'4 Poniente 88']];
    $reg['III0009'] = ['Auto'=>['marca'=>'HYUNDAI','modelo'=>2020,'tipo'=>'sedan'],'Propietario'=>['nombre'=>'Diego Luna','ciudad'=>'Querétaro','direccion'=>'Blvd Central 1']];
    $reg['JJJ0010'] = ['Auto'=>['marca'=>'AUDI','modelo'=>2019,'tipo'=>'sedan'],'Propietario'=>['nombre'=>'Sofia Peña','ciudad'=>'Cancún','direccion'=>'Bulevar Kukulcan']];
    $reg['KKK0011'] = ['Auto'=>['marca'=>'BMW','modelo'=>2021,'tipo'=>'sedan'],'Propietario'=>['nombre'=>'Carlos Mtz','ciudad'=>'León','direccion'=>'Morelos 99']];
    $reg['LLL0012'] = ['Auto'=>['marca'=>'MITSUBISHI','modelo'=>2014,'tipo'=>'camioneta'],'Propietario'=>['nombre'=>'Elena Ruiz','ciudad'=>'Puebla','direccion'=>'La Paz 23']];
    $reg['MMM0013'] = ['Auto'=>['marca'=>'SUBARU','modelo'=>2018,'tipo'=>'hatchback'],'Propietario'=>['nombre'=>'Raul Torres','ciudad'=>'Veracruz','direccion'=>'Mar 11']];
    $reg['NNN0014'] = ['Auto'=>['marca'=>'TESLA','modelo'=>2022,'tipo'=>'sedan'],'Propietario'=>['nombre'=>'Ana Gómez','ciudad'=>'CDMX','direccion'=>'Polanco 5']];
    $reg['OOO0015'] = ['Auto'=>['marca'=>'FIAT','modelo'=>2013,'tipo'=>'hatchback'],'Propietario'=>['nombre'=>'Iván Cruz','ciudad'=>'Puebla','direccion'=>'Granada 2']];

    return $reg;
    }

    function buscar_por_matricula($matricula) {
    $matricula = strtoupper(trim($matricula));
    $reg = registro_parque_vehicular();
    return $reg[$matricula] ?? null;
    }



    
    ?>