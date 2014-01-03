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
    
    
    
    
    
    
?>