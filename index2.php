<?php

include_once 'lib/tablas.php';

//Cargar las abreviaturas
$abreviaturas = cargarAbreviaturas("ua.siglas");

$cadenas = cargarDiccionarios("uax_modificado.txt", $abreviaturas);

usort($cadenas, "compareDiccionario");

for ($i = 0; $i < count($cadenas); $i++) {
	echo $cadenas[$i] -> getC() . "</br>";

	$arr = $cadenas[$i] -> getDit();

	for ($j = 0; $j < count($arr); $j++) {

		echo $arr[$j] . " ";
	}
	echo "</br>";
}

//Cargar las cadenas y almacenarlas
?>