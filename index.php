<?php


	include_once 'lib/distancias.php';
	include_once 'lib/tablas.php';

//Inicializamos las cadenas y las variables secundarias

	/*$fichero = "uax_modificado.txt";	
	$cadenasOri = leerFich($fichero);
	$cadenas = array();*/
	$tabla[][] = "";

	$minLev = PHP_INT_MAX;
	$maxLev = -1;
	$medLev = 0;
	
	$minSim = PHP_INT_MAX;
	$maxSim = -1;
	$medSim = 0;
	
	$minSoundLev = PHP_INT_MAX;
	$maxSoundLev = -1;
	$medSoundLev = 0;
	
	$minMetaLev = PHP_INT_MAX;
	$maxMetaLev = -1;
	$medMetaLev = 0;
	
	$minSoundSim = PHP_INT_MAX;
	$maxSoundSim = -1;
	$medSoundSim = 0;
	
	$minMetaSim = PHP_INT_MAX;
	$maxMetaSim = -1;
	$medMetaSim = 0;
	
	//Preprocesamos las cadenas
	//$cadenas = preprocess($cadenasOri);

	$abreviaturas = cargarAbreviaturas("ua.siglas");

$cadenas = cargarDiccionarios("uax_modificado.txt",$abreviaturas);

usort($cadenas,"compareDiccionario");
	
	
//Calculamos las funciones para cada cadena

//tomamos tiempos de lo que tarda cada distancia con cada cadena , hacer solo un for para cada cadena y se pasa con todas las distancias

	$k = 0;

	for ($i=0; $i < count($cadenas) ; $i++) { 
		for ($j=$i+1; $j < count($cadenas); $j++) {
			
			//Calculamos Levinshtein 
			 $t1 = microtime();
			 $lev = levenshtein($cadenas[$i]->getC(), $cadenas[$j]->getC());
			 $t2 = microtime();
			 
			 $medLev+=abs($t2-$t1);
			 $maxLev = max($maxLev,abs($t2-$t1));
			 $minLev = min($minLev,abs($t2-$t1));
			
			//Calculamos SimilarText
			 $t1 = microtime();
			 $sim = similarTxt($cadenas[$i]->getC(), $cadenas[$j]->getC());
			 $t2 = microtime();
			 
			 $medSim+=abs($t2-$t1);
			 $maxSim = max($maxSim,abs($t2-$t1));
			 $minSim = min($minSim,abs($t2-$t1));
			 
			 //Calculamos Soundex con levinshtein
			 $t1 = microtime();
			 $s_lev = soundLev($cadenas[$i]->getC(), $cadenas[$j]->getC());
			 $t2 = microtime();
			 
			 $medSoundLev+=abs($t2-$t1);
			 $maxSoundLev = max($maxSoundLev,abs($t2-$t1));
			 $minSoundLev = min($minSoundLev,abs($t2-$t1));
			 
			 //Calculamos Soundex con similar
			 $t1 = microtime();
			 $s_sim = soundSim($cadenas[$i]->getC(), $cadenas[$j]->getC());
			 $t2 = microtime();
			 
			 $medSoundSim+=abs($t2-$t1);
			 $maxSoundSim = max($maxSoundSim,abs($t2-$t1));
			 $minSoundSim = min($minSoundSim,abs($t2-$t1));
			 
			 //Calculamos Metaphone con levenshtein
			 $t1 = microtime();
			 $meta_lev = metaLev($cadenas[$i]->getC(), $cadenas[$j]->getC());
			 $t2 = microtime();
			 
			 $medMetaLev+=abs($t2-$t1);
			 $maxMetaLev = max($maxMetaLev,abs($t2-$t1));
			 $minMetaLev = min($minMetaLev,abs($t2-$t1));
			 
			 //Calculamos Metaphone con similar
			 $t1 = microtime();
			 $meta_sim = metaSim($cadenas[$i]->getC(), $cadenas[$j]->getC());
			 $t2 = microtime();
			 
			 $medMetaSim+=abs($t2-$t1);
			 $maxMetaSim = max($maxMetaSim,abs($t2-$t1));
			 $minMetaSim = min($minMetaSim,abs($t2-$t1));
			 
			 
			 //Introducimos valores en la matriz
			 $tabla[$i][$j] = $lev." , ".round($sim,2)." , ".$s_lev." , ".round($s_sim,2)." , ".$meta_lev." , ".round($meta_sim,2);
			 
			 
			 $k.= 1;
		}
		
		
	}
	
	$medLev = $medLev/$k;
	$medMetaLev = $medMetaLev/$k;
	$medMetaSim = $medMetaSim/$k;
	$medSim = $medSim/$k;
	$medSoundLev = $medSoundLev/$k;
	$medSoundSim = $medSoundSim/$k;





?>

<!DOCTYPE html>
<html>
<body>



<p>Orden de distancias en la tabla: Levinshtein , SimilarText , Soundex con Levinshtein, Soundex con SimilarText, Metaphone con Levinshtein, Metaphone con SimilarText.</p>
<p>La distancia de Levinshtein muestra el numero de cambios necesarios para convertir la primera cadena en la segunda.</p>
<p>SimilarText muestra el porcentaje de parecido existente entre dos cadenas.</p>
<p>Soundex se utiliza para saber el sonido (codificado en una cadena de 4 caracteres) de una cadena en ingles, en nuestro caso primero comparamos los sonidos Soundex de las cadenas mediante Levinshtein y tambien con SimilarText.</p>
<p>Para Metaphone hacemos la misma comparacion que con Soundex. Metaphones nos permite conocer la codificacion del sonido de la cadena pero sin la restriccion de que el codigo sea de 4 caracteres.</p>

<p><strong>Cadenas empleadas:</strong></p>

<?php
	
	for ($i=0; $i < count($cadenas) ; $i++) { 
		echo "s".($i+1).": ".$cadenas[$i]->getC()."<br>";
	}


?>

<br />
<table border="1">

<?php
	
	for($i = 0; $i <= count($cadenas);$i++)
	{
		echo "<tr>";
			
		for ($j=0; $j <= count($cadenas) ; $j++) 
		{
			echo "<td>"; 
			if($i == 0)
			{
				if($j!=0)
					echo "s".($j);
			}	
			else if($j == 0 && $i!=0)
			{
				echo "s".$i;
			}
			else 
			{
				if(isset($tabla[$i-1][$j-1]))
					echo $tabla[$i-1][$j-1];	
			}
			
			echo "</td>";
		}
		echo "</tr>";
	}

?>

</table>

<p>Estadisticas:</p>
<strong>Levinshtein</strong>
<p>Min: <?= $minLev  ?></p>
<p>Max: <?= $maxLev  ?></p>
<p>Med: <?= $medLev  ?></p>

<strong>SimilarText</strong>
<p>Min: <?= $minSim  ?></p>
<p>Max: <?= $maxSim  ?></p>
<p>Med: <?= $medSim  ?></p>

<strong>Soundex</strong>
<p>Min con Levinshtein: <?= $minSoundLev  ?></p>
<p>Max con Levinshtein: <?= $maxSoundLev  ?></p>
<p>Med con Levinshtein: <?= $medSoundLev  ?></p>
<p>Min con SimilarText: <?= $minSoundSim  ?></p>
<p>Max con SimilarText: <?= $maxSoundSim  ?></p>
<p>Med con SimilarText: <?= $medSoundSim  ?></p>

<strong>Metaphone</strong>
<p>Min con Levinshtein: <?= $minMetaLev  ?></p>
<p>Max con Levinshtein: <?= $maxMetaLev  ?></p>
<p>Med con Levinshtein: <?= $medMetaLev  ?></p>
<p>Min con SimilarText: <?= $minMetaSim  ?></p>
<p>Max con SimilarText: <?= $maxMetaSim  ?></p>
<p>Med con SimilarText: <?= $medMetaSim  ?></p>

</body>
</html>
