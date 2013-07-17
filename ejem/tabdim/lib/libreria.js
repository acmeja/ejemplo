/*
* Nombre: envia
* Función del módulo: envía el formulario
* Parámetros: recibe como entrada el formulario a enviar
* Fecha: 24/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function envia(form){
	form.submit();
}

/*
* Nombre: enviaAdd
* Función del módulo: envía el formulario para agregar nuevo registro
* Parámetros: recibe como entrada el formulario a enviar y el numero de columnas de la tabla
* Fecha: 24/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function enviaAdd(form,numCol,nomTabla){
	form.nameTable.value = nomTabla;
	form.colsx.value = numCol;
	return;
}

/*
*Nombre: validacion
*Función del módulo: Valida expresiones regulares en formato javaScript
*Parámetros: de entrada la expresión regular y el texto a validar
*Fecha: 29/04/2013
*Realizó: Jesus Abel Vera Cruz
*/
function validacion(expReg,textoValidar) { 
   var texto;
   var cadena="";
   texto=textoValidar.value; //Obtiene el valor de la caja de texto
   var expresion=new RegExp(expReg);//definimos la variable expresión del tipo RegExp 
   cadena=texto.match(expresion); //valida el texto de la caja de texto con la expresión regular
   if (cadena==null)  //si la vairable contiene nulo entonces no cumplió con la expresión regular
   {
	alert("Formato no válido");
	textoValidar.value="";
	return;

   }
}

/*
* Nombre: elimina
* Función del módulo: coloca los valores necesarios al formulario para poder realizar la actividad en cuestion
* Parámetros: recibe como entrada el formulario a enviar, el valor del registro,el identificador de la tabla y el nombre de la columna de donde se realizará la actividad
* Fecha: 29/04/2013
* Realizó: Juan Carlos Piña Moreno
*/
function elimina(form,valor,tabla,nomCol){
	if(confirm("¿Seguro de eliminar el registro?")){
		form.columna0.value = valor;
		form.idTabla.value = tabla;
		form.nomColumna.value = nomCol;
		form.submit();
	}else{ return; }
}

/*
* Nombre: edita
* Función del módulo: coloca los valores necesarios al formulario para poder realizar la actividad en cuestion
* Parámetros: recibe como entrada el formulario a enviar, el valor del registro,el identificador de la tabla y el nombre de la columna de donde se realizará la actividad
* Fecha: 29/04/2013
* Realizó: Juan Carlos Piña Moreno
*/
function edita(form,valor,tabla,nomCol){
	if(confirm("¿Seguro de editar el registro?")){
		form.columna0.value = valor;
		form.idTabla.value = tabla;
		form.nomColumna.value = nomCol;
		form.submit();
	}else{ return; }
}

