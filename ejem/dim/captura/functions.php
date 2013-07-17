<?php
//Documento donde se almacenan las funciones creadas.
require_once("../conexion.php");

//Variables para el paso de mensajes de resultado
$mensajeCorrecto ="";
$mensajeError = "";

/*
* Nombre: obtieneTablaMaestra
* Función del módulo: obtiene el identificador y nombre de la tablas creadas, regresa un array con el id y el nombre de las tablas
* Fecha: 24/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function obtieneTablaMaestra(){
	conectar();
	$resultados = array();
	$count = 0;
	$query='SELECT cvemaestr,nomaestr from tblmaestr'; 
    $result=pg_query("$query");
	while($fila = pg_fetch_array($result)){
		$resultados[$count] = array($fila['cvemaestr'],$fila['nomaestr']);
		$count++;
	}
	desconectar();
	return $resultados;
}

/*
* Nombre: generaListaTablas
* Función del módulo: genera una lista con el nombre de las tablas creadas
* Recibe como parámetro el "action" del formulario a crear 
* Fecha: 26/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function generaListaTablas($action){
	$arrayTablas = obtieneTablaMaestra();//se obtienen las tablas creadas id,nombre
	$form = "";
	$form = $form.'<form name="formTablas" method="post" action="'.$action.'">
  					<select name="tabla" id="tabla"  onchange="javascript:envia(document.formTablas);">
  					<option ></option>';
  	foreach($arrayTablas as $tabla){
		$form = $form."<option value='".$tabla[0]."'>".$tabla[1]."</option>";
  	}
  	$form = $form.'</select></form>';
  	return $form;
}

/*
* Nombre: obtieneCampos
* Función del módulo: obtiene los campos pertenecientes a la tabla seleccionada
* Parámetros: recibe como entrada el id de la tabla a manejar, regresa el formulario creado para la tabla
* Fecha: 24/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function obtieneCampos($id){
	conectar();
	$count = 0;
	$arrayResult = array();
	//nombre de la tabla seleccionada
	$nomTabla ="";
	//query correspondiente
	$query = "select a.loncolumn,a.nulcolumn,a.nomcolumn,b.destpodts,a.leycolumn,a.comcolumn,a.valcolumn,c.nomaestr from tblcolumn a,tbltpodts b,tblmaestr c where a.cvetpodts = b.cvetpodts and a.cvemaestr = ".$id." and a.cvemaestr = c.cvemaestr";
	$result = pg_query($query);
	$formulario = "<form action='pruebaCaptura.php' name='formAdd' method='post' >";
	while($columna = pg_fetch_array($result)){
		if(strcmp (trim($columna['destpodts']),"boolean") != 0){//compara si es un booleano
			$formulario = $formulario.crearCampoValidacion($columna['destpodts'],$count,$columna['leycolumn'],$columna['nulcolumn'],$columna['loncolumn'],$columna['valcolumn'],'');
		}else{
			$formulario = $formulario."<label>".$columna['leycolumn']."<select name='columna".$count."'>
				
				<option value='0'>FALSE</option>
				<option value='1'>TRUE</option>
			</select></label>";
		}
		$count ++;
		$nomTabla = $columna['nomaestr'];
	}
	$arrayResult[1] = $count;//numero de campos del formulario
	$arrayResult[2] = $nomTabla;//nombre de la tabla a utilizar
	$formulario = $formulario."<input type='hidden' name='agregar' value='si'/><input type='hidden' name='colsx'/><input type='hidden' name='nameTable'/><input type = 'submit' value='Guardar' onclick='javascript:enviaAdd(document.formAdd,$count,".'"'.$nomTabla.'"'.");' id='botonGenerado'/></form>";
	$arrayResult[0] = $formulario;//formulario
	desconectar();
	return $arrayResult;
}

/*
* Nombre: newRows
* Función del módulo: inserta un nuevo registro en la tabla que se esta manejando
* Parámetros: recibe como entrada el nombre de la tabla, numero de campos de la tabla, regresa mensaje de exito o error segun sea el caso
* Fecha: 24/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function newRow($table,$numCol){
	conectar();
	$campos = array();
	//Se obtiene los valores del registro a agregar
	for($i = 0; $i < $numCol; $i++){
		$campos[$i] = $_POST["columna".$i];
	}
	//se crea el query correspondiente
	$query = "insert into $table values(";
	for($i = 0; $i < $numCol;$i++){
		$query = $query."'$campos[$i]'";
		if($i != ($numCol-1)){
			$query = $query.",";
		}
	}
	$query = $query.")";
	if(pg_query($query)){
		desconectar();
		return "Registro agregado correctamente.";
	}else{
		desconectar();
		return pg_last_error();
	}
}

/*
* Nombre: CrearCampoValidacion
* Función del módulo: crea un campo dentro del formulario con su validación correspondiente
* Parámetros: recibe como entrada el tipo de dato en cuestion, el número de campo, el nombre del campo, puede ser nulo o no y el correspondiente valor.
* Fecha: 25/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function crearCampoValidacion($validacion,$contador,$nombreCol,$nulos,$valor,$expresion,$contenido)
{   
  //Ejecuta la expresión de validación del campo "valcolumn" 
  $cadenaExpresion="";
  $cadenaPatron="";
  $validar="";
  $cadenaExp=str_replace(' ','',$expresion); //Aqui se reemplaza los espacios que originalmente vienen en $expresion y se dejan los caracteres que no son espacios (la expresión regular)
  if(strlen($cadenaExp)!=0)
  {    
      $validar='onchange="validacion('."'".addslashes (trim($cadenaExp,"/"))."'".',this)"';
  }
  
  //Valida la longitud del campo "caracter"
  $lon=$valor+0; //Convierte la longitud de cadena que se obtuvo de la base de datos a entero
  $longitudCadena="";
  $textoErrorLongCadena="";
  if($lon!=0)//comprueba que la variable $lon sea diferente de cero, si es asi entonces se establecío una longitud de cadena
  {//En caso verdadero estas cadenas se le concatenarán a la caja de texto para que se valide la longitud de una cadena o se imprima un mensaje de error al superar el máximo de caracteres permitidas
      $longitudCadena="minChars:0, maxChars:$lon"; 
      $textoErrorLongCadena='<span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span><span class="textfieldMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span>';
  }
 
 //Valida la opcion de nulos en los campos
  $cadenaNulo=", isRequired:false";
  $cadenaValor='<span class="textfieldRequiredMsg">Se necesita un valor.</span>';
  if($nulos==0){
  $cadenaNulo="";
  }  
  $caja = "";
  
 
  if(strcmp(trim($validacion),"char")==0)
  {
	       
               $caja = $caja.'<span id="sprytextfield'.$contador.'">
               <label for="columna'.$contador.'">'.$nombreCol.'</label>
               <input type="text" name="columna'.$contador.'" id="columna'.$contador.'" '.$validar.' value='."'".$contenido."'".'  />'.$cadenaValor.'
               <span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.
	       </span><span class="textfieldMaxCharsMsg">Se ha superado el número máximo de caracteres.</span></span>
               <script type="text/javascript">
               var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "none", {validateOn:["blur", "change"]'.$cadenaNulo.', minChars:1,      maxChars:1});
                </script>';
	
  }else if(strcmp(trim($validacion),"character")==0)
  {
	      $hola="ggg";
              $caja = $caja.'<span id="sprytextfield'.$contador.'">
              <label for="columna'.$contador.'">'.$nombreCol.'</label>
              <input type="text" name="columna'.$contador.'" id="columna'.$contador.'" '.$validar.' value='."'".$contenido."'".'/>
              '.$cadenaValor.$textoErrorLongCadena.$textoErrorExpresion.'</span>
              <script type="text/javascript">
              var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "none", {'.$longitudCadena.',validateOn:["blur","change"]'.$cadenaNulo.$cadenaPatron.'});
              </script>';
  }else if(strcmp(trim($validacion),"date")==0)
  {
               $caja = $caja.'<span id="sprytextfield'.$contador.'">
               <label for="columna'.$contador.'">'.$nombreCol.'</label>
               <input type="text" name="columna'.$contador.'" id="columna'.$contador.'" '.$validar.' value='."'".$contenido."'".' />
               '.$cadenaValor.'</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span>
               <script type="text/javascript">
               var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "date", {validateOn:["blur", "change"]'.$cadenaNulo.', format:"yyyy-mm-dd",              hint:"aaaa-mm-dd"});
               </script>';
  }else if(strcmp(trim($validacion),"decimal")==0)
  {
               $caja = $caja.'<span id="sprytextfield'.$contador.'">
               <label for="columna'.$contador.'">'.$nombreCol.'</label>
               <input type="text" name="columna'.$contador.'" id="columna'.$contador.'" '.$validar.' value='."'".$contenido."'".' />
               '.$cadenaValor.'<span class="textfieldInvalidFormatMsg">Formato no válido.</span></span>
               <script type="text/javascript">
               var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "real", {validateOn:["blur", "change"]'.$cadenaNulo.'});
               </script>';
  }else if(strcmp(trim($validacion),"double precision")==0)
  {
               $caja = $caja.'<span id="sprytextfield'.$contador.'">
                <label for="columna'.$contador.'">'.$nombreCol.'</label>
                <input type="text" name="columna'.$contador.'" id="columna'.$contador.'" value="'.$valor.'" '.$validar.' value='."'".$contenido."'".' />
                '.$cadenaValor.'<span class="textfieldInvalidFormatMsg">Formato no válido.</span></span>
                 <script type="text/javascript">
                  var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "real", {validateOn:["blur", "change"]'.$cadenaNulo.'});
                   </script>';
  }else if(strcmp(trim($validacion),"integer")==0)
   {
               $caja = $caja.'<span id="sprytextfield'.$contador.'">
               <label for="columna'.$contador.'">'.$nombreCol.'</label>
               <input type="text" name="columna'.$contador.'" id="columna'.$contador.'" '.$validar.' value='."'".$contenido."'".' />
               '.$cadenaValor.'<span class="textfieldInvalidFormatMsg">Formato no válido.</span></span>
               <script type="text/javascript">
               var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "integer", {validateOn:["blur", "change"]'.$cadenaNulo.'});
               </script>';
   }
   else if(strcmp(trim($validacion),"real")==0)
   {
               $caja = $caja.'<span id="sprytextfield'.$contador.'">
               <label for="columna'.$contador.'">'.$nombreCol.'</label>
               <input type="text" name= "columna'.$contador.'" id="columna'.$contador.'" '.$validar.' value='."'".$contenido."'".'/>
               '.$cadenaValor.'<span class="textfieldInvalidFormatMsg">Formato no válido.</span></span>
               <script type="text/javascript">
               var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "real", {validateOn:["blur", "change"]'.$cadenaNulo.'});
               </script>';
   }else if(strcmp(trim($validacion),"smallint")==0)
   {
               $caja = $caja.'<span id="sprytextfield'.$contador.'">
               <label for="columna'.$contador.'">'.$nombreCol.'</label>
               <input type="text" name="columna'.$contador.'" id="columna'.$contador.'"  '.$validar.' value='."'".$contenido."'".'/>
               '.$cadenaValor.'<span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.        </span><span class="textfieldMaxCharsMsg">Se ha superado el número máximo de caracteres.</span><span class="textfieldInvalidFormatMsg">Formato no         válido.</span></span>
                <script type="text/javascript">
                var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "integer", {minChars:1, maxChars:5, minValue:-32768, maxValue:32767, validateOn:["blur","change"]'.$cadenaNulo.'});
                </script>';
   }else //El caso default cuando el tipo de dato es "time"
   {
               $caja = $caja.'<span id="sprytextfield'.$contador.'">
               <label for="columna'.$contador.'">'.$nombreCol.'</label>
               <input type="text" name="columna'.$contador.'" id="columna'.$contador.'" '.$validar.' value='."'".$contenido."'".'/>
               '.$cadenaValor.'<span class="textfieldInvalidFormatMsg">Formato no válido.</span></span>
               <script type="text/javascript">
               var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield'.$contador.'", "time", {validateOn:["blur", "change"]'.$cadenaNulo.', hint:"HH:mm"});
               </script>';                  
   }
   return $caja;	
}

/*
* Nombre: obtieneCamposEsp
* Función del módulo: obtiene los valores del campo y tabla especificada de la tabla tblcolumn
* Parámetros: recibe como entrada el identificador de la tabla a tratar y el campo de la tabla tblcolumn a recuperar
* Fecha: 26/04/2013
* Realizó: Juan Carlos Piña Moreno
*/
function obtieneCamposEsp($idTabla,$campoObtener){
	conectar();
	$campos = array();
	$query = "select ".$campoObtener." from tblcolumn where cvemaestr = ".$idTabla;
	$result = pg_query($query) or die ("Error: ".pg_last_error());
	$contador = 0;
	while($campo = pg_fetch_array($result)){
		$campos[$contador]= $campo[$campoObtener];
		$contador++;
  	}
	return $campos;
}

/*
* Nombre: obtieneRegistrosTabla
* Función del módulo: obtiene los registros existentes en una determinada tabla, regresa un array con los registros encontrados
* Parámetros: recibe como entrada el identificador de la tabla a consultar, el campo de referencia y el valor a buscar
* Fecha: 26/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function obtieneRegistrosTabla($idTabla,$camposTabla,$valorCampo){
	conectar();
	$nombreTabla ="";
	$contador = 0;
	$result = array();
	$registros = array();
	$query = 'select nomaestr from tblmaestr where cvemaestr = '.$idTabla;
	$result = pg_query($query) or die(pg_last_error());//obtiene el nombre de la tabla
	while($fila = pg_fetch_array($result)){
		$nombreTabla = $fila['nomaestr'];
	}
	$query = genQueryConsulta($nombreTabla,$camposTabla,$valorCampo);
	$result = pg_query($query)or die ("Error: ".pg_last_error());//realiza la consulta
	while($fila = pg_fetch_array($result)){
		$registros[$contador] = $fila;
		$contador++;
	}
	return $registros;
}

/*
* Nombre: genQueryConsulta
* Función del módulo: genera el query para realizar una consulta en base a un campo o a ninguno 
* Parámetros: recibe como entrada el nombre de la tabla a consultar, el campo de referencia y el valor a buscar
* Fecha: 30/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Able Vera Cruz
*/
function genQueryConsulta($nombreTabla,$camposTabla,$valorBuscar){
	$query = "select *";
	$query = $query." from ".$nombreTabla;
	if($valorBuscar != ""){
		$query = $query." where $camposTabla[0]::text like '%$valorBuscar%'";// con ::tex se convierte todos los campos a string para pode utilizar like
	}
	return $query;
}

/*
* Nombre: genTablaConsulta
* Función del módulo: regresa una tabla HTML con los registros encontrados de la tabla requerida
* Parámetros: recibe como entrada el identificador de la tabla consultada y el resultado de la consulta
* Fecha: 26/04/2013
* Realizó: Juan Carlos Piña Moreno
*/
function genTablaConsulta($idTabla,$registrosEncontrados){
	$registros = array();
	$tabla = '<div id="tablaConsulta"><table align="center" border="1" width="100%">
			  	<tr>';
	$leycampos = obtieneCamposEsp($idTabla,"leycolumn");//se obtiene la etiqueta de las columnas
	$campos = obtieneCamposEsp($idTabla,"nomcolumn");//se obtiene el nombre de las columnas
	$numCampos = count($leycampos);
	for($i = 0; $i < $numCampos; $i++){
		$tabla = $tabla.'<td>'.$leycampos[$i].'</td>';
	}
	$tabla = $tabla.'<td>Opciones</td></tr>';
	for($i = 0;$i < count($registrosEncontrados);$i++){
		$tabla = $tabla.'<tr>';
			for($j = 0; $j < $numCampos; $j++){
				$tabla = $tabla.'<td>'.$registrosEncontrados[$i][trim($campos[$j])].'</td>';
			}
			$val = trim($registrosEncontrados[$i][trim($campos[0])]);
		$tabla = $tabla."<td><input type='button' onclick='javascript:elimina(document.formDelete,".'"'.$val.'"'.",$idTabla,".'"'.$campos[0].'"'.")' value='Eliminar'/> <input type='button' onclick='javascript:edita(document.formEdit,".'"'.$val.'"'.",$idTabla,".'"'.$campos[0].'"'.")' value='Editar'/></td></tr>";
	}
	$tabla = $tabla.'</table>
	<form name=formDelete action="elimina.php" method="post">
		<input type="hidden" name="columna0"/>
		<input type="hidden" name="idTabla"/>
		<input type="hidden" name="nomColumna"/>
	</form>
	<form name=formEdit action="editar.php" method="post">
		<input type="hidden" name="columna0"/>
		<input type="hidden" name="idTabla"/>
		<input type="hidden" name="nomColumna"/>
	</form>
	</div>';
	return $tabla;
}

/*
* Nombre: genTablaedita
* Función del módulo: genera el formulario para la actualización del registro seleccionado
* Parámetros: recibe como entrada el identificador de la tabla a actualizar, el valor del campo clave y el nombre del campo clave
* Fecha: 29/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function genTablaEdita($idTabla,$valorEditar,$nomCol){
	conectar();
	$registros = array();
	$campos = obtieneCampos($idTabla);//genera formulario en blanco
	echo $campos[0];
	$contador = 0;
	$valorInicial = "";
	$query = 'select nomaestr from tblmaestr where cvemaestr = '.$idTabla;
	$result = pg_query($query) or die(pg_last_error());//obtiene el nombre de la tabla
	while($fila = pg_fetch_array($result)){
		$nombreTabla = $fila['nomaestr'];
	}
	$query = "select * from $nombreTabla where $nomCol = '$valorEditar'";
	$result = pg_query($query) or die(pg_last_error());
	while($fila = pg_fetch_array($result)){// se llenan los campos con los valores actuales
		for($i = 0; $i < $campos[1]; $i++){
			echo '<script type="text/javascript">
			var tipo = document.formAdd.columna'.$i.'.type;
			if(tipo == "select-one"){
				 if("'.$fila[$i].'" == "f"){
					 document.formAdd.columna'.$i.'.selectedIndex = 0;
				}else {
					document.formAdd.columna'.$i.'.selectedIndex = 1;
				}
			}else{
				document.formAdd.columna'.$i.'.value = '."'".trim($fila[$i])."';".'
			}</script>';
			$valorInicial = $fila[0]; 
		}
		$contador++;	
	}
	echo '<script type="text/javascript">document.formAdd.botonGenerado.onclick ="" </script>';
	echo '<script type="text/javascript">document.formAdd.nameTable.value = '."'".$idTabla."'".'</script>';
	echo '<script type="text/javascript">document.formAdd.action = '."'ejecutaEdit.php';".'</script>';
	echo '<script type="text/javascript">document.formAdd.colsx.value = '."'".$valorInicial."'".'</script>';
}

/*
* Nombre: delRow
* Función del módulo: elimina un registro de una tabla específica 
* Parámetros: recibe como entrada el identificador de la tabla, el nombre de la comuna clave y el valor de la columna del registro a eliminar
* Fecha: 29/04/2013
* Realizó: Juan Carlos Piña Moreno
*/
function delRow($idTabla,$nomColumna,$columna0){
	conectar();
	$query = 'select nomaestr from tblmaestr where cvemaestr = '.$idTabla;
	$result = pg_query($query) or die(pg_last_error());//obtiene el nombre de la tabla
	while($fila = pg_fetch_array($result)){
		$nombreTabla = $fila['nomaestr'];
	}
	$query = "delete from $nombreTabla where $nomColumna = '$columna0'";
	if(pg_query($query)){
		$GLOBALS['mensajeCorrecto']="Registro elimando correctamente";
		$GLOBALS['mensajeError']="";
	}else{
		$GLOBALS['mensajeCorrecto']="";
		$GLOBALS['mensajeError']=pg_last_error();
	}
}

/*
* Nombre: setRow
* Función del módulo: edita el registro seleccionado, los campos son obtenidos por POST
* Parámetros: recibe como entrada el identificador de la tabla a actualizar
* Fecha: 29/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function setRow($idTabla,$valorOriginal){
	conectar();
	$nombreCampos = obtieneCamposEsp($idTabla,"nomcolumn");//se obtiene el nombre de las columnas a actualizar
	$valorCampos = array();
	for($i = 0; $i < count($nombreCampos); $i++){
		$valorCampos[$i] = $_POST["columna".$i];
	}
	$query = 'select nomaestr from tblmaestr where cvemaestr = '.$idTabla;
	$result = pg_query($query) or die(pg_last_error());//obtiene el nombre de la tabla
	while($fila = pg_fetch_array($result)){
		$nombreTabla = $fila['nomaestr'];
	}
	$query="update $nombreTabla set ";
	for($i =0;$i < count($nombreCampos);$i++){
		$query = $query.$nombreCampos[$i]." = '".$valorCampos[$i]."'";
		if($i != (count($nombreCampos)-1)) $query = $query.", ";
	}
	$query = $query." where $nombreCampos[0] = '".$valorOriginal."'";
	pg_query($query);// se realiza la actualización de los campos en la base de datos
	$GLOBALS['mensajeCorrecto']="Registro actualizado correctamente.";
}

/*
* Nombre: generaComboFiltro
* Función del módulo: genera un formulario html con un combo que contiene el nombre de las columnas y una caja de texto para realizar el filtrdo
* Parámetros: recibe como entrada el identificador de la tabla de donde se buscará y la acción del formulario.
* Fecha: 30/04/2013
* Realizó: Juan Carlos Piña Moreno, Jesus Abel Vera Cruz
*/
function generaComboFiltro($idTabla,$action){
	$campos = obtieneCamposEsp($idTabla,"nomcolumn");//Se obtiene el nombre de los campos de la tabla seleccionada
	$leyendas = obtieneCamposEsp($idTabla,"leycolumn");//Se obtiene la leyenda de los campos de la tabla seleccionada
	$form = $form.'<form name="formFiltro" method="post" action="'.$action.'">
  					<select name="campo" id="campo">';//se arma el formulario
  	for($i = 0;$i < count($campos);$i++){
		$form = $form."<option value='".$campos[$i]."'>".$leyendas[$i]."</option>";
  	}
  	$form = $form.'</select> <input type="text" name="valorBuscar"/> <input type="hidden" name="idTabla" value="'.$idTabla.'"/><input type="submit" value="Filtrar"/></form>';
  	return $form;
}
?>

	


