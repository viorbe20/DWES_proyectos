<!--Sopa de Letras.
Muestra una sopa de letras con 5 capitales almacenadas.
Virginia Ordoño Bernier
-->
<?php
//Longitud del tablero
DEFINE("LENGTHBOARD", 9);
//Array que contiene los datos de la tabla
$boardArray = array();
//Dirección que va a tomar la palabra: = igual, + suma y - resta
$direction = array("+", "-", "=");
$rowDirection = "";
$columnDirection = "";
$sameLetter = false;

//Array de palabras
$capitalsArray = array("MADRID", "LONDRES", "PARIS", "BERLIN", "ROMA");
$alphabet = array("a", "b", "c", "d", "e", "f");
$capitalsArrayLenght = count($capitalsArray);
//Array que rellenaremos con los datos de cada palabra una vez colocadas y que usaremos para una comprobación final
$capitalsDataArray = array();
//array_push($capitalsArray, array("Nombre"=>"MADRID"));

//Contiene la palabra que se vaya a colocar en ese momento. Cada letra ocupa una posición del array
$lettersArray = array();

//Contiene coordenadas de primera y última letra
$firstLR = "";
$firstLC = "";
$lastLR = "";
$lastLC = "";
$row = "";
$column = "";
$wordSet = true;
//Indica cuando se ha colocado una palabra y poder pasar a la siguiente
$wordSet = false;
$letterChecked = "";
$wordChecked = "";

//Array inicial que cargamos con valor 0
for ($i = 0; $i <= LENGTHBOARD; $i++) {
    for ($j = 0; $j <= LENGTHBOARD; $j++) {
        $boardArray[$i][$j] = "0";
    }
}

//Por cada capital del array, realizamos el mismo proceso
foreach ($capitalsArray as $key => $capitalName) {

    //Extrae la palabra del array y la separa en letras dentro de un nuevo array
    $lettersArray
        = str_split($capitalName);

    //Mientras no se comprueba que la palabra se puede colocar, genera posiciones aleatorias
    do {

        //Comienza en 0 hasta que comprobemos que cabe
        $wordChecked = 0;

        //Creamos fila y columna inicial para la primera letra de palabra actual
        $firstLR = rand(0, 9);
        $firstLC = rand(0, 9);

        //Según la dirección de la línea, calculamos la posición de la línea de la última letra.
        //Controlamos que no se salga del tablero generando números hasta que cuadre
        do {
            $rowDirection = $direction[rand(0, 2)];
            //echo "<br>Dirección  de la fila => " . $rowDirection;
            switch ($rowDirection) {
                case '+':
                    $lastLR = $firstLR + (count(
                        $lettersArray
                    ) - 1);
                    break;
                case '-':
                    $lastLR = $firstLR - (count(
                        $lettersArray
                    ) - 1);
                    break;
                case '=':
                    $lastLR = $firstLR;
                    break;
            }
        } while ($lastLR > LENGTHBOARD || $lastLR < 0);

        //Según la dirección de la columna, calculamos la posición de columna de la última letra
        //Controlamos que no se salga del tablero generando números hasta que cuadre
        do {

            //Verificamos que al menos una coordenada se desplaza. Las dos no pueden ser =
            do {
                $columnDirection = $direction[rand(0, 2)];
            } while ($columnDirection == "=" && $rowDirection == "=");
            //echo "<br>Direction  de la columna => " . $columnDirection;

            switch ($columnDirection) {
                case '+':
                    $lastLC = $firstLC + (count(
                        $lettersArray
                    ) - 1);
                    break;
                case '-':
                    $lastLC = $firstLC - (count(
                        $lettersArray
                    ) - 1);
                    break;
                case '=':
                    $lastLC = $firstLC;
                    break;
            }
        } while ($lastLC > LENGTHBOARD || $lastLC < 0);

        //Cargamos datos de capital y coordenadas en el array de verificación
        $capitalsDataArray = array("Nombre" => $capitalName, "Empieza" => $firstLR . $firstLC, "Acaba" => $lastLR . $lastLC);


        //Cargamos estas variables con la posición de la letra incial y 
        //las usaremos como índices para recorrer el array donde ya hay colocadas capitales
        $row = $firstLR;
        $column = $firstLC;

        //Si una letra no se puede colocar, vuelve al inicio a generar nuevas coordenadas
        foreach ($lettersArray as $key => $letter) {

            //$boardArray[$row][$column] =  $letter;

            //Si hay letra, comprobamos si es la misma. Siempre inicia en false
            $sameLetter = false;

            if ($boardArray[$row][$column] == $letter) {
                $sameLetter = true;
            }

            //Si el contenido es diferente 0 y la letra también, no se puede colocar
            if (($boardArray[$row][$column] != "0") && !$sameLetter) {
                $wordChecked++;
            }

            //Comprobamos la dirección para saber si tenemos que sumar, restar o dejar igual las coordenadas
            if ($rowDirection == "+") {
                $row = $row + 1;
            } else if ($rowDirection == "-") {
                $row = $row - 1;
            }

            if ($columnDirection == "+") {
                $column = $column + 1;
            } else if ($columnDirection == "-") {
                $column = $column - 1;
            }
        }
    } while ($wordChecked != 0); //Sale cuando la palabra se ha comprobado. 

    //Recorro la palabra comprobada y colocamos
    $row = $firstLR;
    $column = $firstLC;
    foreach ($lettersArray as $key => $letter) {

        $boardArray[$row][$column] =  $letter;
        //Comprobamos la dirección para saber si tenemos que sumar, restar o dejar igual las coordenadas
        if ($rowDirection == "+") {
            $row = $row + 1;
        } else if ($rowDirection == "-") {
            $row = $row - 1;
        }

        if ($columnDirection == "+") {
            $column = $column + 1;
        } else if ($columnDirection == "-") {
            $column = $column - 1;
        }
    }
} //Vuelve al foreach a por la siguiente palabra

?>

<!--Muestra tablero interno-->
<!DOCTYPE HTML>
<html lang='es'>

<head>
    <style>
        #container{
            background-color: palegoldenrod;
            width: 300px;
            padding: 10px;
        }
        .square {
            width: 30px;
            height: 30px;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #upper {
            color: blue;
        }

        .row{
            display: flex;
        }

      
    </style>
</head>

<body>
    <h1>Sopa de Letras</h1>
    <h2>Encuentra cinco capitales.</h2>
<div id='container'>
    <?php
    for ($i = 0; $i <= LENGTHBOARD; $i++) {
        //echo "<br><br>";
        echo "<div class='row'>";
        for ($j = 0; $j <= LENGTHBOARD; $j++) {
            if ($boardArray[$i][$j] != 0) {
                echo "<div id='upper' class='square'>" . $boardArray[$i][$j] . "</div>";
            } else {
                echo "<div id='low' class='square'>" . $boardArray[$i][$j] = $alphabet[rand(0, 5)] . "</div>";
            }
        }
        echo "</div>";
    }

    ?>
</div>
</body>

</html>