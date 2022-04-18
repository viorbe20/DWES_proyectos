<!--Sopa de Letras.
Muestra una sopa de letras con 5 capitales almacenadas.
En esta versión se usan sesiones para guardar la información.
Virginia Ordoño Bernier
-->

<?php

echo ('<a href="destroySession.php">Empezar</a> <br>');
//Longitud del tablero
DEFINE("LENGTHBOARD", 9);

session_start();

if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array();
    $_SESSION['dataArray'] = array();
    $_SESSION['toggle'] = 1;
    createNewBoard();
}

//Imprime por pantalla
showBoard();

//Cuando se hace click en una letra se comprueba
if (isset($_GET['row'])) {
    $clickedRow = $_GET['row'];
    $clickedColumn = $_GET['column'];

    $_SESSION['toggle'] = 1 - $_SESSION['toggle'];


    //Posición de inicio
    if ($_SESSION['toggle'] == 0) {
        echo ('<br>Toggle 0');

        //Cierra sesión que contiene posición final
        unset($_SESSION['finish']);
        $_SESSION['start'] = $clickedRow . $clickedColumn;
        echo ('<br>Inicio' . $_SESSION['start']);

        foreach ($_SESSION['dataArray'] as $key => $value) {

            foreach ($value as $key => $position) {
                if ($key == "Empieza") {
                    if ($clickedRow . $clickedColumn == $position) {
                        echo ('<br>Inicio correcto');
                        //print_r(array_keys($_SESSION['dataArray'], $value));
                        //Guardo la posición
                        $_SESSION['solutionRow'] = array_keys($_SESSION['dataArray'], $value);
                    } else {
                        echo ('<br>Inicio NO correcto');
                        //$_SESSION['solution'] = 0;
                    }
                }
            }
        }
    }

    //Posición de fin
    if ($_SESSION['toggle'] == 1) {
        echo ('<br>Toggle 1');

        //Cierra sesión que contiene posición inicial
        unset($_SESSION['start']);

        //En caso de acertar posición inicial
        if (isset($_SESSION['solutionRow'])) {
            $_SESSION['finish'] = $clickedRow . $clickedColumn;
            echo ('<br>Fin elegido => ' . $_SESSION['finish']);

            foreach ($_SESSION['dataArray'] as $key => $value) {
                foreach ($value as $key => $position) {
                    if ($key == "Acaba") {
                        if ($clickedRow . $clickedColumn == $position) {
                            echo ('<br>Fin correcto');
                            //print_r(array_keys($_SESSION['dataArray'], $value));
                            //Guardo la posición
                            $_SESSION['solutionColumn'] = array_keys($_SESSION['dataArray'], $value);
                        } else {
                            echo ('<br>Final NO correcto');
                            //$_SESSION['solution'] = 0;
                        }
                    }
                }
            }

            //Si se han acertado las posiciones comprobamos índices
            if ($_SESSION['solutionRow'] = $_SESSION['solutionColumn']){
                echo('<br>Has acertado') ;
            } 

            unset($_SESSION['solutionRow']);
            unset($_SESSION['solutionColumn']);
        }
    }







    // //Recoge la posición final
    // if (isset($_SESSION['start'])) {
    //     unset($_SESSION['start']);
    //     echo('<br>Termina') ;
    //     if (isset($_SESSION['solutionRow'])) {
    //         $_SESSION['finish'] = $clickedRow . $clickedColumn;   
    //     }


    // }
    //Cambia valor
    //$_SESSION['toggle'] = 1 - $_SESSION['toggle'];

    //unset($_GET['row']);
}



//Array que contiene los datos de la tabla
//$boardArray = array();
$rowDirection = "";
$columnDirection = "";
$sameLetter = false;


//$capitalsArrayLenght = count($capitalsArray);
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




function checkLetters($clickedRow, $clickedColumn)
{
    echo 'hola';
    echo $clickedRow;
    echo $clickedColumn;
    //var_dump($_SESSION["board"]);
}


function createNewBoard()
{
    //Array inicial que cargamos con valor 0
    for ($i = 0; $i <= LENGTHBOARD; $i++) {
        for ($j = 0; $j <= LENGTHBOARD; $j++) {
            $_SESSION["board"][$i][$j] = "0";
        }
    }

    //Por cada capital del array, realizamos el mismo proceso
    //Array de palabras
    $capitalsArray = array("MADRID", "LONDRES", "PARIS", "BERLIN", "ROMA");
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
            //Dirección que va a tomar la palabra: = igual, + suma y - resta
            $direction = array("+", "-", "=");
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
            //$capitalsDataArray = array("Nombre" => $capitalName, "Empieza" => $firstLR . $firstLC, "Acaba" => $lastLR . $lastLC, "Estado" => "falso");
            //$_SESSION['capitalsDataArray'] = array("Nombre" => $capitalName, "Empieza" => $firstLR . $firstLC, "Acaba" => $lastLR . $lastLC, "Estado" => "falso");

            //Cargamos estas variables con la posición de la letra incial y 
            //las usaremos como índices para recorrer el array donde ya hay colocadas capitales
            $row = $firstLR;
            $column = $firstLC;

            //Si una letra no se puede colocar, vuelve al inicio a generar nuevas coordenadas
            foreach ($lettersArray as $key => $letter) {

                //Si hay letra, comprobamos si es la misma. Siempre inicia en false
                $sameLetter = false;

                if ($_SESSION["board"][$row][$column] == $letter) {
                    $sameLetter = true;
                }

                //Si el contenido es diferente 0 y la letra también, no se puede colocar
                if (($_SESSION["board"][$row][$column] != "0") && !$sameLetter) {
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

        //Cargamos el array con los datos de las palabras seleccionadas
        $_SESSION['dataArray'][] = array("Nombre" => $capitalName, "Empieza" => $firstLR . $firstLC, "Acaba" => $lastLR . $lastLC, "Estado" => "false");
        //$_SESSION['dataArray'][] = array($capitalName, "Empieza" => $firstLR . $firstLC, "Acaba" => $lastLR . $lastLC, "Estado" => "falso");

        //Recorro la palabra comprobada y colocamos
        $row = $firstLR;
        $column = $firstLC;
        foreach ($lettersArray as $key => $letter) {

            $_SESSION["board"][$row][$column] =  $letter;
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
}

function showBoard()

{
    $alphabet = array("a", "b", "c", "d", "e", "f");
    echo "<h1>Sopa de Letras</h1>
    <h2>Encuentra cinco capitales.</h2>
    <div id='container'>";
    for ($i = 0; $i <= LENGTHBOARD; $i++) {
        echo "<div class='row'>";
        for ($j = 0; $j <= LENGTHBOARD; $j++) {

            if ($_SESSION["board"][$i][$j] == 0) {
                $_SESSION["board"][$i][$j] = $alphabet[rand(0, 5)];
            }
            echo "<div id='upper' class='square'><a href=\"index.php?row=" . $i . "&column=" . $j . "\">" . $_SESSION["board"][$i][$j]  . "</a></div>";
        }
        echo "</div>";
    }


    echo "</div><br>";
}





?>

<!--Muestra tablero interno-->

<style>
    body {
        padding: 10px;
    }

    a {
        *text-decoration: none;
    }

    #container {
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

    .row {
        display: flex;
    }
</style>

</html>