<?php

	/*
	 * Clase TDistancias donde se guarda la informacion de las distancias entre las 
	 * cadenas
	 */
	class TDistancias{
		//Tamanyo del vector	
		private $n;
		//Vector de distancias dip
		private $dip;
		
		public function __construct($num)
		{
			$this->dip[][] = "";
			$this->n = $num;	
		}
		
		public function inserta($i,$j,$dist)
		{
			$this->dip[$i][$j] = $dist;
		}
	
		public function distancia($i,$j)
		{
			return $this->dip[$i][$j];
		}
		
		public function getN()
		{
			return $this->n;
		}
		
		public function getDIP()
		{
			return $this->dip;
		}
	}

?>