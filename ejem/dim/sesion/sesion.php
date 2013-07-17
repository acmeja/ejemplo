<?php
//*********************************************************************
//Nombre: sesion.php
//Funcion del Modulo: revisa si existe o no una sesion y cierra sesion
//Fecha: 01/04/2013
//Relizo: Javier Acosta Mejia
//*********************************************************************
session_start();

function chk_acces()
{
	if (!isset($_SESSION['nomusuar']) && !isset($_SESSION['cveusuar']))
	{
		header("Location: ".$_SERVER['HTTP_REFERER']."acceso/acceso.html");
	}
}

if (isset($_REQUEST['cerrar']))
{
	borrarArchivos();
	cerrar();
}

function cerrar()
{
	session_unset();
	session_destroy();
	header("Location: ".$_SERVER['HTTP_REFERER']."acceso/acceso.html");
}


#Obtener la clave de usuario de la sesion

if (isset($_REQUEST['cveusr']))
{
	echo $_SESSION['cveusuar'];
}

 /*
* Nombre: borrarArchivos
* Función del modulo: Borrar los archivos que se utilizaron para insertar datos en las tablas
* Parámetros: Utiliza variables de sesión
* Fecha: 03/05/2013
* Realizó: Jesus Abel Vera Cruz
*/
function borrarArchivos()
{
$dirArchivo="../".$_SESSION['archAgregar'].".html";

if(file_exists($dirArchivo))
{
unlink($dirArchivo);
}
}


?>