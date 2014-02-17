<?php
    
//Funcion que devuelve el procentaje de aprecido entre dos strings 
function similarTxt($c1,$c2)
{
	similar_text($c1, $c2,$percent);
	
	return $percent;
}


//Funcion que devuelve la distancia de levenshtein entre dos cadenas soundex
function soundLev($c1,$c2)
{
	return levenshtein(soundex($c1),soundex($c2));
}

//Funcion que devuelve el porcentaje de similitud entre dos cadenas soundex
function soundSim($c1,$c2)
{
	
	similar_text(soundex($c1),soundex($c2),$percent);
	
	return $percent;
	
}

//Funcion que devuelve la distancia de levenshtein entre dos cadenas metaphone
function metaLev($c1,$c2)
{
	return levenshtein(metaphone($c1),metaphone($c2));
}

//Funcion que devuelve el porcentaje de similitud entre dos cadenas metaphone
function metaSim($c1,$c2)
{
	similar_text(metaphone($c1),metaphone($c2),$percent);
	
	return $percent;
}
    

/*Funcion que calcula la distancia invariante transposicional a partir
 *  de los vectores de frecuencias de dos cadenas
 *  v: vector de frecuencias
 *  w: vector de frecuencias
 */

 function distanceDIT($v,$w)
 {
 	
	 $dit = 0;
	 $lv = 0;
	 $lw = 0;
  
  	for($i = 0; $i < count($v); $i++)
  	{
    	$lv += $v[$i];
   	 	$lw += $w[$i];
    	$dit += abs($v[$i] - $w[$i]);  
  	}
  	$dit += abs($lv - $lw);
  
  	return ($dit / 2);
	
 }
    
/*******************************************************************
 * Funciones auxiliares
 ******************************************************************/
 
 
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