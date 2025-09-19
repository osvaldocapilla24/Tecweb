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


    
    ?>