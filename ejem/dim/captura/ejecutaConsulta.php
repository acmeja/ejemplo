<?php
require_once("../lib/functions.php");
session_start(); 
$tablaSelec = $_SESSION['tablaSelec'];
$limite = $_POST['limite'];//limite para la paginación de resultados
$campo = $_SESSION['campo'];//campo clave para realizar el filtro
$valorCampo = $_SESSION['valorBuscar'];//valor del campo clave para el filtro
if(!isset($valorCampo)){
	 $valorCampo = "";
}
if(!isset($limite)){
	$limite = 0;
}
$campos = obtieneCamposEsp($tablaSelec,"nomcolumn");
$total = numRows($tablaSelec,$campos[0],$campos,$valorCampo);
$campos = array();
if(isset($campo) && $campo != ""){
	$campos[0]= $campo;
	$consulta = genTablaConsulta($tablaSelec,obtieneRegistrosTabla($tablaSelec,$campos,$valorCampo,$limite,$total),"index.php",$limite,$total);
	unset($_SESSION['campo']);
	unset($_SESSION['valorBuscar']);
}else{
	$consulta = genTablaConsulta($tablaSelec,obtieneRegistrosTabla($tablaSelec,obtieneCamposEsp($tablaSelec,"nomcolumn"),"",$limite,$total),"index.php",$limite,$total);
}
echo $consulta;