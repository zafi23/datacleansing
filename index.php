<?php
    
	include_once("cabecera.inc");
	
?>

<div id="contenido">

	<div id="formFichero">
							
		<form  action="javascript:calcularTablas()" method="post" onsubmit="return validar(this)">

						
								<label for="tFichero">Fichero csv:</label>
								<input type="file" id="tFichero" name="tFichero"/>
								<span id="errorFichero" class="error"></span>
								<br />
								
								<label for="tEmail">Email:</label>
								<input type="text" id="tEmail" name="tEmail"/>
								<span id="errorEmail" class="error"></span>
								<br />
								<fieldset id="datosTablas">
									<legend>Opciones:</legend>
									<label for="cbSiglas">Incluir fichero de abreviaturas:</label>
									<input type="checkbox" id="cbSiglas" name="cbSiglas" onclick="javascript:checkAbreviaturas(this)"/>
									<br />
									
									<div id="divSiglas" style="display:none">
										<label for="tSiglas">Fichero de abreviaturas:</label>
										<input type="file" id="tSiglas" name="tSiglas"/>
										<span id="errorSiglas" class="error"></span>
										<br />
									</div>
			
									<label for="tPosicion">Posición de la cadena:</label>
									<input type="text" id="tPosicion" name="tPosicion"/>
									<span id="errorPosicion" class="error"></span>
									<br />
									
									<label for="cbAlpha">Incluir valor alpha:</label>
									<input type="checkbox" id="cbAlpha" name="cbAlpha" onclick="javascript:checkAlpha(this)"/>
									<br />
									
									<div id="divAlpha" style="display: none">
										<label for="tAlpha">Alfa</label>
										<input type="text" id="tAlpha" name="tAlpha"/>
										<span id="errorAlpha" class="error"></span>
										<br />	
									</div>
								</fieldset>
								
								<input type="submit" id="btnEnviar" value="Enviar" />
		</form>
		
	</div>
	
	<div id="msgEnviado" style="display: none">
		Su peticición se está analizando, recbirá un email cuando se termine de procesar
	</div>

</div>
<?php

	include_once("pie.inc");

?>