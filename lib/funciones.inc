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
				
	}
	
	function mostrarListaClusters($clusters)
	{
		
		echo "<select name='listaClusters' size='20'>";
		
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

		for($i = 0; $i < count($mods); $i++)
		{
			$cads[$mods[$i]->indice]->setCOri($mods[$i]->cadena);  
			
			if($mods[$i]->operacion == 0)
			{
				$tc = $clusters[intval($mods[$i]->cluster)]->cluster();
				
				for($j = 0; $j < count($tc); $j++)
				{			
					if(intval($tc[$j]) == intval($mods[$i]->indice) )
					{
						unset($tc[$j]);
						break;
					}
				}
				
				array_values($tc);
				$clusters[$mods[$i]->cluster]->setCluster($tc);
			}
			
		}
		
		$_SESSION["cads"] = serialize($cads);
		$_SESSION["clusters"] = serialize($clusters);
		
	}
	


?>