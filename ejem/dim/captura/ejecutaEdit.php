<?php
session_start();
require_once("../lib/functions.php");
$idTable = $_POST['nameTable'];
$valorOriginal = $_POST['colsx'];
setRow($idTable,$valorOriginal);
$_SESSION['mensaje'] = "Registro actualizado correctamente.";
header('Location: ../');