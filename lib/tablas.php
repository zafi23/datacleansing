<?php

/*******************************************************************
 * Includes
 ******************************************************************/
include_once 'distancias.php';



/*******************************************************************
 * Funciones de Diccionario
 ******************************************************************/
 
/*
 * Clase donde se almacena la informacion de las cadenas 
 * 
 */ 
class Diccionario {

	//La cadena
	private $c;
	//Cadena de csv completa
	private $csv;
	//Posicion de la cadena en el csv
	private $pos;
	//Longitud de la cadena
	private $l;
	//Frecuencia de aparicion
	private $f;
	//Vector DIT de frecuencias
	private $dit;
	//Longitud de caracteres almacenados en el vector de frecuencias
	private $lDit;
	//Array de division de palabras de la cadena
	private $arr;
	//Indice de la linea de la cadena en el fichero original
	private $indice;

	public function getC() {
		return $this -> c;
	}

	public function setC($cad) {
		$this -> c = $cad;
	}
	
	public function getCsv()
	{
		return $this->csv;
	}
	
	public function getPos()
	{
		return $this->pos;
	}
	
	public function getL() {
		return $this -> l;
	}

	public function getF() {
		return $this -> f;
	}

	public function setF($f) {
		$this -> f = $f;
	}

	public function getDit() {
		return $this -> dit;
	}

	public function getLDit() {
		return $this -> lDit;
	}

	public function getArr() {
		return $this -> arr;
	}

	public function getIndice() {
		return $this -> indice;
	}

	  /*Constructor de la clase Diccionario
	 * Se guardan los datos y se calcula el vector de frecuencias dit
	 * 
	 * @param string cadena cadena principal de la clase diccionario
	 * @param string csvCad cadena csv original del fichero de base de datos
	 * @param int position posicion donde se encuentra la cadena respecto del csv
	 * @param int in indice que ocupa la cadena en el fichero de base de datos original
	 */
	public function __construct($cadena,$csvCad,$position,$in) {
		$this -> c = $cadena;
		$this -> l = strlen($this -> c);
		$this -> f = 1;
		$this -> arr = explode(" ", $this -> c);
		$this -> dit = array_fill(0, 37, 0);
		$this -> lDit = 0;
		
		$this -> indice = $in;
		$this -> csv = $csvCad;
		$this -> pos = $position;

		for ($i = 0; $i < $this -> l; $i++) {
			if ($this -> c[$i] >= '0' && $this -> c[$i] <= '9') {
				$this -> dit[ord($this -> c[$i]) - ord('0')]++;
				$this -> lDit++;
			} else if ($this -> c[$i] >= 'a' && $this -> c[$i] <= 'z') {
				$this -> dit[ord($this -> c[$i]) - ord('a') + 10]++;
				$this -> lDit++;
			} else if ($this -> c[$i] == 'ñ') {
				$this -> dit[36]++;
				$this -> lDit++;
			}
		}

	}

}

  /*Funcion que obtiene compara dos objetos Diccionario por su frecuencia
 * 
 * @param Diccionario dic1 diccionario 1
 * @param Diccionario dic2 diccionario 2
 * @return int el resultado de la comparacion
 */
function compareDiccionario($dic1, $dic2) {

	if ($dic1 -> getF() > $dic2 -> getF()) {
		return -1;
	} else if ($dic1 -> getF() == $dic2 -> getF()) {
		return 0;
	} else {
		return 1;
	}

}

  /*Funcion que lee wl fichero y obtiene las cadenas para guardarlas como diccionarios
 * 
 * @param string fich nombre del fichero del que se obtienen las cadenas
 * @param Abreviatura abreviaturas array de la clase Abreviatura que se usa para expandir las cadenas
 * @param int position posicion de la cadena respecto del csv
 * @return array diccionarios generados
 */
function cargarDiccionarios($fich,$abreviaturas,$position) {
	$cads = array();
	
	$indice = 0;
	
	$gestor = fopen($fich, "r");
	
	
	
	if ($gestor) {

		while (!feof($gestor)) {
			$cadenaCsv = fgets($gestor);
			
			$c = explode(";", $cadenaCsv);
			$cadena = limpiaCad($c[intval($position)]);
			
			if(isset($abreviaturas))
			{
				$cadena = expandirAbreviaturas($cadena, $abreviaturas);	
			}

			if (!contiene($cads, $cadena)) {
				$diccionario = new Diccionario($cadena,$cadenaCsv,$position,$indice);
				array_push($cads, $diccionario);
				
				$indice++;
			}

		}

		fclose($gestor);

	}

	return $cads;
}

/*******************************************************************
 * Funciones de Abreviaturas
 ******************************************************************/

/*
 * Clase donde se guardan las abreviaturas con su abreviatura y su expansion correspondiente
 * */ 
class Abreviatura {

	//Abreviatura
	private $abrev;
	//Expansion
	private $cadena;

	public function getAbrev() {
		return $this -> abrev;
	}

	public function getCadena() {
		return $this -> cadena;
	}

	public function __construct($a, $c) {
		$this -> abrev = $a;
		$this -> cadena = $c;
	}

	public function compare($ab) {
		return strcmp($abrev, $ab . getAbrev());

	}

}

  /*Funcion que obtiene las abreviaturas del fichero de abreviaturas
 * 
 * @param string fich nombre del fichero del que se obtienen las abreviaturas
 * @return array devuelve un array de abreviaturas 
 */
function cargarAbreviaturas($fich) {
	$cads = array();

	$gestor = fopen($fich, "r");

	if ($gestor) {
	
		$n = fgets($gestor);

		for ($i = 0; $i < $n; $i++) {	
			
			$abrev = limpiaCad(fgets($gestor));
			$cadena = limpiaCad(fgets($gestor));

			$abreviatura = new Abreviatura($abrev, $cadena);

			array_push($cads, $abreviatura);

		}

		fclose($gestor);

	}


	return $cads;
}

  /*Funcion que expande las abreviaturas de una cadena
 * 
 * @param string cadena cadena a expandir abreviaturas
 * @param array abreviaturas array que contiene la informacion de las abreviaturas
 * @return string cadena con las abreviaturas expandidas
 */
function expandirAbreviaturas($cadena, $abreviaturas) {
	$cad = $cadena;
	
	for ($i = 0; $i < count($abreviaturas); $i++) {

		if (strpos($cadena, $abreviaturas[$i] -> getAbrev()) !== false) {
			$cad = str_replace($abreviaturas[$i] -> getAbrev(), $abreviaturas[$i] -> getCadena(), $cadena);

		}

	}

	return $cad;
}

/*******************************************************************
 * Funciones Clustering
 ******************************************************************/

   /*Funcion que calcula la distancia menor entre las cadenas
 * Calcula y compara distintos tipos de distancias para conseguir el menor coste o distancia entre ellas
 * 
 * @param file recurso con nombre asociado a un fichero abierto
 * @param array cadenas array de diccionarios con todas las cadenas del fichero 
 */
function clusteringTabla($file,$cadenas) {

	$i = 0;
	$j = 0;
	
	$jaccard = 0.0;
	
	$mejor = 0.0;
	
	$distancias = 0;

	while ($i < count($cadenas)) {
	
		$j = $i+1;
		
		
		while($j < count($cadenas))
		{
			$distancias++;
			
			$auxDL = (1.0 * levenshtein($cadenas[$i]->getC(), $cadenas[$j]->getC()) / max($cadenas[$i]->getL(),$cadenas[$j]->getL()));
			
			$auxDIP = (1.0 * distanceDIP($cadenas[$i]->getArr(),$cadenas[$j]->getArr()) /max($cadenas[$i]->getLDit(),$cadenas[$j]->getLDit()));
			
			
			
			if($auxDL <=$auxDIP)
			{
				$mejor = $auxDL;
			}
			else
			{
				$mejor = $auxDIP;	
			}
			
			$auxDIP2 = (1.0 * distanceDIP2($cadenas[$i]->getArr(),$cadenas[$j]->getArr(),$jaccard)/max($cadenas[$i]->getLDit(),$cadenas[$j]->getLDit()));
			
			if($auxDIP2 <= $mejor)
			{
				$mejor = $auxDIP2;
				
			}
			
			if($jaccard <= $mejor)
			{
				$mejor = $jaccard;
			}
			
			/*$auxS = 1 - (soundSim($cadenas[$i]->getC(), $cadenas[$j]->getC())/100);
			$auxM = 1 - (metaSim($cadenas[$i]->getC(), $cadenas[$j]->getC())/100);
			
			if($auxS <= $mejor)
			{
				$mejor = $auxS;
			}
			
			if($auxM <= $mejor)
			{
				$mejor = $auxM;
			}
			*/
			
			fputs($file,number_format(round($mejor,6),6)." ");
			
			
			$j++;
		}
	
		fputs($file,"\n");
		
		$i++;
	}

	fputs($file,"Distancias calculadas:".$distancias." x 4\n");
}

/*******************************************************************
 * Funciones auxiliares
 ******************************************************************/

  /*Funcion que devuelve si una cadena esta entre los diccionarios guardados
 * 
 * @param array cads array de Diccionarios 
 * @param string c cadena a evaluar
 * @return boolean devuelve true si el array de disccionarios contiene la cadena c
 */
function contiene($cads, $c) {
	$contiene = false;

	for ($i = 0; $i < count($cads); $i++) {
		if (strcmp($cads[$i] -> getC(), $c) == 0) {
			$cads[$i] -> setF($cads[$i] -> getF() + 1);
			$contiene = true;
			break;
		}
	}

	return $contiene;
}

  /*Funcion que limpia las cadenas 
 * 
 * @param string cad cadena a limpiar
 * @return string cadena limpiada
 */
function limpiaCad($cad) {
	$cadena = utf8_encode($cad);
	$cadena = trim($cad);
	$cadena = utf8_encode(strtr(utf8_decode($cadena), utf8_decode('àáèéìíòóùúÀÁÈÉÌÍÒÓÙÚÑ'), 'aaeeiioouuAAEEIIOOUUñ'));

	//$cadena = preg_replace("/[^A-Za-z0-9\.\(\)\"\'\s\s+]/", "", $cadena);
	$cadena = strtolower($cadena);

	return $cadena;
}
?>