<?php
	
	include_once("lib/cluster.php");

	session_start();

	/*Funcion que carga la informacion de los emparejamientos y guarda la informacion en variables de session 
	 * La informacion se recoge en un directorio dado por el email y por el nombre del fichero
	 * 
	 * @param string email email del usuario 
	 * @param string nomFich nombre del fichero que contiene la informacion
	 */
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
	
	/*Funcion que muestra la lista de cluster en un select de html
	 * 
	 * @param array clusters array de TCluster que contiene la informacion de los clusters
	 */
	function mostrarListaClusters($clusters)
	{
		
		echo "<select id='listaClusters' name='listaClusters' class='floatIz' size='15'>";
		
		for($i = 0; $i < count($clusters); $i++)
		{
			echo "<option value='cluster_".($i+1)."' onclick='mostrarCadenas(".$i.")'>Cluster ".($i+1)."</option>";
		}
		
		echo "</select>";
	}

	/*Funcion que devuelve la informacion de las cadenas de un emaprejamiento
	 * 
	 * @param int cluster cluster del que se quiere saber las cadenas que contiene
	 * @return array devuelve un array con la informacion de las cadenas del emparejamiento
	 */
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
	
	/*Funcion que aplica las modificaciones guardadas por el usuario en la lista de cluster y en las cadenas
	 * 
	 * @param array mods Array con la informacion de las modificacions a aplicar
	 */
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