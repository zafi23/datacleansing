<?php

include_once 'TElemento.php';
include_once 'TDistancias.php';
include_once 'TCluster.php';

/*******************************************************************
 * Funciones de clustering
 ******************************************************************/

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

function leerCadenas($fich,&$cads,&$n) {
	

	$gestor = fopen($fich, "r");

	if ($gestor) {

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

	return $dist;

}

function saveFichB($cads,$listaC)
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
	
	saveFich($cadenas, "ua.resB.csv");
	
}

function saveFichC($cads,$listaC)
{
	$cadenas = $cads;
	
	for($i= 0; $i < count($listaC); $i++)
	{
		
		$tc = $listaC[$i]->cluster();
		$centro = $listaC[$i]->centro();
		$cadCentro = $cadenas[$centro]->getC(); 
		
		
		for($j = 0; $j < count($tc); $j++)
		{
			$cadenas[$tc[$j]]->setC($cadCentro);
			$cadenas[$tc[$j]]->reestructuraCsv();
		}
	
	}
	
	
	saveFich($cadenas, "ua.resC.csv");
	
}

function saveFich($cadenas,$fichero)
{
	usort($cadenas, "compareElemento");
	
	$file = fopen($fichero,"w");

	foreach ($cadenas as $cad) 
	{
		fputs($file, $cad->getCsv());
	}
	
	fclose($file);
}




?>