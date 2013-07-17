<?php
include_once("../lib/functions.php");
session_start();
$nombreTabla = $_POST['nameTable'];//recibe nombre de la tabla
$numColumn = $_POST['colsx'];//recibe el numero de columnas de la tabla
echo $nombreTabla;
echo $numColumn;
echo "regresa ";
$resultado = newRow($nombreTabla,$numColumn);
$_SESSION['mensaje']=$resultado;
header('Location: ../');// redirección a la página deseada con el mensaje correspondiente