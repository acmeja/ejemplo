<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="libreria.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../estilos/estilogral.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<?php
session_start();
require_once("../lib/functions.php");
$valorEditar = $_POST["columna0"];
$idTabla = $_POST["idTabla"];
$nomCol = $_POST["nomColumna"];
echo '<label class="etiqueta"><b>Edite los campos deseados y de clic en Guardar.</b></label><br><br>';
echo '<hr><br>';
genTablaEdita($idTabla,$valorEditar,$nomCol);
?>
</head>

<body>


</body>
</html>