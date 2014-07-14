<?php
	
	include_once("lib/cluster.php");

	session_start();

	function cargarClusters($email,$nomFich)
	{
			
		$alpha = "";
	    $fich = "users/".$email."/".$nomFich;
		$cads = array(); 
		$listaCluster = array();
		
		$dist = leerCadenas($fich,$cads,$numero,$email,$alpha);
	
		clustering($listaCluster,$dist,$numero,$alpha);
	
		$nomFArr = split(".",$nomFich);
	
		$_SESSION["cads"] = serialize($cads);
		$_SESSION["clusters"] = serialize($listaCluster); 
		$_SESSION["email"] = $email;
		$_SESSION["nomF"] = $nomFich;
		$_SESSION["dist"] = serialize($dist);
				
	}
	
	function mostrarListaClusters($clusters)
	{
		
		echo "<select id='listaClusters' name='listaClusters' class='floatIz' size='15'>";
		
		for($i = 0; $i < count($clusters); $i++)
		{
			echo "<option value='cluster_".($i+1)."' onclick='mostrarCadenas(".$i.")'>Cluster ".($i+1)."</option>";
		}
		
		echo "</select>";
	}

	function cadenasCluster($cluster)
	{
		
		$cadenas = array();
		
		if(isset($_SESSION["cads"]) && isset($_SESSION["clusters"]))
		{
			$cads = unserialize($_SESSION["cads"]);
			$clusters = unserialize($_SESSION["clusters"]);
			
			$tc = $clusters[$cluster]->cluster();
			
			for($i = 0; $i < count($tc); $i++)
			{				
				array_push($cadenas,array($cads[$tc[$i]]->getCsv(),$tc[$i]));
			}
		}
		
		return $cadenas;
	}
	
	function aplicarMods($mods)
	{
		$cads = unserialize($_SESSION["cads"]);
		$clusters = unserialize($_SESSION["clusters"]);
		$dist = unserialize($_SESSION["dist"]);

		for($i = 0; $i < count($mods); $i++)
		{
			$cads[$mods[$i]->indice]->setCOri($mods[$i]->cadena);  
			
			if($mods[$i]->operacion == 0)
			{
				$tc = $clusters[intval($mods[$i]->cluster)]->cluster();
				$centro = $clusters[intval($mods[$i]->cluster)]->centro();
				
				for($j = 0; $j < count($tc); $j++)
				{			
					if(intval($tc[$j]) == intval($mods[$i]->indice) )
					{
						$elem = $tc[$j];
						$clus = new TCluster();
						$clus->inserta($elem);
						$clus->calcularCentro($dist);
						array_push($clusters,$clus);	
						unset($tc[$j]);
						break;
					}
				}
				
				$tc = array_keys($tc);
					
				$clusters[$mods[$i]->cluster]->setCluster($tc);
				$clusters[$mods[$i]->cluster]->calcularCentro($dist);
			}
			else if($mods[$i]->operacion == 1)
			{
					
				$tc = $clusters[intval($mods[$i]->cluster)]->cluster();
				$centro = $clusters[intval($mods[$i]->cluster)]->centro();
				
				for($j = 0; $j < count($tc); $j++)
				{			
					if(intval($tc[$j]) == intval($mods[$i]->indice))
					{
						$elem = $tc[$j];
						unset($tc[$j]);
						break;
					}
				}
				
				$tc = array_values($tc);
				$clusters[$mods[$i]->cluster]->setCluster($tc);
				$clusters[$mods[$i]->cluster]->calcularCentro($dist);
				unset($cads[$mods[$i]->indice]);
			} 
			
		}
		
		$_SESSION["cads"] = serialize($cads);
		$_SESSION["clusters"] = serialize($clusters);
		
	}
	


?>