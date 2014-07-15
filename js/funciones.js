
//Array de modificaciones guardadas
var ArrayMod = [];


/************************************************************************************************
 * Funciones para validar formularios
 ************************************************************************************************/

/*Funcion que valida los campos del form del index
 * 
 * @param f referencia al objeto del formulario
 * @return boolean devuelve si es valido o no el formulario 
 */
function validar(f)
{
	var valido = false;
	
	var fichero = f.tFichero.value;
	var email = f.tEmail.value;
	var posicion = f.tPosicion.value;
	var siglas = f.tSiglas.value;
	var alpha = f.tAlpha.value;
	var cbSiglas = f.cbSiglas.checked;
	var cbAlpha = f.cbAlpha.checked;

	var VFichero = validarFichero(fichero);
	var Vemail = validarEmail(email);
	var Vposicion = validarPosicion(posicion);
	
	var VAbrev = validarAbreviaturas(siglas);
	var Valpha = validarAlpha(alpha);
	
	
	if(VFichero && Vemail && Vposicion)
	{
		valido = true;
	}

	
	if(cbSiglas && !validarAbreviaturas(siglas))
	{
		valido = false;
	}
	
	if(cbAlpha && !validarAlpha(alpha))
	{
		valido = false;
	}

	return valido;
}

/*Funcion que valida el campo del email
 * 
 * @param string em email a validar
 * @return boolean devuelve si es valido o no el email
 */
function validarEmail(em)
{
	var expr = new RegExp("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*(\.[a-zA-Z]{2,3})");

	var valido = false;
	
	
	if(expr.test(em))
	{
		valido = true;
		document.getElementById("errorEmail").innerHTML ="";
	}
	else
	{
		document.getElementById("errorEmail").innerHTML ="El email es incorrecto";
		
	}
	
	return valido;
	
}

/*Funcion que valida el campo de posicion
 * 
 * @param string p posicion a validar
 * @return boolean devuelve si es valido o no la posicion
 */
function validarPosicion(p)
{
	var valido = false;

	
	if(!esEntero(p))
	{
		document.getElementById("errorPosicion").innerHTML = "Tiene que ser un numero entero";
	}
	else 
	{
		valido = true;
		document.getElementById("errorPosicion").innerHTML = "";
	}
	
	return valido;
}

/*Funcion que valida el campo de alpha
 * 
 * @param string a va.lor alpha a validar
 * @return boolean devuelve si es valido o no el valor alpha
 */
function validarAlpha(a)
{
	var valido = false;
	var num =parseFloat(a); 
	
	if(isNaN(num) || (num >1 || num<0))
	{
		document.getElementById("errorAlpha").innerHTML = "Tiene que ser un numero decimal entre 0 y 1";

	}
	else
	{
		valido = true;
		document.getElementById("errorAlpha").innerHTML = "";
	}
	
	return valido;
}

/*Funcion que valida el campo de abreviaturas
 * 
 * @param string a campo a validar
 * @return boolean devuelve si es valido o no el campo de abreviaturas
 */
function validarAbreviaturas(a)
{
	var valido = false;
	
	if(estaVacio(a))
	{
		document.getElementById("errorSiglas").innerHTML = "Las abreviaturas no pueden estar vacias";
	}
	else 
	{
		valido = true;
		document.getElementById("errorSiglas").innerHTML = "";
	}
	
	return valido;
}

/*Funcion que valida el campo de fichero
 * 
 * @param string a campo a validar
 * @return boolean devuelve si es valido o no el campo de fichero
 */
function validarFichero(a)
{
	var valido = false;
	
	if(estaVacio(a))
	{
		document.getElementById("errorFichero").innerHTML = "El fichero no puede estar vacio";
	}
	else 
	{
		valido = true;
		document.getElementById("errorFichero").innerHTML = "";
	}
	
	return valido;
}

/************************************************************************************************
 * Funciones para mostrar y modificar datos
 ************************************************************************************************/

/*Funcion que muestra los datos de una cadena en el div de modificaciones
 * 
 * @param f referencia a la cadena del select
 */
function mostrarDatos(f)
{

	document.getElementById("objMod").style.display="inline";
	document.getElementById("errorCadMod").innerHTML = "";
	
	var cadena = document.getElementById("cadMod");
	var c = f.text.split(";");
	var pos = document.getElementById("posicion").value;
	var cCads = document.getElementById("cCads");
	var cluster = document.getElementById("cluster").value;
	var indice = cCads.value;

	
	var i = indiceMod(c[pos],cluster,indice);

	if(i!=-1)
	{
		switch(ArrayMod[i].operacion)
		{
			case 0:
				document.getElementById("qCad").checked = true;
				document.getElementById("bCad").checked = false;
				break;
			case 1:
				document.getElementById("qCad").checked = false;
				document.getElementById("bCad").checked = true;
				break;
			default:
				document.getElementById("qCad").checked = false;
				document.getElementById("bCad").checked = false;	
		}
		
	}
	else
	{
		document.getElementById("qCad").checked = false;
	}
	
	cadena.value = c[pos];
	
	
	
}

/*Funcion que guarda las modificaciones en el array de modificaciones
 * 
 */
function guardarMods()
{
	//Se obtiene la informacion a guardar
	var cadena = document.getElementById("cadMod").value;
	var cCads = document.getElementById("cCads");
	var cluster = document.getElementById("cluster").value;
	var indice = cCads.value;
	var i = indiceMod(cluster,indice);
	
	var operacion  = -1;
	
	if(document.getElementById("qCad").checked)
	{
		operacion = 0;
	}
	
	if(document.getElementById("bCad").checked)
	{
		operacion = 1;
	}
	
	//Si la cadena esta vacia saldra un error
	if(estaVacio(cadena))
	{
		document.getElementById("errorCadMod").innerHTML = "La cadena no puede estar vacia";
	}
	else if(i==-1)
	{
		//Si no existe una modificacion previa de la cadena se guardara la modificacion
		document.getElementById("errorCadMod").innerHTML = "";
		var operacion  = -1;
		var posicion =  document.getElementById("posicion").value;
		
		if(document.getElementById("qCad").checked)
		{
			operacion = 0;
		}
		
		if(document.getElementById("bCad").checked)
		{
			operacion = 1;
		}
		
		var obj = new ObjModificado(cluster,indice,cadena,operacion);
		
		ArrayMod.push(obj);
		
		var cCsv = cCads.options[cCads.selectedIndex].text;
		var csv = cCsv.split(";");
		csv[posicion] = cadena;
		
		document.getElementById("cCads").options[document.getElementById("cCads").selectedIndex].text = csv.join(";");
		
	}
	else
	{
		//Si existiese una modificacion de esa cadena, semodificacian solo los dacots de la cadena y su oepracion a la mas reciente
		ArrayMod[i].cadena = cadena;
		ArrayMod[i].operacion = operacion;
	}
	
}

/*Funcion que guarda las modificaciones de la cadena centro
 * 
 */
function guardarModCentro()
{
	var cadena = document.getElementById("centroC").value;
	var cluster = document.getElementById("cluster").value;
	var indice = document.getElementById("indiceC").value;;
	var i =indiceMod(cluster,indice);
	
	//Si la cadena esta vacia se generara un error
	if(estaVacio(cadena))
	{
		document.getElementById("errorCentroC").innerHTML = "La cadena no puede estar vacia";
	}
	else if(i==-1)
	{
		//En caso de que no exista ninguna modificacion sobre esta cadena se crearauna modificacion nueva
		document.getElementById("errorCentroC").innerHTML = "";
		var operacion  = -1;
		
		var obj = new ObjModificado(cluster,indice,cadena,operacion);
		
		ArrayMod.push(obj);
		var cCads = document.getElementById("cCads");
		var posicion =  document.getElementById("posicion").value;
	
		for(var i = 0; i < cCads.length; i++)
		{
		      if(cCads.options[i].value == indice)
		      {
		      	var cCsv = cCads.options[i].text;
				var csv = cCsv.split(";");
				csv[posicion] = cadena;
				
				cCads.options[i].text = csv.join(";");
				break;
		      }
		}	
	}
	else
	{
		//Si existe unamodificacion de esta cadena se modifica solo la cadena de texto
		ArrayMod[i].cadena = cadena;
	}
}



/************************************************************************************************
 * Funciones para enviar datos a php
 ************************************************************************************************/

/*Funcion que envia la informacion del form del index para poder calcular las tablas de distancias
 * 
 */
function calcularTablas()
{
	//Se obtienen todos los datos a enviar
	var xmlhttp;
	var cad = "";
	var file = document.getElementById("tFichero");
	var siglas = document.getElementById("tSiglas");
	
	var formData = new FormData();
      /* Add the file */ 
    formData.append("fichero", file.files[0]);
	formData.append("email", document.getElementById("tEmail").value);
	formData.append("posicion",document.getElementById("tPosicion").value);

	if(document.getElementById("cbSiglas").checked)
	{
		formData.append("abreviaturas",siglas.files[0]);
	}
	
	if(document.getElementById("cbAlpha").checked)
	{
		formData.append("alpha",document.getElementById("tAlpha").value);
	}
	

	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  
	xmlhttp.onreadystatechange=function(){};
	
	//Se hace visible el comentario de que se han enviado los datos al servidor
	document.getElementById("msgEnviado").style.display="inline";
	document.getElementById("formFichero").style.display="none";
	
	//Se envia la informacion por Ajax
	xmlhttp.open("POST","CalculaTablas.php",true);
	xmlhttp.send(formData);
	
	
}

/*Funcion que envia y muestra lso datos de las cadenas de un emparejamiento
 * El envio de datos y su posterior recepcion se hacen por Ajax
 * 
 * @param int num numero de emparejamiento
 */
function mostrarCadenas(num)
{
	//Se recogen los datos a enviar al servidor
	var formData = new FormData();
	formData.append("cluster", num);
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }

	//Se envian los datos por Ajax
	xmlhttp.open("POST","datosCluster.php",true);
	xmlhttp.send(formData);
	
	//Se recogen los datos de Ajax para poder escribir la informacion de las cadenas
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    	var resultado = JSON.parse(xmlhttp.responseText);
	    	var centro = resultado[0];
	    	var indiceC = resultado[1];
	    	var iCentro = indiceMod(num,indiceC);
	    	if(iCentro!=-1)
	    	{
	    		centro = ArrayMod[iCentro].cadena;
	    	}
	    	
	    	
	    	var cads = resultado[2];
	    	var posicion =  document.getElementById("posicion").value;
	    	
	    	var select = document.getElementById("cCads");
	    	select.innerHTML = "";
	    	
	    	for(i in cads)
	    	{
	    		var option = document.createElement("option");
	    		
	    		var iCad = indiceMod(num,cads[i][1]);
	    		
	    		if(iCad!=-1)
		    	{
		    		var cCsv = cads[i][0];
					var csv = cCsv.split(";");
					csv[posicion] = ArrayMod[iCad].cadena;
					
					option.text = csv.join(";");

		    	}
	    		else
	    		{
	    			option.text = cads[i][0];
	    		}
				
				option.value = cads[i][1];
				option.addEventListener("click", function(){
    					mostrarDatos(this);});
				select.add(option);
	    	}
	    	
	    	document.getElementById("centroC").value = centro;
	    	document.getElementById("indiceC").value = indiceC;
	    	document.getElementById("cluster").value = num;
	    	document.getElementById("objMod").style.display="none";

	    }
	};
	
}

/*Funcion que envia la peticion por Ajax para que se guarde el fichero
 * 
 */
function guardarFichero()
{
	var xmlhttp;
		
	var formData = new FormData();
      
    var radios = document.getElementsByName('tipoGuardado');
	var tipo = "";
	for (var i = 0, length = radios.length; i < length; i++) {
	    if (radios[i].checked) {
	        
	       	tipo = radios[i].value;
	        break;
	    }
	}
      
    var modificaciones =  JSON.stringify(ArrayMod); 
      
    formData.append("modificaciones", modificaciones);
	formData.append("tipo", tipo);

	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    	document.getElementById("msgEnviado").style.display="inline";
	    	document.getElementById("tablasDatos").style.display="none";
	    }
	};
	
	xmlhttp.open("POST","GuardarFichero.php",true);
	xmlhttp.send(formData);
	
}

/************************************************************************************************
 * Funciones para controlar checks
 ************************************************************************************************/

/*Funcion que controla el estado del check de abreviaturas
 * 
 * @param f referencia al objeto check
 */
function checkAbreviaturas(f)
{
	
	if(f.checked)
	{
		document.getElementById("divSiglas").style.display="inline";	
	}
	else
	{
		document.getElementById("divSiglas").style.display="none";
		document.getElementById("errorSiglas").innerHTML = "";
	}

}

/*Funcion que controla el estado del check del campo alpha
 * 
 * @param f referencia al objeto check
 */
function checkAlpha(f)
 {
 	if(f.checked)
	{
		document.getElementById("divAlpha").style.display="inline";	
	}
	else
	{
		document.getElementById("divAlpha").style.display="none";
		document.getElementById("errorAlpha").innerHTML = "";
	}

 }

/************************************************************************************************
 * Clase de objetos a modificar
 ************************************************************************************************/

/*
 * Clase que guarda la informacion de las modificaciones
 */
var ObjModificado = function(cluster,indice,cadena,operacion)
{
	this.cluster = cluster;
	this.indice = indice;
	this.cadena = cadena;
	this.operacion = operacion;
};


/************************************************************************************************
 * Funciones auxiliares
 ************************************************************************************************/

/*Funcion que determina si una cadena esta vacia o no
 * 
 * @param string n cadena a evaluar
 * @return boolean devuelve true si la cadena esta vacia
 */
function estaVacio(n)
{
	var vacio = false;
	
	if(n == "" || n == null || n.indexOf(" ")==0 || n.indexOf("\t")==0)
		vacio = true;
	
	return vacio;
}

/*Funcion que determina si un numero es entero o no
 * 
 * @param string x cadena a evaluar
 * @return boolean devuelve true si la cadena esun numero entero
 */
function esEntero(x)
{
	var y = parseInt(x);
	if (isNaN(y)) 
		return false;
	return x == y && x.toString() == y.toString();
}

/*Funcion que devuelve un indice del array de modificaciones
 * 
 * @param int cluster emparejamiento donde se encuentra la cadena
 * @param int indice indice de la cadena
 * @return int devuelve -1 si no esta en las modificaciones y su indice en caso contrario
 */
function indiceMod(cluster,indice)
{
	ind = -1;
	
	for(var i = 0; i < ArrayMod.length ; i++)
	{
		if(ArrayMod[i].indice == indice && ArrayMod[i].cluster == cluster )
		{
			ind = i;
			break;
		}
	}
	
	return ind;
}

