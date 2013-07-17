<?php
//*********************************************************************
//Nombre: lib_func.php
//Funcion del Modulo: Libreria de funciones globales php
//Fecha: 01/04/2013
//Relizo: Javier Acosta Mejia
//*********************************************************************		

include('../conexion.php');//Incluiye el archivo de conexión
//Para activar las funciones verifica si se envia una variable y en base al nombre de la variable enviada se ejecuta alguna funcion determinada

//*********************************************************
// Variables Gobales
//*********************************************************
	$ltxt_cols="";
 	$ltxt_vals="";
 	$ltxt_sqltxt="";

//Si recibe la variable crglist para cargar options de un select
if (isset($_REQUEST['crglist']))
{
	 carga_lsoption($_REQUEST['crglist']);
}

//Si recibe ejcsql para ejecutar una instruccion sql 
if (isset($_REQUEST['ejcsql']))
{
	ejecutar_ddl($_REQUEST['ejcsql']);
}

//Si recibe chkreg verifica si existe el registro en la bd
if(isset($_REQUEST['chkreg']))
{
	verificar_resistro($_REQUEST['chkreg']);
}



//**********************************************************************
//Nombre: carga_lsoption
//Descripción: lee de la base de datos una tabla enviada como parametro para imprimir dos columnas (1,2) y 
//				todas la filas en <option> html que seran insertados en un <select>
//Parametros: $tbllist-> nombre de la tabla a imprimir
//Realizo: javier Acosta Mejía
//Fecha: 01/04/2013
//***********************************************************************	
function carga_lsoption($tbllist)
{
	conectar();
	$sql = pg_query("SELECT  * FROM ".$tbllist." ORDER BY 2");
	while ($rows = pg_fetch_array($sql)) 
	{
		echo "<option value='".$rows[0]."'>".$rows[1]."</option>";
	}
	desconectar();

}

//**********************************************************************
//Nombre: ejecutar_ddl
//Descripción: ejecuta el codigo SQL en la bd
//Parametros: $sqltxt-> cadeda de texto que contiene la isntruccion sql
//Realizo: Javier Acosta Mejía
//Fecha: 01/04/2013
//***********************************************************************	
function ejecutar_ddl($sqltxt)
{
	
	conectar();
	if (pg_query($sqltxt) or die(pg_last_error()))
	{
		return true;
	}
	else
	{
		return false;
	}
	desconectar();
}

//**********************************************************************
//Nombre: verificar_resistro
//Descripción: búsca si existe la el registro en la bd
//Parametros: $sqltxt-> cadeda de texto que contiene la isntruccion sql
//Realizo: Javier Acosta Mejía
//Fecha: 03/04/2013
//***********************************************************************	
function verificar_resistro($sqltxt)
{
	conectar();
	$sql = pg_query($sqltxt);
	$cont = 0;
	while ($rows = pg_fetch_array($sql))
	{
		$cont = $cont + 1;
	}

	if ($cont == 1 )
	{
		echo $cont;
	}
	else
	{
		echo $cont;
	}
}



//**********************************************************************
//Nombre: agregar_registro
//Descripción: se encarga de llamar la funcion que genera la cadena de columnas y valores para agregar un registro a la tabla solicitada
//Parametros: $lobj_agregar->objeto recibido en base a una tabla
//Retorna:  $sqlpg[0]-> es el valor del id agregado o el valor de la primera columna en la tabla, false-> si es que la insercion fallo
//Realizo: Javier Acosta Mejía
//Fecha: 04/04/2013
//***********************************************************************	
function agregar_registro($lobj_agregar)
{
	global $ltxt_cols; // declaramos las variables globales a usar
	$larr_cols_vals =  gra_sqltxt_insert($lobj_agregar->arr_cols,$lobj_agregar->arr_cols_val); 
	$ltxt_sqltxt = "INSERT INTO ".$lobj_agregar->tblnombre." (".$larr_cols_vals[0].") VALUES (".$larr_cols_vals[1].") RETURNING ".$lobj_agregar->arr_cols[0];
	conectar();
	$sqlpg = pg_query($ltxt_sqltxt) or die(pg_last_error());
	if ($sqlpg)
	{
		$id = pg_fetch_array($sqlpg);		
		return $id[0];
	}
	else 
	{
		return false;
	}
	desconectar();

}

//**********************************************************************
//Nombre: agregar_registros
//Descripción: se encarga de llamar la funcion que genera la cadena de columnas y valores para agregar un registro a la tabla solicitada
//Parametros: $lobj_agregar->objeto recibido en base a una tabla
//Retorna:  true->si el conjunto de registros fueron insertados, false-> si es que la insercion fallo
//Realizo: Javier Acosta Mejía
//Fecha: 04/04/2013
//***********************************************************************	

function agrega_registros($lobj_agregar)
{
	global $ltxt_sqltxt; 	
	$larr_cols_vals = gra_sqltxt_inserts($lobj_agregar->arr_cols,$lobj_agregar->arr_cols_vals);	
	$ltxt_sqltxt = "INSERT INTO ".$lobj_agregar->tblnombre." (".$larr_cols_vals[0].") VALUES ".$larr_cols_vals[1].";";
	conectar();
	if (pg_query($ltxt_sqltxt) or die(pg_last_error()))
	{
		return true;		
	}
	else 
	{
		return false;
	}
	desconectar();

}

//**********************************************************************
//Nombre: actualiza_registro
//Descripción: se encarga de generar y ejecutar la instruccion de actualizar un registro junto con sus condiciones
//Parametros: $lobj_actualizar->objeto recibido en base a una tabla
//Retorna:  true->si el  registro fue insertado, false-> si es que la insercion fallo
//Realizo: Javier Acosta Mejía
//Fecha: 09/04/2013
//***********************************************************************	
function actualiza_registro($lobj_actualizar)
{
	global $ltxt_sqltxt;
	$larr_cols_vals =  gra_sqltxt_insert($lobj_actualizar->arr_cols,$lobj_actualizar->arr_cols_val);
	$larr_cols_aux = explode(',',$larr_cols_vals[0]);
	$larr_vals_aux = explode(',',$larr_cols_vals[1]);
	
	for ($i=0; $i < count($larr_cols_aux); $i++)
	{ 
		$ltxt_sqltxt = $ltxt_sqltxt.$larr_cols_aux[$i]."=".$larr_vals_aux[$i].",";
	}
	  
	 $ltxt_sqltxt = substr($ltxt_sqltxt,0,-1); // eliminamos la ultima coma

	$ltxt_sqltxt = "UPDATE ".$lobj_actualizar->tblnombre." SET ".$ltxt_sqltxt." WHERE ".gra_condiciones_sqltxt($lobj_actualizar->arr_cols,$lobj_actualizar->arr_col_con);
	$ltxt_sqltxt = substr($ltxt_sqltxt,0,-1);
	echo $ltxt_sqltxt."<br>";

}

//**********************************************************************
//Nombre: elimina_registro
//Descripción: se encarga de generar y ejecutar la instruccion para eliminar un registro junto con sus condiciones
//Parametros: $lobj_actualizar->objeto recibido en base a una tabla
//Retorna:  true->si el  registro fue insertado, false-> si es que la insercion fallo
//Realizo: Javier Acosta Mejía
//Fecha: 11/04/2013
//***********************************************************************
function elimina_registro($lobj_actualizar)
{
	global $ltxt_sqltxt;
	$ltxt_sqltxt = "UPDATE ".$lobj_actualizar->tblnombre." SET fecbaja='".date("Y/m/d")."' WHERE ".gra_condiciones_sqltxt($lobj_actualizar->arr_cols,$lobj_actualizar->arr_col_con);
	echo $ltxt_sqltxt."<br>";
}

//**********************************************************************
//Nombre:  gra_condiciones_sqltxt
//Descripción: genera las condiciones de una instruccion sql
//Parametros: $larr_columns->arreglo que contiene las columnas,$larr_valores-> arreglo que contiene los valores
//Retorna:  $ltxt_condicion-> cadena con la instruccion de la condicion sql
//Realizo: Javier Acosta Mejía
//Fecha: 10/04/2013
//***********************************************************************
function gra_condiciones_sqltxt($larr_columns,$larr_valores)
{

	$ltxt_condicion = "";

	for ($i=0; $i < count($larr_columns); $i++)
	{ 
		if ($larr_valores[$i])
		{
			$ltxt_condicion = $ltxt_condicion." ".$larr_columns[$i]."=".$larr_valores[$i]." AND";
		}		
	}
	$ltxt_condicion = substr($ltxt_condicion,0,-3);
	return $ltxt_condicion;
}

//**********************************************************************
//Nombre:  gra_sqltxt_insert
//Descripción: genera las cadenas de valores y columnas a usarse para generar la  instruccion sql que inserte un registro
//Parametros: $lobj-> objeto recibido en base a una tabla
//Retorna: $larr_cols_vals-> arreglo que contiene dos elementos, en el primero guarda las columnas a usar en la sentencia sql de insercion y en el segundo guarda los valores 
//Realizo: Javier Acosta Mejía
//Fecha: 04/04/2013
//***********************************************************************
function gra_sqltxt_insert($larr_cols,$larr_cols_val)
{
	$i=0;
	// recorre los arreglos de columnas y valores del arreglo en el objeto enviado 		
	while ( $i < count($larr_cols))
	{		

 		if ($larr_cols_val[$i])
 		{
 			if ($larr_cols_val[$i] == 'fecha')
 			{
 				$larr_cols_val[$i] = "'".date('Y/m/d')."'";	
 			}
 			$ltxt_cols = $ltxt_cols.",".$larr_cols[$i]; 			
 			$ltxt_vals = $ltxt_vals.",".$larr_cols_val[$i];
 		}		
 		$i++;
	}

	$larr_cols_vals[0] = substr($ltxt_cols,1);
	$larr_cols_vals[1] = substr($ltxt_vals,1);

	return $larr_cols_vals;

}

//**********************************************************************
//Nombre:  gra_sqltxt_inserts
//Descripción: genera las cadenas de valores y columnas a usarse para generar la  instruccion sql que inserte un registro
//Parametros: $larr_cols-> arreglo con las columnas existentes en la tabla,$larr_cols_vals-> arreglo con el conjunto de valores a insertar
//Retorna: $larr_aux2-> arreglo que contiene dos elementos, en el primero guarda las columnas a usar en la sentencia sql de insercion y en el segundo guarda los valores 
//Realizo: Javier Acosta Mejía
//Fecha: 04/04/2013
//***********************************************************************
function gra_sqltxt_inserts($larr_cols,$larr_cols_vals)
{
	$i = 0;
	$larr_aux = "";
	$larr_aux2;
	$larr_vals_aux = "";
	$lint_stop = count($larr_cols_vals) -1; 
	while ($i < $lint_stop)
	{		
		$larr_aux = explode("|",$larr_cols_vals[$i]);				
		$larr_aux2 = gra_sqltxt_insert($larr_cols,$larr_aux);
		$larr_vals_aux  = $larr_vals_aux.",(".$larr_aux2[1].")";
		$i++;
	}
	
	$larr_aux2[1] = substr($larr_vals_aux, 1);
 	return $larr_aux2;
}




?>