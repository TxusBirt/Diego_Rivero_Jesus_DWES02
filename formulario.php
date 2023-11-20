 <!--
    Autor: Jesús Diego Rivero
    UD: 02
    Modulo: DWES
    Fecha:20/11/2023
    Proposito del programa: presentar un formulario para recoger los datos
 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <style>
        form {
            width: 180px;
            border: 2px solid black;
            padding: 10px;
            background-color: lightblue;
        }
        label {
            font-size: 12px;
            ;
        }
        input {
            display: block;
            margin-bottom: 10px;
            
        }
        #submit {
        margin: 0px 0px 0px 115px;
        display: inline-block;
        }
        
        
    </style>
</head>
<body>
    <form action="response.php" method="get">
        <label for="nombre">Nombre</label>
        <input type="text" id ="nombre" name="nombre"/>
        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos"/>
        <label for="libro">Libro Alquilado</label>
        <input type="text" id="libro" name="libro"/>
        <label for="email">Email</label>
        <input type="email" id="email" name="email"/>
        <label for="fecha">Fecha alquiler</label>
        <input type="date" id="fecha" name="fecha"/>
        <label for="dni">DNI</label>
        <input type="text" id="dni" name="dni"/>
        <label for="telefono">Telefono</label>
        <input type="tel" id="telefono" name="telefono"/>
        <input type="submit" id="submit" name="enviar"/>
    </form>
</body>
</html>