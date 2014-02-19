<?php
/*******************************************************************
 * Funciones de Diccionario
 ******************************************************************/
class Diccionario {

	//La cadena
	private $c;
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

	public function getC() {
		return $this->c;
	}

	public function getL() {
		return $this->l;
	}

	public function getF() {
		return $this->f;
	}

	public function getDit() {
		return $this->dit;
	}

	public function getLDit() {
		return $this->lDit;
	}

	public function getArr() {
		return $this->arr;
	}

	public function __construct($cadena) {
		$this->c = $cadena;
		$this->l = strlen($this->c);
		$this->f = 1;
		$this->arr = explode(" ", $this->c);
		$this->dit = array_fill(0, 37, 0);
		$this->lDit = 0;

		
		for ($i = 0; $i < $this->l; $i++) {
			if ($this->c[$i] >= '0' && $this->c[$i] <= '9') {
				$this->dit[ord($this->c[$i]) - ord('0')]++;
				$this->lDit++;
			} else if ($this->c[$i] >= 'a' && $this->c[$i] <= 'z') {
				$this->dit[ord($this->c[$i]) - ord('a') + 10]++;
				$this->lDit++;
			} else if ($this->c[$i] == 'ñ') {
				$this->dit[36]++;
				$this->lDit++;
			}
		}

	}

}

function compareDiccionario($dic1,$dic2) {
		return strcmp($dic1->getC(), $dic2->getC());

	}


function cargarDiccionarios($fich,$abreviaturas)
{
	$cads = array();
		
	
	$gestor = fopen($fich, "r");
	
	
	if($gestor)
	{
		
		while (!feof($gestor))
		{
			$cadena = limpiaCad(fgets($gestor));
			
			$cadena = expandirAbreviaturas($cadena, $abreviaturas);
			
			$diccionario = new Diccionario($cadena);
			
        	array_push($cads,$diccionario);
	    
		}
		
	    fclose($gestor);
		

	}
	

	
	return $cads;
}


/*******************************************************************
 * Funciones de Abreviaturas
 ******************************************************************/

 class Abreviatura {

	//Abreviatura
	private $abrev;
	//Expansion
	private $cadena;

	public function getAbrev() {
		return $this->abrev;
	}

	public function getCadena() {
		return $this->cadena;
	}

	public function __construct($a, $c) {
		$this->abrev = $a;
		$this->cadena = $c;
	}

	public function compare($ab) {
		return strcmp($abrev, $ab . getAbrev());

	}

}
 
 
function cargarAbreviaturas($fich)
{
	$cads = array();
	
	$gestor = fopen($fich, "r");
	
	
	
	if($gestor)
	{
		
		$n = fgets($gestor);
		
		
		for($i = 0; $i < $n ; $i++)
		{
			$abrev = limpiaCad(fgets($gestor));
			$cadena = limpiaCad(fgets($gestor));
			
			$abreviatura = new Abreviatura($abrev,$cadena);
			
        	array_push($cads,$abreviatura);
	    
		}
		
	    fclose($gestor);
		

	}
	

	
	return $cads;
}

function expandirAbreviaturas($cadena,$abreviaturas)
{
	$cad  = $cadena;

	for ($i=0; $i < count($abreviaturas) ; $i++) { 
		
		if(strpos($cadena, $abreviaturas[$i]->getAbrev())!==false)
		{
			$cad = str_replace($abreviaturas[$i]->getAbrev(), $abreviaturas[$i]->getCadena(), $cadena);

		}
		
	}
	
	return $cad;
}

/*******************************************************************
 * Funciones auxiliares
 ******************************************************************/

function limpiaCad($cad)
{
	$cadena = trim($cad);
	$cadena = utf8_encode(strtr(utf8_decode($cadena), utf8_decode('àáèéìíòóùúÀÁÈÉÌÍÒÓÙÚ'),'aaeeiioouuAAEEIIOOUU'));

	$cadena = preg_replace("/[^A-Za-z0-9\.\s\s+]/","",$cadena);
	$cadena = strtolower($cadena);
	
	
	return $cadena;
}

?>