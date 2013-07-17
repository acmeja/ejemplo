<?php
 include("../obj/obj_tblmaestr.php");
  include("../obj/obj_tblcolumn.php");
 include("../lib/lib_fun.php");

 
 $obj_tbl = new tblmaestr();// Genera nuevo objeto de tipo tblmaestr, en el archivo obj_tblmaestr
 if ($_REQUEST['add'])// si recibe la variable add para agregar registro
 {
 	$obj_tbl->arr_cols_val = explode("|",$_REQUEST['add']); // llena el array con los valores enviados desde javascript 
 	if (ejecutar_ddl($_REQUEST['ejecsql'])) // si ejecuto el sql para crear la tabla, en el archivo lib_fun.php 
 	{
 		try
 		{
 			$idtbldin = agregar_registro($obj_tbl); //agrega la tabla a la tabla de registro 'tablamestr' y recibe el identificador del registro agregado
 			if ( $idtbldin ) // si agrego el registro de la tabla 
 			{
 				try
 				{
	 				$obj_column = new tblcolumn();
 					$ltxt_vals = str_replace('ultid',$idtbldin,$_REQUEST['colsvals']);
 					$obj_column->arr_cols_vals = explode("sep1",$ltxt_vals);
 					if (agrega_registros($obj_column)) // si agrega los campos a la tabla 'tblcolumn'
 					{
 						echo "add";
 					}
 			
 				}
 				catch(Exception $e)
 				{
 					echo "ocurrio un error";
 					ejecutar_ddl("DELETE FROM tblmaestr WHERE cvemaestr=".$idtbldin);
 					ejecutar_ddl("DROP TABLE ".$$obj_tbl->$arr_cols[1]);
 					//echo $e->getMessage();
 				}
 			} 		

 		}
 		catch(Exception $ex)
 		{
 			ejecutar_ddl("DROP TABLE ".$$obj_tbl->$arr_cols[1]);
 			//echo $ex->getMessage();
 		} 
 	}
 	 	
 }


?>