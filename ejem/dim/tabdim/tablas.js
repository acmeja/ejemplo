//*********************************************************************
//Nombre: tablas.js
//Funcion del Modulo: Funciones para el apartado  de tablas
//Fecha: 01/04/2013
//Relizo: Javier Acosta Mejia
//*********************************************************************		



//*********************************************************************
//Variables Globales del modulo y su Definición
//*********************************************************************
var ltext_options = "";// variable para guardar los options generados por php de un select 
filas = new Array(); // Array para las columnas
var lint_numcol = 0; //Numero de columnas existentes
var ltxt_tblnom =""; //Nombre de la tabla

//*********************************************************************
//Nombre: grainputs
//Descripcion: genera los inputs necesarios para las columnas que formaran la tabla
//Parametros: numcols-> numero de columnas,ltxt_tblnom ->  nombre de la tabla
//Realizo: Javier Acosta Mejía
//Fecha: 01/04/2013
//*********************************************************************

function grainputs(numcols,tblnom)
{	
 	
 	lint_numcol = numcols;
 	ltxt_tblnom = tblnom;
 	divtblnom.innerHTML = "Tabla: <input type='text' name='txtnomtbl' id='txtnomtbl' value='"+tblnom+"'/> Descripción: <input type='text' name='txttbldes' id='txttbldes' size='30'>";
 	var inputs = "";
  	//Llena arreglo con objetos
 	ltext_options = obtener_text_php("../lib/lib_fun.php?crglist=tbltpodts");

	//Genera los inputs para ser llenados
 	for (var i = 0; i < numcols; i++) 
 	{

  		inputs = inputs +"<tr>"
  				+"<td><input type='text' id='txtnom"+i+"' name='txtcol[]' value='"+filas[i].nom+"'  size='10' onblur='validar_input("+'"'+"nombre"+'"'+",this,"+'"'+"divnom"+i+'"'+");'/><div class='divmsj' id='divnom"+i+"'></div></td>"
	  			+"<td><select id='ls"+i+"' name='ls"+i+"' onchange='verifica_tpo_dato("+'"'+"txtlon"+i+'"'+",this.value);'>"+ltext_options+"</select></td>"
  				+"<td><input type='text' id='txtlon"+i+"' name='txtlon' value='"+filas[i].lon+"'  size='10' onblur='validar_input("+'"'+"numero"+'"'+",this,"+'"'+"divlon"+i+'"'+");' /><div class='divmsj' id='divlon"+i+"'></div></td>"
  				+"<td><input type='checkbox' id='txtnul"+i+"' name='txtnul"+i+"' /></td>"
  				+"<td><input type='text' id='txtley"+i+"' name='txtcol[]' value='"+filas[i].ley+"' size='20' /></td>"
  				+"<td><input type='text' id='txtcom"+i+"' name='txtcol[]' value='"+filas[i].com+"' size='25' /></td>"
  				+"<td><input type='text' id='txtval"+i+"' name='txtcol[]' value='"+filas[i].val+"' size='20' /></td>"
  				+"</tr>";   		
 	}


 	divnvatabla.innerHTML = "<form><table align='center'>"
 							+"<tr><td align='center'>Nombre</td><td align='center'>Tipo</td><td align='center'>Longitud</td><td align='center'>Nulo</td><td align='center'>Leyenda</td><td align='center'>Comentario</td><td align='center'>Validación</td></tr>"
 							+inputs
 							+"<tr>"
 							+"<td colspan='7' align='center'>Añadir <input type='text' name='txtnumcolsa[]' id='txtnumcolsa' size='5' onblur='validar_input("+'"'+"numero"+'"'+",this,"+'"'+"divcolsa"+'"'+");'> Columna(s)"/*Añadimos tambien un input por si el usuario quiere añadir mas campos*/
 							+"<input type='button' value='Aceptar' onclick='javascript:agregar_columnas(obt_val_elem("+'"'+"txtnumcolsa"+'"'+"));' class='input1'/></td></tr>"
 							+"<tr><td colspan='7' align='right'><input type='button' value='Guardar' onclick='javascript:guarda_tabla_bd();' class='input1'></td></tr>"
 							+"<tr><td colspan='7' align='center'><div id='divcolsa' ></td></tr>"
 							+"</table></form>";



 	for (var i = 0; i < numcols; i++) 
 	{
 		document.getElementById('ls'+i).value = filas[i].tpo;

 		if (filas[i].nul == 'not null')
 		{
 			document.getElementById('txtnul'+i).checked = false; 			
 		}
 		else
 		{
 			document.getElementById('txtnul'+i).checked = true;	
 		}

 	}

}


//*********************************************************************
//Nombre: agregar_columnas
//Descripcion:  agrega nuevas columnas junto con la que ya estan
//Parametros: numcols -> numero de columnas a agregar
//Realizo: Javier Acosta Mejía
//Fecha: 02/04/2013
//*********************************************************************		
function agregar_columnas(numcols)
{
	llena_array_columnas();
	llena_array_inicio_columnas(parseInt(numcols)+parseInt(lint_numcol),lint_numcol);
	lint_numcol = parseInt(lint_numcol) + parseInt(numcols);	
	grainputs(lint_numcol,ltxt_tblnom);
}



//*********************************************************************
//Nombre: llena_array_columnas
//Descripcion: extrae las valores de los inputs para llenar el arreglo de objetos de las columnas 
//Realizo: Javier Acosta Mejía
//Fecha: 02/04/2013
//*********************************************************************		
 function llena_array_columnas()
 {

 	var ltxt_valnul = "";

 	for (var i = 0; i < lint_numcol; i++) 
 	{
 		if (document.getElementById("txtnul"+i).checked)
 		{
 			ltxt_valnul = ""; 			
 		}
 		else
 		{
 			ltxt_valnul = "not null";
 		}

 		filas[i] = new columna(obt_val_elem('txtnom'+i),obt_val_elem('ls'+i),obt_val_elem('txtlon'+i),ltxt_valnul,obt_val_elem('txtley'+i),obt_val_elem('txtcom'+i),obt_val_elem('txtval'+i));
 	}

 }	



//*********************************************************************
//Nombre: llena_array_inicio_columnas
//Descripcion: hace un prellenbado del arreglo de objetos de columnas para poder reusar la funcion grainputs al momento de añadir mas columnas
//Parametros: numcols-> numero de columnas a guardar en el arreglo, lint_inicia-> numero en que que tiene que iniciar el apuntador del arreglo
//Realizo: Javier Acosta Mejía
//Fecha: 02/04/2013
//*********************************************************************		
  function llena_array_inicio_columnas(numcols,lint_inicia)
 {
 	for (var i = lint_inicia; i < numcols; i++)
  	{  		
 	 	filas[i] = new columna('',7,'45','not null','','','');
  	}
 }	




//*********************************************************************
//Nombre: verifica_tpo_dato
//Descripcion: habilita o deshabilita el cuadro de texto relacionado con la longitud del tipo de dato en este caso solo es para 
//				decimal y character los cuales tienen como clave en la bd 7 y 3
//Parametros: txtid -> identificador del campo de texto a habilitar o deshabilitar, valtpodato-> clave del tipo de dato en la bd a evaluar
//Realizo: Javier Acosta Mejía
//Fecha: 01/04/2013
//*********************************************************************
function verifica_tpo_dato(txtid,valtpodto)
{
 	if (valtpodto == 7 || valtpodto == 3)
 	{
 		document.getElementById(txtid).disabled = false; 		
 		document.getElementById(txtid).value = 10;
 	}
 	else
 	{
 		document.getElementById(txtid).disabled = true;
 		document.getElementById(txtid).value = "";
 	}
}



//*********************************************************************
//Nombre: columna
//Descripcion: objeto de tipo columna  
//Parametros: nom->nombre de la columna,tpo-> tipo de dato,lon-> longitud,
//			  nul-> ¿es nulo?,ley->leyenda del campo,com-> comentario del campo,val-> validacion del campo
//Realizo: Javier Acosta Mejía
//Fecha: 01/04/2013
//*********************************************************************
function columna(nom,tpo,lon,nul,ley,com,val)
{
	this.nom = nom;
	this.tpo = tpo;
	this.lon = lon;
	this.nul = nul;
	this.ley = ley;
	this.com = com;
	this.val = val;
}


//*********************************************************************
//Nombre: guarda_tabla_bd
//Descripcion: contruye la sentencia SQL para generar la tabla en base a los campos capturados y envia los datos de la tabla
//				con sus campos para que sean guardados en la tabla maestra de la bd
//Realizo: Javier Acosta Mejía
//Fecha: 02/04/2013
//*********************************************************************
function guarda_tabla_bd()
{

	llena_array_columnas();	
	var sqltxt = "";
	var lint_ultcol = filas.length - 1 ;
	var ltxt_tpodts = "";
	var ltxt_colsval = "";
	var ltxt_long = 0;
	var ltxt_nul_aux = 0;
	ltxt_tblnom = txtnomtbl.value;	
	if (validar_vacios('txtcol') && verificar_errores_input())
	{		
		for (var i = 0; i < filas.length; i++)
		{
			var select = document.getElementById('ls'+i);
			ltxt_tpodts = select.options[select.selectedIndex].innerHTML;//obtenemos el texto del <option> en el <select>

			if (filas[i].lon != '') // si el tipo de dato tiene longitud
			{
				ltxt_long = filas[i].lon;
				filas[i].lon = "("+filas[i].lon+")";// conctatena longitud entre parentesis				
			}
			if (lint_ultcol == i) // si  es la ultiuma columna no pone coma para separar los campos
			{
				sqltxt = sqltxt+filas[i].nom+" "+ltxt_tpodts+''+filas[i].lon+" "+filas[i].nul; // definicion del campo para la estructura de la tabla				
			}
			else // si no es la ultima columna pone coma para separar los campos
			{
				sqltxt = sqltxt+filas[i].nom+ " " +ltxt_tpodts+''+filas[i].lon+" "+filas[i].nul+","; // definicion del campo para la estructura de la tabla tblcolumn
			}

			if (filas[i].nul == 'not null')
 			{		
 				ltxt_nul_aux = '(cast(0 as bit))'; 			
 			}
 			else 
 			{
 				ltxt_nul_aux = '(cast(1 as bit))';	
 			}

			ltxt_colsval = ltxt_colsval+"|'"+filas[i].nom+"'|'"+select.value+"'|'"+ltxt_long+"'|"+ltxt_nul_aux+"|'"+filas[i].ley+"'|'"+filas[i].com+"'|'"+filas[i].val+"'|'ultid'|sep1"; // definicion del campo para insertarlo en la tabla de campos		
		}	

		var link = "../lib/lib_fun.php?chkreg=SELECT * FROM tblmaestr WHERE nomaestr ='"+ltxt_tblnom+"'";

		var resp = obtener_text_php(link); 

		if (parseInt(resp) == 0 && valida_campo_repetido() == true) // si la tabla no existe  y no hay campos de igual nombre
		{		
			var lint_cveusr= obtener_text_php("../sesion/sesion.php?cveusr=1"); // obtener la clave del usuario activo						
			var ltxt_tblval = "|'"+ltxt_tblnom+"'|'"+obt_val_elem('txttbldes')+"'|(select cast(1 as bit))|"+lint_cveusr+"|fecha";   // compone cadena con valores para enviar
			sqltxt = "CREATE TABLE "+ltxt_tblnom+" ("+sqltxt+")";
			document.location.href= "nvatabla.php?add="+ltxt_tblval+"&ejecsql="+sqltxt+"&colsvals="+ltxt_colsval;

		}
		else
		{
			alert("el nombre de la tabla "+ltxt_tblnom+", ya existe");
		}
		
		
	}
}


//*********************************************************************
//Nombre: validacion_tabla
//Descripcion: funcion auxiliar para validar vacios y errores de entrada en los inputs
//Realizo: Javier Acosta Mejía
//Fecha: 02/04/2013
//*********************************************************************
function validacion_tabla()
{
	if ( validar_vacios('txttbl') && verificar_errores_input() )
	{
		llena_array_inicio_columnas(obt_val_elem('txtnumcols'),0);
		grainputs(obt_val_elem('txtnumcols'),obt_val_elem('txttblnom'));	
	}
	
}

//*********************************************************************
//Nombre: valida_campo_repetido
//Descripcion: funcion para validar que no existan campos repetidos
//Realizo: Javier Acosta Mejía
//Fecha: 11/04/2013
//*********************************************************************
function valida_campo_repetido()
{
	var ltxt_column  = "";
	var i = 0;
	var resp = true ;
	while ( i < filas.length) 
	{
		if (filas[i].nom == ltxt_column )
		{
			resp = false;
			alert('La columna " '+filas[i].nom+' ", ya existe');
			i =  filas.length;

		}
		ltxt_column = filas[i].nom;
	 i++;
	}

	return resp;
}
