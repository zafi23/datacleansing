<?php
    
	include_once("cabecera.inc");
	include_once("lib/funciones.inc");


?>

<div id="contenido">

<?php
	if(!isset($_GET['e']) || !isset($_GET['f']))
	{
?>
	
	<p>Por favor, acceda a la página desde la url proporcionada en el email</p>
	
	
<?php
	}
	else 
	{	
?>

	<div id="tablasDatos">


<?php
		$email = trim($_GET['e']);
		$fichero = trim($_GET['f']);
		
		cargarClusters($email,$fichero);
		
		$cads = unserialize($_SESSION["cads"]);
		$clusters = unserialize($_SESSION["clusters"]);
		
		mostrarListaClusters($clusters);
		
		$cadenasCluster = cadenasCluster(0);
		
		echo "<input type='hidden' id='posicion' value='".trim($cads[0]->getPos())."' />" ;
		echo "<input type='hidden' id='cluster' value='0' />" ;
		
		
		echo "<select id='cCads' name='cCads' class='floatIz' size='15'>";
		
		for($i = 0; $i < count($cadenasCluster); $i++)
		{
			echo "<option value='".$cadenasCluster[$i][1]."' onclick='javascript:mostrarDatos(this)'>".$cadenasCluster[$i][0]."</option>";
						
		}
		
		echo "</select>";
		
		echo "<label for='centroC'>Cadena centro:</label>";
		echo "<input type='text' id='centroC' name='centroC' class='floatDer' value='".$cads[$clusters[0]->centro()]->getC()."' onchange='guardarModCentro()'/>";
		echo "<span id='errorCentroC' class='error'></span>";
		echo "<input type='hidden' id='indiceC' value='".$clusters[0]->centro()."' />" ;
	
?>

		<fieldset id="objMod" style="display:none">
			<legend>Datos de la cadena</legend>
			<input type="text" id="cadMod"/>
			<span id="errorCadMod" class="error"></span>
			<br />
			
			<label for="qCad">Quitar cadena de cluster:</label>
			<input type="checkbox" id="qCad" name="qCad" />
			<br />
			
			<label for="bCad">Borrar cadena del fichero:</label>
			<input type="checkbox" id="bCad" name="bCad" />
			<br />
			
			<input type="button" value="Modificar" onclick="javascript:guardarMods()" />		
		</fieldset>
	
		<form action="javascript:guardarFichero()" method="post" class='floatIz'>
			<input type="radio" name="tipoGuardado" value="0" checked="checked" /><span>Mantener todos los registros de los clusters</span> 
			</br>               
			<input type="radio" name="tipoGuardado" value="1" /><span> Mantener registros y modificarlos por el registro centro</span>
			</br>
			<input type="radio" name="tipoGuardado" value="2" /><span> Borrar filas de cluster y dejar el registro centro</span> 
			<br />
			<input type="submit" value="Guardar Fichero" />
		</form>
	</div>
	
	<div id="msgEnviado" style="display: none">
		Mensaje enviado correctamente a su correo, gracias por utilizar nuestro servicio
	</div>
	
<?php
	}

?>
</div>
<?php
	include_once("pie.inc");

?>