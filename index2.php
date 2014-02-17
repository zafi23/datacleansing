<?php

include_once 'lib/tablas.php';

//Cargar las abreviaturas
$abreviaturas = cargarAbreviaturas("ua.siglas");

for ($i=0; $i < count($abreviaturas) ; $i++) { 
	echo $abreviaturas[$i]->getAbrev()."</br>";
	echo $abreviaturas[$i]->getCadena()."</br>";
}


//Cargar las cadenas y almacenarlas




?>