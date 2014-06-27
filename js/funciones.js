
var ArrayMod = [];


/************************************************************************************************
 * Funciones para validar formularios
 ************************************************************************************************/

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

	var VFichero = estaVacio(fichero);
	var Vemail = validarEmail(email);
	var Vposicion = validarPosicion(posicion);
	
	
	
	if(!VFichero)
	{
		valido = true;
		document.getElementById("errorFichero").innerHTML = "";
	}
	else
	{
		document.getElementById("errorFichero").innerHTML = "El fichero no puede estar vacio";
	}
	
	if( Vemail && Vposicion )
	{
		valido = true;
	}
	
	
	if((cbSiglas && estaVacio(siglas)) || (cbAlpha && !validarAlpha(alpha)))
	{
		valido = false;
	}

	return valido;
}

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

function validarPosicion(p)
{
	var valido = false;

	
	if(!esEntero(p))
	{
		//Poner en el campo de error que tiene que ser un numero
		document.getElementById("errorPosicion").innerHTML = "Tiene que ser un numero entero";
	}
	else 
	{
		valido = true;
		document.getElementById("errorPosicion").innerHTML = "";
	}
	
	return valido;
}

function validarAlpha(a)
{
	var valido = false;
	var num =parseFloat(a); 
	
	if(isNaN(num) || (num >1 || num<0))
	{
		//Poner en el campo de error que tiene que ser un numero
		document.getElementById("errorAlpha").innerHTML = "Tiene que ser un numero decimal entre 0 y 1";

	}
	else
	{
		valido = true;
		document.getElementById("errorAlpha").innerHTML = "";
	}
	
	return valido;
}

/************************************************************************************************
 * Funciones para mostrar y modificar datos
 ************************************************************************************************/

function mostrarDatos(f)
{
	
	document.getElementById("objMod").style.display="inline";
	document.getElementById("errorCadMod").innerHTML = "";
	
	var cadena = document.getElementById("cadMod");
	var c = f.text.split(";");
	var pos = document.getElementById("posicion").value;
	
	document.getElementById("qCad").checked = false;
	cadena.value = c[pos];
	
	
	
}

function guardarMods()
{
	var cadena = document.getElementById("cadMod").value;
	
	if(estaVacio(cadena))
	{
		document.getElementById("errorCadMod").innerHTML = "La cadena no puede estar vacia";
	}
	else
	{
		document.getElementById("errorCadMod").innerHTML = "";
		var cCads = document.getElementById("cCads");
		var cluster = document.getElementById("cluster").value;
		var indice = cCads.value;
		var operacion  = -1;
		var posicion =  document.getElementById("posicion").value;
		
		if(document.getElementById("qCad").checked)
		{
			operacion = 0;
		}
		
		var obj = new ObjModificado(cluster,indice,cadena,operacion);
		
		ArrayMod.push(obj);
		
		var cCsv = cCads.options[cCads.selectedIndex].text;
		var csv = cCsv.split(";");
		csv[posicion] = cadena;
		
		document.getElementById("cCads").options[document.getElementById("cCads").selectedIndex].text = csv.join(";");
		
	}
	
}



/************************************************************************************************
 * Funciones para enviar datos a php
 ************************************************************************************************/

function calcularTablas()
{
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
	
	xmlhttp.open("POST","CalculaTablas.php",true);
	xmlhttp.send(formData);
	
}

function mostrarCadenas(num)
{
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

	xmlhttp.open("POST","datosCluster.php",true);
	xmlhttp.send(formData);
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    	var resultado = JSON.parse(xmlhttp.responseText);
	    	var centro = resultado[0];
	    	var cads = resultado[1];
	    	
	    	
	    	var select = document.getElementById("cCads");
	    	select.innerHTML = "";
	    	
	    	for(i in cads)
	    	{
	    		var option = document.createElement("option");
				option.text = cads[i][0];
				option.value = cads[i][1];
				option.addEventListener("click", function(){
    					mostrarDatos(this);});
				select.add(option);
	    	}
	    	
	    	document.getElementById("centroC").value = centro;
	    	document.getElementById("cluster").value = num;
	    	document.getElementById("objMod").style.display="none";

	    }
	};
	
}

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

	};
	
	xmlhttp.open("POST","GuardarFichero.php",true);
	xmlhttp.send(formData);
	
}

/************************************************************************************************
 * Funciones para controlar checks
 ************************************************************************************************/


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


function estaVacio(n)
{
	var vacio = false;
	
	if(n == "" || n == null || n.indexOf(" ")==0 || n.indexOf("\t")==0)
		vacio = true;
	
	return vacio;
}

function esEntero(x)
{
	var y = parseInt(x);
	if (isNaN(y)) 
		return false;
	return x == y && x.toString() == y.toString();
}


//Funcion que devuelve el valor del parametro name en la url
function gup( name ){
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp ( regexS );
	var tmpURL = window.location.href;
	var results = regex.exec( tmpURL );
	if( results == null )
		return"";
	else
		return results[1];
}
