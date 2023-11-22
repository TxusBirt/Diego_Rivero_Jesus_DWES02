 <!--
    Autor: Jesús Diego Rivero
    UD: 02
    Modulo: DWES
    Fecha:20/11/2023
    Proposito del programa: Procesar datos de un formulario
 -->
<?php
// Funcion que valida que se declara la variable nombre al enviar el 
// formulario y que no está vacia
function valida_nombre($nombre) 
{
    if (isset($nombre) and !empty($nombre)) {
        return true;
    } else {
        return false;
    }
} 
// Funcion que valida que se declara la variable apellidos al enviar el 
// formulario y que no está vacia
function valida_apellidos($apellidos) 
{
    if (isset($apellidos) and !empty($apellidos)) {
        return true;
    } else {
        return false;
    }
} 
function valida_libro($libro) 
{
    if (isset($libro) and !empty($libro)) {
        return true;
    } else {
        return false;
    }
} 
// Funcion que valida que se declara la variable email al enviar el 
// formulario y que tiene el formato correcto
function validar_email($email) 
{
    if (isset($email) and filter_var($email, FILTER_VALIDATE_EMAIL)) {
       return true;
    } else {
        return false;
    };
}  
// Funcion que valida que se declara la variable fecha al enviar el 
// formulario y que es posterior o igual a la fecha actual
function validar_fecha($fecha)
{
    $fecha_actual = strtotime(date("d-m-Y",time()));
    $fecha_entrada = strtotime($fecha);
    if (isset($fecha) and $fecha_entrada >= $fecha_actual)  {
        return true;
    } else {
        return false;
    };
}
// Funcion que indica la fecha de devolución sumandole 10 dias
function fecha_devolucion($fechaInicio) 
{
    return date('d-m-Y', strtotime('+10 day' , strtotime($fechaInicio))); 
}
// Funcion que valida que se declara la variable dni al enviar el 
// formulario y que tiene la estructura correcta
function validar_dni($dniForm) 
{
    if (isset($dniForm) and dniValido($dniForm)) {
        return true;
    } else {
        return false;
    }
}
// Funcion que comprueba que la letra del DNI introducido es la correcta
// y que el DNI tiene 9 caracteres. También se comprueba la letra para DNIs de 
// extranjeros que viven en España
function dniValido($dni) 
{
    $dniLetra = ["T","R","W","A","G","M","Y","F","P","D","X","B","N","J","Z","S","Q","V","H","L","C","K","E"];
    // array con las letras iniciales posibles de los DNI de extranjeros
    $dniExt = ["X", "Y", "Z"];
    if(strlen($dni)!=9) {
        return false;
    }
    $numDni='';
    for($i=0; $i < 8; $i++) {
        if(is_numeric($dni[$i])) {
                $numDni=$numDni.$dni[$i];
        } else {
            if ($i==0) {
                // sustitución de las letras en DNI extranjeros para 
                // luego calcular la letra final
                if(in_array($dni[$i],$dniExt)) {
                    if($dni[$i]=='X'){
                        $dni[$i]='0';
                        $numDni=$numDni.$dni[$i];
                    } else if ($dni[$i]=='Y'){
                        $dni[$i]='1';
                        $numDni=$numDni.$dni[$i];
                    } else {
                        $dni[$i]='2';
                        $numDni=$numDni.$dni[$i];
                    }
                } else {
                    return false;
                }
            } 
        }
    }
    $letraDni=strtoupper($dni[8]);
    $numEnt= intval($numDni%23);
    $letra_valida =  $dniLetra[$numEnt];
    if($letra_valida==$letraDni) {
        return true;
    } else {
        return $letra_valida;
    }
}
// Funcion que valida el telefono
function valida_telefono($telefono) {
    // compruebo que tenga 9 digitos y que la variable se envíe del formulario
    if(strlen($telefono) !=9 || !isset($telefono)) {
        return false;
    } else {
        // requisito para los telefonos fijos que empiece por 8 o por 9
        if (($telefono[0] == '9' || $telefono[0] == '8')) {
            // requisito para los telefonos fijos el segundo digito no sea ni un 0 ni un 9
            if ($telefono[1]=='0' || $telefono[1]=='9') {
                return false;
            } else {
                return true;
            }
        // requisito para los telefonos móviles que empiecen por 6 o 7;
        } else if ($telefono[0] == '6' || $telefono[0] == '7') {
            return true;
        } else {
            return false;
        };
    }
}
// función para decidir los datos que enviar a la interfaz gráfica y enviarla
function salida_datos() {
    if (valida_nombre($_GET['nombre']) and valida_apellidos($_GET['apellidos']) and valida_libro($_GET['libro']) and 
        validar_email($_GET['email']) and validar_dni($_GET['dni']) and validar_fecha($_GET['fecha']) and 
        valida_telefono ($_GET['telefono'])) {
        /* 
          Compruebo si el libro esta disponible (sólo tengo en cuenta los libros 
          que se alquilan en una sesión para simular si el libro esta disponible ya
          que no hay BBDD)
         */
        session_start();
        /* 
          Genero una variable booleana para que indique si el libro está en la lista
          Se supone que los libros están alquilados surante 10 días desde la fecha de alquiler
         */
        $estaEnArray=false;
        /* 
          Si existe el array $_SESSION['libro'] lo recorro para comparar con los datos que se han introducido
          en el formulario. Si hay un libro donde la fecha de devolución es mayor o igual a la que se introduce
          en el formulario y el titulo es el mismo el libro está alquilado y cambio el valor de la variable
         */
        if (isset($_SESSION['libro']) ? is_array($_SESSION['libro']) : false) {
            foreach($_SESSION['libro'] as $datos) {
                list($libro, $fecha) = $datos;
                if (($libro==$_GET['libro']) && (strtotime(fecha_devolucion($fecha))>=strtotime($_GET['fecha']))){
                    $estaEnArray=true;
                }
            }
            // añado el libro con su fecha de alquiler al array
            array_push($_SESSION['libro'], [$_GET['libro'], $_GET['fecha']]);
        } // si no es un array (o no existe), lo convertimos en array de arrays que contengan libro y fecha
        else { 
            $_SESSION['libro'] = array([$_GET['libro'], $_GET['fecha']]);
        };
        // si está alquilado se lanza un aviso y se norra el libro recién introducido en el formulario
        if($estaEnArray) {
            echo "<p>Libro alquilado, elija otro</p>";
            array_pop($_SESSION['libro']);   
        } //si está disponible se genera la ficha de solicitud
        else {
            echo "<table style='border-collapse:collapse'>";
            echo "<h1> Ficha Solicitud prestamo <h1>";
            echo "<tr>";
            echo "<th class ='etiqueta'> Nombre </th> <td>" . $_GET['nombre'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<tr>";
            echo "<th class ='etiqueta'> Apellidos </th> <td>" . $_GET['apellidos'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th class ='etiqueta'> DNI </th> <td>" . $_GET['dni'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th class ='etiqueta'> Telefono </th> <td>" . $_GET['telefono'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th class ='etiqueta'> Fecha devolucion </th> <td>" . fecha_devolucion($_GET['fecha']) . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th class ='etiqueta'> Libro </th> <td>" . $_GET['libro'] . "</td>";
            echo "</tr>";
            echo "</table>";
        }
    }   // Opciones que muestra el programa en caso de datos incorrectamente rellenados o que directamente 
        // no se han rellenado
    else {
        echo "<p style='font-size:2.5em; font-weight:bold; color:red;'>"; 
        echo "Formulario incorrectamente rellenado. </p>";
        echo "<p style='font-size:2em; font-weight:bold;'> Corrija los elementos siguientes del formulario: </p>";
        if (validar_email($_GET['email']) == false) {
            echo "<p> <span>Email</span> incorrectamente rellenado: asegurese de no haberlo dejado";
            echo " en blanco y que el email sea correcto</p>";
        } 
        if (validar_dni($_GET['dni'])== false) {
            echo "<p> <span>DNI</span> incorrectamente rellenado: asegurese de no haberlo dejado";
            echo " en blanco y que el DNI sea correcto</br>";
            echo " La letra correcta al numero de DNI dado es la: <span>". dniValido($_GET['dni']) . "</span></p>";
        }
        if (validar_fecha($_GET['fecha'])== false) {
            echo "<p> <span> Fecha </span> incorrectamente rellenada: asegurese de no haber elegido";
            echo " una fecha anterior al día de hoy</p>";
        }
        if (valida_nombre($_GET['nombre'])==false) {
            echo "<p> No ha rellenado el <span>Nombre</span></p>";
        }
        if (valida_libro($_GET['libro'])==false) {
            echo "<p> No ha rellenado el <span>libro</span> que desea</p>";
        }
        if (valida_apellidos($_GET['apellidos'])==false) {
            echo "<p> No ha rellenado los <span>Apellidos</span></p>";
        }
        if (valida_telefono($_GET['telefono'])==false) {
            echo "<p> El <span>telefono</span> dado no es correcto</p>";
        }
    }
}