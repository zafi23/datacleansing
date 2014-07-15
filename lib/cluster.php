<?php

include_once 'TElemento.php';
include_once 'TDistancias.php';
include_once 'TCluster.php';

/*******************************************************************
 * Funciones de clustering
 ******************************************************************/

/*Funcion que realiza el algoritmo de emparejamiento de las cadenas
 * 
 * @param array listaC lista de los clusteres obtenidos
 * @param TDistancias dist distancias entre las cadenas
 * @param int numero referencia al elemento que se quiere emparejar
 * @param float alphaDL valor alpha que se considera para introducir un elemento en el emparejamiento 
 */
function clustering(&$listaC,$dist,$numero,$alphaDL)
{
	
	$d = 0;
	$dAux = 0;
	
	$tc = new TCluster();
	$tc->inserta(0);
	array_push($listaC,$tc);

	$tc->calcularCentro($dist);

	for($elemento = 1; $elemento < $numero ; $elemento++)
	{
		$tlc = 0;
		
		$d = $dist->distancia($elemento,$listaC[$tlc]->centro());
		$tc = $listaC[$tlc];
		
		$tlc++;
		
		while($tlc < count($listaC))
		{
			$dAux = $dist->distancia($elemento,$listaC[$tlc]->centro());
			
			if($dAux < $d)
			{
				$d = $dAux;
				
				$tc = $listaC[$tlc];
			}
			
			$tlc++;
		}
		
		if($d <= $alphaDL)
		{
			$tc->inserta($elemento);
		}
		else 
		{
			$tc = new TCluster();
			$tc->inserta($elemento);
			array_push($listaC,$tc);	
		}
		
		$tc->calcularCentro($dist);
	}
	
	
}

/*******************************************************************
 * Funciones de mostrado
 ******************************************************************/
/*Funcion que muestra la informacion de los clusters
 * 
 * @param array listaC lista de los clusteres obtenidos
 * @param array cads array de las TElementos existentens
 */
function mostrar($listaC,$cads)
{
	
	for($i= 0; $i < count($listaC); $i++)
	{
		
		$tc = $listaC[$i]->cluster();
		
		for($j = 0; $j < count($tc); $j++)
		{
			echo $cads[$tc[$j]]->getIndice()."</br>";
		}
		
		echo "</br>";

	}
	
	
} 
 

/*******************************************************************
 * Funciones de cargado de datos
 ******************************************************************/

/*Funcion que carga los datos desde un fichero
 * 
 * @param string fich nombre del fichero del que se obtienen los datos
 * @param array cads array de Telemento donde se almacenan las cadenas
 * @param int n posision de la cadena en el csv original
 * @param string email email del usuario
 * @param float alpha valor alpha para calcular los emparejamientos
 * @return TDistancias devuelve la matriz de las distancias entre las cadenas
 */ 
function leerCadenas($fich,&$cads,&$n,&$email,&$alpha) {
	

	$gestor = fopen($fich, "r");

	if ($gestor) {

		$email = fgets($gestor);
		$alpha = fgets($gestor);
		$n = fgets($gestor);
		
		$dist = new TDistancias($n);
		

		for ($i = 0; $i < $n; $i++) {
			$cadenaCsv = fgets($gestor);
			$cadena = trim(fgets($gestor));
			$frecuencia = fgets($gestor);
			$longDL = fgets($gestor);
			$longDIP = fgets($gestor);
			$indice = fgets($gestor);
			$position = fgets($gestor);

			$elemento = new TElemento($cadenaCsv,$position,$cadena, $frecuencia, $longDL, $longDIP,$indice);

			array_push($cads, $elemento);

		}

		
		for($i = 0; $i < $n; $i++)
		{
			$dist->inserta($i,$i,0);
		}
		
		for($i = 0; $i < $n; $i++)
		{
			$line = fgets($gestor);
			$u = explode(" ",$line);
			for($j=0; $j < ($n-$i-1);$j++)
			{
				$dip = $u[$j];
				$dist->inserta($i,$i+$j+1,$dip);
				$dist->inserta($i+$j+1,$i,$dip);	
			}
		}
		
		fclose($gestor);
	}
	else
		{
			echo "no me abro xk si";
		}

	return $dist;

}

/*Funcion que guarda las cadenas en el fichero borrando las cadenas de los emparejamientos
 * y dejando solo la cadena centro de cada emparejamiento
 * 
 * @param file referencia con nombre al fichero que esta abierto
 * @param array cads array de TElemento que contiene las cadenas
 * @param array listaC array que contiene la lista de clusteres obtenidos
 */
function saveFichB($file,$cads,$listaC)
{

	$cadenas = $cads;
	
	for($i= 0; $i < count($listaC); $i++)
	{
		
		$tc = $listaC[$i]->cluster();
		$centro = $listaC[$i]->centro();
		
		$cadenas[$centro]->reestructuraCsv();
		
		for($j = 0; $j < count($tc); $j++)
		{
			if($tc[$j]!= $centro)
				unset($cadenas[$tc[$j]]);
		}
	
	}
	
	saveFich($file,$cadenas);
	
}

/*Funcion que guarda las cadenas en el fichero manteniendo las cadenas de los emparejamientos
 * y copiando al cadena centro a las demas cadenas del emparejamiento
 * 
 * @param file referencia con nombre al fichero que esta abierto
 * @param array cads array de TElemento que contiene las cadenas
 * @param array listaC array que contiene la lista de clusteres obtenidos
 */
function saveFichC($file,$cads,$listaC)
{
	$cadenas = $cads;
	
	for($i= 0; $i < count($listaC); $i++)
	{
		
		$tc = $listaC[$i]->cluster();
		$centro = $listaC[$i]->centro();
		$cadCentro = $cadenas[$centro]->getCOri(); 
		
		
		for($j = 0; $j < count($tc); $j++)
		{
			$cadenas[$tc[$j]]->setC($cadCentro);
			$cadenas[$tc[$j]]->reestructuraCsv();
		}
	
	}
	
	
	saveFich($file,$cadenas);
	
}

/*Funcion que guarda el fichero
 * 
 * @param file referencia con nombre al fichero que esta abierto
 * @param array cadenas array de TElemento que contiene las cadenas
 */
function saveFich($file,$cadenas)
{
	usort($cadenas, "compareElemento");
	
	foreach ($cadenas as $cad) 
	{
		fputs($file, $cad->getCsv());
	}
	
}




?>