
<?php 
//*********************************************************************
//Nombre: conexion.php
//Funcion del Modulo: Conexion a la base de datos postgres
//Fecha: 01/04/2013
//Relizo: Javier Acosta Mejia
//*********************************************************************	


$strCnx = "";

//**********************************************************************
//Nombre: conectar
//Descripción: conecta a la bd 
//Realizo: javier Acosta Mejía
//Fecha: 01/04/2013
//***********************************************************************	
function conectar()
{
	global $strCnx;
	$user = "postgres";
	$passwd ="admin";
	$db = "bduiem";
	$port = 5432;
	$host = "localhost";
	$strCnx = "host=$host port=$port dbname=$db user=$user password=$passwd";
	$cnx = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
}


function desconectar()
{
	global $strCnx;
	//pg_close($strCnx);
}
?>