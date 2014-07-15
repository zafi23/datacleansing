<?php
    
 /*******************************************************************
 * Funciones de calculo de distancias
 ******************************************************************/   
    
    
/* Funcion que devuelve el procentaje de aprecido entre dos strings 
 * 
 * @param string c1 cadena 1 a evaluar
 * @param string c2 cadena 2 a evaluar
 * @return float porcentaje de similitud entre la cadena 1 y la cadena 2
 */
function similarTxt($c1,$c2)
{
	similar_text($c1, $c2,$percent);
	
	return $percent;
}



/* Funcion que devuelve la distancia de levenshtein entre dos cadenas soundex
 * 
 * @param string c1 cadena 1 a evaluar
 * @param string c2 cadena 2 a evaluar
 * @return float distancia entre la cadena 1 y la cadena 2
 */
function soundLev($c1,$c2)
{
	return levenshtein(soundex($c1),soundex($c2));
}


/* Funcion que devuelve el porcentaje de similitud entre dos cadenas soundex
 * 
 * @param string c1 cadena 1 a evaluar
 * @param string c2 cadena 2 a evaluar
 * @return float porcentaje de similitud entre las cadenas c1 y c2
 */
function soundSim($c1,$c2)
{
	
	similar_text(soundex($c1),soundex($c2),$percent);
	
	return $percent;
	
}


/* Funcion que devuelve la distancia de levenshtein entre dos cadenas metaphone
 * 
 * @param string c1 cadena 1 a evaluar
 * @param string c2 cadena 2 a evaluar
 * @return float porcentaje de similitud entre las cadenas c1 y c2
 */
function metaLev($c1,$c2)
{
	return levenshtein(metaphone($c1),metaphone($c2));
}


/* Funcion que devuelve el porcentaje de similitud entre dos cadenas metaphone
 * 
 * @param string c1 cadena 1 a evaluar
 * @param string c2 cadena 2 a evaluar
 * @return float porcentaje de similitud entre las cadenas c1 y c2
 */
function metaSim($c1,$c2)
{
	similar_text(metaphone($c1),metaphone($c2),$percent);
	
	return $percent;
}
    

/*Funcion que calcula la distancia invariante transposicional a partir
 *  de los vectores de frecuencias de dos cadenas
 * 
 * @param array v vector de frecuencias
 * @param array w vector de frecuencias
 * @return int distancia invariante transposicional de los vectores de frecuencias
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
 
 /*Funcion que calcula la distancia invariante a la posicion
   de una palabra en una cadena
 * 
 * @param array v vector de palabras de la cadena
 * @param array w vector de palabras de la cadena
 * @return int distancia invariante a la posicion
 */
 function distanceDIP($v,$w)
 {
 	$matriz[][] = "";
	
	//Inicializamos el array 
	$matriz[0][0] = 0;
	
	for ($i=1; $i <= count($v) ; $i++) { 
		$matriz[$i][0] = strlen($v[$i-1]);
	}
	for ($i=1; $i <= count($w) ; $i++) { 
		$matriz[0][$i] = strlen($w[$i-1]);
	}
	for ($i=1; $i <= count($v) ; $i++) {
		for ($j=0; $j <= count($w) ; $j++) { 
			$matriz[$i][$j] = 0;
		} 
	}
	
	//Marcamos las palabras que tienen la DL nula (es cero)
	for ($i=1; $i <= count($v) ; $i++) { 
		for ($j=1; $j <= count($w) ; $j++) { 
			
			if(!$matriz[$i][$j])
			{
				$matriz[$i][$j] = levenshtein($v[$i-1], $w[$j-1]);
				
				if(!$matriz[$i][$j])
				{
					
					for ($iAux=0; $iAux <= count($v) ; $iAux++) { 
						$matriz[$iAux][$j] = -1;
					}
					for ($jAux=0; $jAux <= count($w) ; $jAux++) { 
						$matriz[$i][$jAux] = -1;
					}
					
					break;
				}
				
			}
			
		}
	}
	
	$costeMejor = -1;
	
	emparejamiento($matriz, count($v), count($w), 1, 0, $costeMejor);
	
	
	return $costeMejor;
 }
 
  /*Funcion que calcula la distancia invariante a la posicion
   de una palabra en una cadena empleando el coeficiente de jaccard
 * 
 * @param array v vector de palabras de la cadena
 * @param array w vector de palabras de la cadena
 * @param float	jaccard coeficiente de Jaccard empleado en la funcion
 * @return int distancia invariante a la posicion empleando el coficiente de jaccard
 */
 function distanceDIP2($v,$w,&$jaccard)
 {
 	$matriz[][] = "";
	
	//Inicializamos el array 
	$matriz[0][0] = 0;
	
	for ($i=1; $i <= count($v) ; $i++) { 
		$matriz[$i][0] = strlen($v[$i-1]);
	}
	for ($i=1; $i <= count($w) ; $i++) { 
		$matriz[0][$i] = strlen($w[$i-1]);
	}
	for ($i=1; $i <= count($v) ; $i++) {
		for ($j=0; $j <= count($w) ; $j++) { 
			$matriz[$i][$j] = 0;
		} 
	}
	
	//Marcamos las palabras que tienen la DL nula (es cero)
	for ($i=1; $i <= count($v) ; $i++) { 
		for ($j=1; $j <= count($w) ; $j++) { 
			
			if(!$matriz[$i][$j])
			{
				$matriz[$i][$j] = levenshtein($v[$i-1], $w[$j-1]);
				
				if(!$matriz[$i][$j])
				{
					
					for ($iAux=0; $iAux <= count($v) ; $iAux++) { 
						$matriz[$iAux][$j] = -1;
					}
					for ($jAux=0; $jAux <= count($w) ; $jAux++) { 
						$matriz[$i][$jAux] = -1;
					}
				}
				
			}
			
		}
	}
	
	for ($i=1; $i <= count($v) ; $i++) { 
		for ($j=1; $j <= count($w) ; $j++) { 
			
			if($matriz[$i][$j] >0)
			{
				if($matriz[$i][$j] <= (1 + ($matriz[$i][0] + $matriz[0][$j]) /20))
				{
					for ($iAux=0; $iAux <= count($v) ; $iAux++) { 
						$matriz[$iAux][$j] = -1;
					}
					for ($jAux=0; $jAux <= count($w) ; $jAux++) { 
						$matriz[$i][$jAux] = -1;
					}
					
					break;
				}
			}
		}
	}
	
	$num = 0;
	$den = 0;
	
	if(count($v) >= count($w))
	{
		$den = count($v);
		for ($i=1; $i <= count($w) ; $i++) { 
			if($matriz[0][$i] == -1)
			{
				$num++;
			}
			else
			{
				$den++;
			}
		}
	}
	else 
	{
		$den = count($w);
		for ($i=1; $i <= count($v) ; $i++) { 
			if($matriz[$i][0] == -1)
			{
				$num++;
			}
			else
			{
				$den++;
			}
		}
	}
	
	$jaccard = 1 - (1.0*$num)/$den;
	
	$costeMejor = -1;
	
	emparejamiento($matriz, count($v), count($w), 1, 0, $costeMejor);
	
	return $costeMejor;
 }
 
 
 
 /*Realiza el emparejamiento por backtracking (ramificacion y poda)
   Se pasa el costeActual y el costeMejor para realizar podas que
   incrementan la velocidad de ejecucion
 * 
 * @param array matriz matriz que indica si las cadenas se han emparejado o no
 * @param int lV longitud del array V
 * @param int lW longitud del array W
 * @param int nivel nivel donde se encuentra la recursion, enun principio es 0
 * @param int costeActual coste actual de la distancia entre las dos cadenas
 * @param int costeMejor coste mejor de la distancia entre las cadenas
 */
function emparejamiento($matriz , $lV,$lW,$nivel,$costeActual,&$costeMejor)
{
	
	$coste = 0;
	
	if($nivel <= $lV)
	{
		//No se ha eliminado la palabra
		if($matriz[$nivel][0]!= -1)
		{
			for ($i=0; $i < $lW ; $i++) { 
				if($matriz[$nivel][$i] != -1 && $matriz[0][$i]>0)
				{
					//Se puede empezar a emparejar la palabra
					$coste = $costeActual + $matriz[$nivel][$i];
					
					//Lo marca como cogido
					$matriz[0][$i]*=-1;
					
					//Llamada recursiva
					if($coste < $costeMejor || $costeMejor == -1)
					{
						emparejamiento($matriz, $lV, $lW, $nivel + 1 , $coste, $costeMejor);
					}
					
					//Desmarcamos la palabra cogida
					$matriz[0][$i]*=-1;
				}
			}
			
			//No se empareja con ninguna de las palabras
			$coste = $costeActual + $matriz[$nivel][0];
			
			if($coste < $costeMejor || $costeMejor == -1)
			{
				emparejamiento($matriz, $lV, $lW, $nivel + 1 , $coste, $costeMejor);
			}
			
		}
		else 
		{
			//Se pasa alsiguiente nivel porque la palabra ya se ha emparejado
			emparejamiento($matriz, $lV, $lW, $nivel+1, $costeActual, $costeMejor);
		}
	}
	else 
	{
		/*
		 * Se llega al final y se verifica que no se quede ninguna
		 * palabra sin emparejar 
		 */
		
		$coste = $costeActual;
			
		for ($i=0; $i < $lW ; $i++) { 
			if($matriz[0][$i] > 0)
			{
				$coste+= $matriz[0][$i];
			}
		}	
			
		if($coste < $costeMejor || $costeMejor == -1)
		{
			$costeMejor = $coste;
		}	
	}
	
}
  
    
/*******************************************************************
 * Funciones auxiliares
 ******************************************************************/
 
 

  /*Funcion que preprocesa las cadenas
 * 
 * @param array cadenasOri vector de cadenas sin procesar
 * @return array cadenas procesadas
 */
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


  /*Funcion que obtiene las cadenas del fichero
 * 
 * @param string fichero nombre del fichero del que se obtienen las cadenas
 * @return array cadenas del fichero
 */
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