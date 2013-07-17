<?php
include("/sesion/sesion.php");
include("/lib/functions.php");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);  
chk_acces();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Agrega Variables</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="estilos/estilogral.css">
	<style type="text/css">
		#div_mnu
		{
			position: absolute;
			top: 80px;
			left: 90px;
			width: 400px;
			background:#CDD3DF;
			height: 80%;
			border-radius: 5px;
			border:2px solid #D7FDBF;
		}
		#div_cont
		{
			position: absolute;
			top: 80px;
			left: 500px;
			width: 62%;
			background: #CDD3DF;
			height: 80%;
			border-radius: 5px;	
			border:2px solid #D7FDBF;
			font-size: 12px;
			font-family: tahoma;
		}	

		#obj_cont
		{
			width: 100%;
			height: 100%;
		}
	</style>
	<script type="text/javascript" src="lib/lib_fun.js"></script>
    <script type="text/javascript" src="lib/libreria.js"></script>
</head>
<body>
	<label>Bienvenido: <?php echo $_SESSION['nomusuar'];?> |<a href="sesion/sesion.php?cerrar=1">   Cerrar Sesion</a></label>
	<div id="div_cont">
	  
	</div>
	<div id="div_mnu">
		<br>
		<table>
			<tr>
				<td> <label><strong>Tabla:</strong></label></td>
			</tr>
			<tr>
				<td>
					<select id="slc_tbla" name="slc_tbla" class="boton1">
						<option>Empleados</option>
						<option>tba_2</option>
					</select>
					<br>
					<input type="button" value="Nueva" id="btn_add" name="btn_add" onclick="cambiar('tabdim/nvatabla');" class="boton1">
					<input type="button" value="Modificar" id="btn_mod" name="btn_mod" onclick="cambiar('captura');" class="boton1">
					<input type="button" value="Eliminar" id="btn_del" name="btn_del"  onclick="" class="boton1">
				</td>
			</tr>
		</table>
        <hr>
        <?php
			if(!isset($_SESSION['archAgregar'])){
				$archAgregar = "generado/auxiliar1".time();
				$_SESSION['archAgregar']= $archAgregar;
			}
			if(!isset($_SESSION['tablaSelec']) && $_POST['tabla']!= "" || $_SESSION['tablaSelec']!= $_POST['tabla'] && $_POST['tabla']!= ""){
				$_SESSION['tablaSelec'] = $_POST['tabla'];
			}
		?>
      <table width="100%" border="0">
          <tr align="center">
            <td>Seleccione una tabla</td>
            <td>Tabla seleccionada: <b><?php if($_SESSION['tablaSelec'] != '') echo obtieneNombreTabla($_SESSION['tablaSelec']);?></b></td>
          </tr>
        <tr align="center">
            <td><?php echo generaListaTablas(""); ?></td>
            <td>
          <?php if($_SESSION['tablaSelec'] != ''){
				    $campo = array(); //variable para realizar filtro
					$_SESSION['campo'] = $_POST['campo']; //variable para realizar filtro
					$_SESSION['valorBuscar'] = $_POST['valorBuscar']; //variable para realizar filtro
					$idTabla = $_POST['idTabla']; //variable para realizar filtro 					
			  		obtieneCampos($_SESSION['tablaSelec']);
					if(isset($_SESSION['campo']) && $_SESSION['campo']!=""){
						echo '<script type="text/javascript">cambiar('."'captura/consulta'".');</script>';
					}//$tablaconsulta=genTablaConsulta($_SESSION['tablaSelec'],obtieneRegistrosTabla($_SESSION['tablaSelec'],obtieneCamposEsp($_SESSION['tablaSelec'],"nomcolumn"),""),"../");
					/*if(isset($valorCampo)){
						$tablaFiltro=genTablaConsulta($idTabla,obtieneRegistrosTabla($idTabla,$campo,$valorCampo),"../");
						if(isset($tablaFiltro) && $tablaFiltro[1] !=""){
							echo '<script type="text/javascript"> cambiar('."'".$_SESSION['archFiltrar']."'".'); </script>';
						}
					}*/
			  		echo '<input type="button" name="botonAgregar" id="botonAgregar" onClick="cambiar('."'".$_SESSION['archAgregar']."'".')" value="Agregar registro"/>  <input type="submit" name="consulta"
id="consulta"
value="Consulta Registros"
onClick="cambiar('."'captura/consulta'".')"/>';// se colocan los botones
			 }//fin tablaSelec
			  ?></td>
          </tr>
          <tr><td colspan="2" align="center"><?php echo  '<label class="mensaje">'.$_SESSION['mensaje']."</label>"; unset($_SESSION['mensaje']);?></td></tr>
      </table>
    </div>	
</body>
</html>