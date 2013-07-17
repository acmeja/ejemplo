<?php
//sección que funge como controlador, se realiza la eliminación del registro y se redirecciona para presentar resultados
require_once("../lib/functions.php");
session_start();
$valorEliminar = $_POST["columna0"];
$idTabla = $_POST["idTabla"];
$nomCol = $_POST["nomColumna"];
delRow($idTabla,$nomCol,$valorEliminar);//se elimina el registro seleccionado
$_SESSION['mensaje'] = "Se eliminó correctamente el registro";
header('Location: ../');// redirección a la página deseada con los mensajes correspondientes


