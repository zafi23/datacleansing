<?php
  
  include_once 'TDistancias.php';
  
  
  class TCluster{
  	
	//Elemento que es el centro del cluster
	private $c;
	
	//Número de elementos del cluster
	private $n;
	
	//Valor del criterio que define el centro
	//Se usa el error cuadratico medio
	private $ecm;
	
	//Vector que almacena los indices de los elementos
	private $cluster;
	
	public function __construct()
	{
		$this->c = -1;
		$this->n = 0;
		$this->ecm = 0;
		$this->cluster = array();
		
	}
	
	public function cluster()
	{
		return $this->cluster;
	}
	
	public function setCluster($c)
	{
		$this->cluster = $c;
	}	
	
	public function primero()
	{
		return $this->cluster[0];
	}
	
	public function ultimo()
	{
		return end($this->cluster);
	}
	
	public function centro()
	{
		return $this->c;
	}
	
	public function numero()
	{
		return $this->n;
	}
	
	public function criterio()
	{
		return $this->ecm;
	}
	
	public function inserta($elemento)
	{
		array_push($this->cluster,$elemento);
		$this->n++;
	}
	
	public function calcularCentro($dist)
	{
		$d = 0;
		$ecmAux = 0;
		$i = 0;
		$j = 1;
		
		while($j < count($this->cluster))
		{
			$d = $dist->distancia($this->cluster[$i],$this->cluster[$j]);
			$ecmAux += $d * $d;
			$j++;
		}
		
		$this->c = $this->cluster[$i];
		$this->ecm = $ecmAux;
		
		$i++;
		while($i < count($this->cluster))
		{
			$ecmAux = 0;
			$j = 0;
			
			while($j < count($this->cluster))
			{
				if($this->cluster[$i] != $this->cluster[$j])
				{
					$d = $dist->distancia($this->cluster[$i],$this->cluster[$j]);
					$ecmAux += $d * $d;
				}
				
				$j++;
			}
			
			if($ecmAux < $this->ecm)
			{
				$this->ecm = $ecmAux;
				$this->c = $this->cluster[$i];  
			}
			
			$i++;
		}
		
		return $this->c;
	}
	
	
  }
  
  
?>