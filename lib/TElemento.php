<?php
   
   class TElemento{
			
		//Cadena
		private $c;
		
		//Cadena Original
		private $cOri;
		
		//Cadena csv original
		private $csv;
		
		//Posicion de la cadena dentro de la cadena csv
		private $pos;
		
		//Longitud   	
		private $lDL;
		private $lDIP;
		
		//Frecuencia
   		private $f;
		
		//indice de la cadena en el fichero original
		private $indice;
		
		
		
   	
		public function __construct($cvsCad,$posi,$cad,$frec,$longDL,$longDIP,$ind)
		{
			$this->csv = $cvsCad;
			$this->pos = $posi;
			$this->c = $cad;
			$this->f = $frec;
			$this->lDIP = $longDIP;
			$this->lDL = $longDL;
			$this->indice = $ind;
			
			$strCsv = split(";", $cvsCad);
			
			$this->cOri = $strCsv[intval($posi)];
		}
		
		//Funcion para modificar el campo a evaluar del csv
		public function reestructuraCsv()
		{
			$csv = explode(";",$this->csv);
			
			$csv[intval($this->pos)] = $this->cOri;
			
			$this->csv = implode(";", $csv);
			
		}
		
		public function getC()
		{
			return $this->c;
		}
		
		public function getCOri()
		{
			return $this->cOri;
		}
		
		public function setC($cad)
		{
			$this->c = $cad;
		}
		
		public function setCOri($cad)
		{
			$this->cOri = $cad;
			$this->reestructuraCsv();
		}
		
		public function getCsv()
		{
			return $this->csv;
		}
		
		public function getPos()
		{
			return $this->pos;
		}
		
		public function getF()
		{
			return $this->f;
		}
		
		public function getLDL()
		{
			return $this->lDL;
		}
		
		public function getLDIP()
		{
			return $this->lDIP;
		}
	
		public function getIndice()
		{
			return $this->indice;
		}
	
   }
   
   function compareElemento($el1, $el2) {

	if (intval($el1 -> getIndice()) > intval($el2 -> getIndice())) {
		return 1;
	} else if (intval($el1 -> getIndice()) == intval($el2 -> getIndice())) {
		return 0;
	} else {
		return -1;
	}

}
   
   
?>