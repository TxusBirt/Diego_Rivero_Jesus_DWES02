 <!--
    Autor: Jesús Diego Rivero
    UD: 02
    Modulo: DWES
    Fecha:20/11/2023
    Proposito del programa: interfaz de salida a los datos recogidos 
                            en formulario una vez procesados
 -->
<!DOCTYPE html>
  <html lang='es'>
    <head>
      <meta charset='UTF-8'>
      <title>Ficha libro</title>
      <style>
      td {
        border: 1px  solid black; 
        padding: 5px; 
        font-size:1em;
        }
      th {
        border: 1px  solid black;
        background-color: lightblue; 
        font-size: 1em;
        }
      p {
       font-size: 1.5em;
      }
      span {
        font-weight: bold;
      }
      div {
        margin:5px 0px 0px 90px;
        text-align: center;
        background-color: silver;
        border-radius: 5px;
        border-color: black;
        border-style: solid;
        width: 55px;
      }
      </style>
    </head>
    <body>
      <?php
        include 'procesado_datos.php';
        salida_datos();
      ?>
      <div>
        <a href="formulario.php">Volver</a>
      </div>
    </body>
  </html>

    

