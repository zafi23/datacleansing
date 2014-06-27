<?php
    
	include_once("cabecera.inc");
	include_once("lib/funciones.inc");

	
	if(!isset($_GET['e']) || !isset($_GET['f']))
	{
?>
	
	<p>Por favor, acceda a la página desde la url proporcionada en el email</p>
	
	
<?php
	}
	else 
	{
		$email = trim($_GET['e']);
		$fichero = trim($_GET['f']);
		
		cargarClusters($email,$fichero);
		
		$cads = unserialize($_SESSION["cads"]);
		$clusters = unserialize($_SESSION["clusters"]);
		
		mostrarListaClusters($clusters);
		
		$cadenasCluster = cadenasCluster(0);
		
		echo "<input type='hidden' id='posicion' value='".trim($cads[0]->getPos())."' />" ;
		echo "<input type='hidden' id='cluster' value='0' />" ;
		
		
		echo "<select id='cCads' name='cCads' size='20'>";
		
		for($i = 0; $i < count($cadenasCluster); $i++)
		{
			echo "<option value='".$cadenasCluster[$i][1]."' onclick='javascript:mostrarDatos(this)'>".$cadenasCluster[$i][0]."</option>";
						
		}
		
		echo "</select>";
		
		echo "<input type='textbox' id='centroC' value='".$cads[$clusters[0]->centro()]->getC()."'/>"
	
?>

	<fieldset id="objMod" style="display:none">
		<input type="text" id="cadMod"/>
		<span id="errorCadMod" class="error"></span>
		<br />
		
		<label for="qCad">Quitar cadena de cluster:</label>
		<input type="checkbox" id="qCad" name="qCad" />
		<br />

		<input type="button" value="Modificar" onclick="javascript:guardarMods()" />		
	</fieldset>

	<form action="javascript:guardarFichero()" method="post">
		<input type="radio" name="tipoGuardado" value="0" checked>Mantener todos los registros de los clusters
		<input type="radio" name="tipoGuardado" value="1">Borrar filas de cluster y dejar el registro centro
		
		</br>
		<input type="submit" value="Guardar Fichero" />
	</form>


<?php
	}
	
	include_once("pie.inc");

?>