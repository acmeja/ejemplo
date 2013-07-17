//*********************************************************************
//Nombre: lib_func.js
//Funcion del Modulo: Libreria de funciones globales javascript
//Fecha: 01/04/2013
//Relizo: Javier Acosta Mejia
//*********************************************************************		



//**********************************************************************
//Nombre: NuevoAjax
//Descripción: genera un nuevo objeto ajax
//Parametros:
//Retorna: nuevo objeto ajax
//Realizo: javier Acosta Mejía
//Fecha: 01/04/2013
//***********************************************************************
function NuevoAjax()
{
	var xmlhttp = false;

	try
	{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined')
	{
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

//*****************************************************************************
//Nombre: cargarcontededor
//Descripcion: Funcion para cargar alguna pagina dentro de un div
//Parametros: link= ruta y/o nombre del archivo a cargar dentro de un contenedor
//			div = id del div donde se va a cargar la pagina  
//Realizo: Javier Acosta Mejia
//Fecha: 01/04/2013
//*******************************************************************************
function cargarcontededor(liga,div)
{
	var codr = document.getElementById(div);
	ajax = NuevoAjax();
	ajax.open("POST",liga,false);
	ajax.onreadystatechange = function ()
	{
		if (ajax.readyState == 4)
		{
			codr.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

//*********************************************************************
//Nombre: cambiar
//Descripcion: Cambia la pagina que esta dentro del contenedor o div 
//Parametros: ltxt_pag-> nombre de la pagina a cargar dentro del contenedor
//Realizo: Javier Acosta Mejía
//Fecha: 01/04/2013
//*********************************************************************
function cambiar(ltxt_pagina)
{
	div_cont.innerHTML = "<object data='"+ltxt_pagina+".html' id='obj_cont'></object>";
}

//*********************************************************************
//Nombre: obtener_text_php
//Descripcion: carga archivos php mediante ajax y devuelve el texto generado por el php
//				Un ejemplo de como usarlo podemos hacerlo para:
//				obtiener los registros de una tabla en la bd para generar los <option> de un <select>
//				para poder usarla debemos enviar la variable "crglist" con el nombre de la tabla que se quiera cargar en un select
//				y el nombre del archivo es "lib_fun.php" que se encuentra en la carpeta "lib"
//				ejem; lib_fun.php?crglist=tbltpodts
//Parametros: link-> direccion del archivo .php que extrae de la bd los registros 
//Realizo: Javier Acosta Mejía
//Fecha: 01/04/2013
//*********************************************************************	
function obtener_text_php(link)
{	
  	ajax = NuevoAjax();
	ajax.open("POST",link,false);
	var ltext_ajax = ""
	ajax.onreadystatechange = function ()
	{
		if (ajax.readyState == 4)
		{
			ltext_ajax = ajax.responseText;						

		}
	}

	ajax.send(null);
	return ltext_ajax;	
}


//*********************************************************************
//Nombre: obt_val_elem 
//Descripcion: obtiene el valor de un input
//Parametros: ltxt_idelem -> identificador del input
//Retorna: valor -> valor introducido del input
//Realizo: Javier Acosta Mejía
//Fecha: 02/04/2013
//*********************************************************************	
function obt_val_elem(ltxt_idelem)
{
	var valor = document.getElementById(ltxt_idelem).value;
	return valor;
}


//**************************************************************
//Nombre: verificar_entrada
//Descripcion: Función para la validacion de entradas, para mas explicacion ver la tabla de simbolos de la parte inferior de este archivo 
//Parametros: tipo = tipo de valor a validar, valor = el valor a evaluar de acuerdo al tipo de dato enviado
//Realizo: Javier Acosta Mejia
//Fecha: 03/04/2013
//**************************************************************

function verificar_entrada(tipo,valor)
{
	var resp = true ;

	var expresion = "";
	switch (tipo)
	{
		case "fecha": // expresion para validar fecha con el formato: 01/01/2013 
			expresion = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
		break;

		case "entero": // expresion para validar numero con signo
			expresion = /^(?:\+|-)?\d+$/;
		break;

		case "decimal": // expresion para validar numero con decimal
			expresion = /^[0-9]+[\.]*[0-9]*$/;
		break;

		case "nombre": // expresion para validar cadena de texto iniciando con cualquier letra mayuscula o nimuscula y enseguda puede ir un numero o no
			expresion = /^[a-zA-Z]+[0-9]*$/;
		break;

		case "nombreb": // expresion para validar cadena de texto iniciando con cualquier letra mayuscula o nimuscula y enseguda puede ir un numero o no con espacios 
			expresion = /^[a-zA-Z]+[\d|\b]*$/;
		break;

		case "numero": // expresion para validar numero positivo entero
			expresion =/^\d+$/;
		break;


	}

	if ((valor.match(expresion)) && (valor != ''))
	{
		resp = true ;
	}
	else
	{
		resp = false;
	}

	return resp;
}


//**************************************************************
//Nombre: validar_input
//Descripcion: verifica si el valor introducido es correcto en base al tipo de dato requerido si no es valido pone el foco en el input digitado
//Parametros: tipo-> tipo de valor,objinput-> objeto input
//Realizo: Javier Acosta Mejia
//Fecha: 03/04/2013
//**************************************************************
function validar_input(tipo,objinput,iddiv)
{
	var div = document.getElementById(iddiv);
	if (!verificar_entrada(tipo,objinput.value))
	{
		//alert('El valor introducido no es valido');		
		div.innerHTML = '* El valor no es valido';
		div.style.color = '#FF0040';
  		if(0 != 1)
  		{
    	// Así damos de nuevo el foco al INPUT
    		setTimeout(function () { objinput.focus() }, 0);    
    	}
  	}
  	else
  	{
  		div.innerHTML = "";
  	}
}



//**************************************************************
//Nombre: validar_vacios
//Descripcion: verifica que los inputs de un arreglo solicitado no esten vacios y pone el foco en el campo vacio
//Parametros: objinputs -> arreglo de inputs
//Realizo: Javier Acosta Mejia
//Fecha: 03/04/2013
//**************************************************************
function validar_vacios(objinputs)
{
	var input_s = document.forms[0].elements[objinputs+'[]'];
	var resp = true;

	for (var i = 0; i < input_s.length; i++) 
	{
		if (input_s[i].value == "" &&  input_s[i].disabled == 'disabled')
		{
			alert('Campo requerido');
			input_s[i].focus();
			resp = false;
			return resp;	
		}
	}

	return resp;
}


function verificar_errores_input()
{
	
	var div_error = document.getElementsByTagName('div');	
	var resp = true;
	var i = 0;
	while ( i < div_error.length) 
	{		
		if (div_error[i].className == 'divmsj' &&  div_error[i].innerHTML != '')			
	 	{	 		
	 		resp = false ;
	 		i = div_error.length;
	 	}
	 i++;
	}

	return resp;
}

/*
													Tabla de simbolos para generar expresiones regulares de validación
#########################################################################################################################################################################
# Carácter	# Texto buscado																																				#
#########################################################################################################################################################################
#  ^		#	Principio de entrada o línea.																															#
#  $		#	Fin de entrada o línea.																																	#
#  *		#	El carácter anterior 0 o más veces.																														#
#  +		#	El carácter anterior 1 o más veces.																														#
#  ?		#	El carácter anterior una vez como máximo (es decir, indica que el carácter anterior es opcional).														#
#  .		#	Cualquier carácter individual, salvo el de salto de línea.																								#
#  x|y		#	x o y.																																					#
#  {n}		#	Exactamente n apariciones del carácter anterior.																										#
#  {n,m}	#	Como mínimo n y como máximo m apariciones del carácter anterior.																						#
#  [abc]	#	Cualquiera de los caracteres entre corchetes. Especifique un rango de caracteres con un guión (por ejemplo, [a-f] es equivalente a [abcdef]).			#
#  [^abc]	#	Cualquier carácter que no esté entre corchetes. Especifique un rango de caracteres con un guión (por ejemplo, [^a-f] es equivalente a [^abcdef]).		#
#  \b   	#	Límite de palabra (como un espacio o un retorno de carro).																								#
#  \B 		#	Cualquiera que no sea un límite de palabra.																												#
#  \d 		#	Cualquier carácter de dígito. Equivalente a [0-9].																										#
#  \D 		#	Cualquier carácter que no sea de dígito. Equivalente a [^0-9].																							#
#  \f 		#	Salto de página.																																		#
#  \n 		#	Salto de línea.																																			#
#  \r 		#	Retorno de carro.																																		#
#  \s 		#	Cualquier carácter individual de espacio en blanco (espacios, tabulaciones, saltos de página o saltos de línea).										#
#  \S 		#	Cualquier carácter individual que no sea un espacio en blanco.																							#
#  \t 		#	Tabulación.																																				#
#  \w 		#	Cualquier carácter alfanumérico, incluido el de subrayado. Equivalente a [A-Za-z0-9_].																	#
#  \w 		#	Cualquier carácter que no sea alfanumérico. Equivalente a [^A-Za-z0-9_].																				#
#########################################################################################################################################################################

*/