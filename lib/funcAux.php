<?php

//Funcion que preprocesa las cadenas
function preprocess($cadenasOri)
{
	
	$cadenas = array();
	
	for ($i=0; $i < count($cadenasOri) ; $i++) 
	{
		//Primero le quitamos los caracteres que no son alfanumericos 
		$cad = preg_replace("/[^A-Za-z0-9\s\s+]/","",$cadenasOri[$i]);
		
		//Pasamos todas las letras a minuscula
		$cad = strtolower($cad);
		
		array_push($cadenas,$cad);
	}
	
	return $cadenas;
	
}

//Obtenemos las cadenas de un fichero de texto
function leerFich($fichero)
{
	$cads = array();
	
	$gestor = fopen($fichero, "r");
	
	
	
	if($gestor)
	{
		while (($bufer = fgets($gestor, 4096)) !== false) 
		{
        	array_push($cads,$bufer);
	    }
	    if (!feof($gestor)) 
	    {
	        echo "Error: fallo inesperado de fgets()\n";
	    }
	    fclose($gestor);
		

	}
	

	
	return $cads;
}




?>