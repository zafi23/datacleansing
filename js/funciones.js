
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
	
	var VFifhero = estaVacio(fichero);
	var Vemail = estaVacio(email);
	var Vposicion = estaVacio(posicion);
	
	if(!VFifhero && !Vemail && !Vposicion )
	{
		valido = true;
	}
	
	if((cbSiglas && estaVacio(siglas)) || (cbAlpha && estaVacio(alpha)))
	{
		valido = false;
	}

	return valido;
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
	  
	xmlhttp.onreadystatechange=function(){}
	
	xmlhttp.open("POST","CalculaTablas.php",true);
	//xmlhttp.setRequestHeader("Content-Type", "multipart/form-data");
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
	}

 }

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

