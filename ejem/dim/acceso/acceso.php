<?php

//*********************************************************************
//Nombre: acceso.php
//Funcion del Modulo: control de acceso a usuarios
//Fecha: 01/04/2013
//Relizo: Javier Acosta Mejia
//*********************************************************************	

session_start();
include('../conexion.php');

$usua_nom = $_REQUEST['txt_usua_nom'];
$usua_pwd = $_REQUEST['txt_usua_pwd'];

conectar();

$txtsql = "SELECT cveusuar, nomusuar ,pwdusuar 
			FROM tblusuar 
			WHERE nomusuar = '".$usua_nom."' 
			AND pwdusuar=MD5('".$usua_pwd."')
			AND actusuar = cast(1 as bit);";
$sql = pg_query($txtsql);
$cont = 0;

/*Recorre la consulta*/
while ($rows = pg_fetch_array($sql)) 
{
	$_SESSION['cveusuar'] = $rows['cveusuar'];
	$_SESSION['nomusuar'] = $rows['nomusuar'];
	$cont = $cont + 1;
}


/*Si el usuario esta dentro de la bd*/
if ($cont == 1)
{

echo "
		<script type='text/javascript'>
			window.location='../';
		</script>
	 ";
// 	header ("Location: ../");
}
/*Si el usuario no esta en la bd imprime el formulario de acceso con un mesaje de error en la contraseña o usuario|*/
else
{
	echo "
			<!DOCTYPE html>
			<html lang='es'>
			<head>
				<meta charset='utf-8'>
				<title>Acceso Estadistico IUEM</title>
				<style type='text/css'>
					#div_acces
					{
						position: absolute;
						left: 50%;
						top: 50%;
					}
				</style>
			</head>
			<body>
			<div id='div_acces'>
			<form name='frm_acces' id='frm_acces' action='acceso.php' method='post'>
				<table>
					<tr align='center'>
						<td><input type='text' placeholder='usuario' required id='txt_usua_nom' name='txt_usua_nom' value='".$usua_nom."''><br>
						<input type='password' placeholder='Contraseña' required id='txt_usua_pwd' name='txt_usua_pwd' value='".$usua_pwd."''><br>
						<input type='submit' value='accesar'></td>
					</tr>
					<tr><td>El nombre de usuario o el password es incorrecto</td></tr>
				</table>
			</form>
			</div>
			</body>
	";
}
desconectar();

?>