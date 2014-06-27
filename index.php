<?php
    
	include_once("cabecera.inc");
	
?>


<form  action="javascript:calcularTablas()" method="post" onsubmit="return validar(this)">
			<fieldset id="datosTablas">
				
						<label for="tFichero">Fichero csv:</label>
						<input type="file" id="tFichero" name="tFichero"/>
						<span id="errorFichero" class="error"></span>
						<br />
						
						<label for="tEmail">Email:</label>
						<input type="text" id="tEmail" name="tEmail"/>
						<span id="errorEmail" class="error"></span>
						<br />
						
						<label for="cbSiglas">Incluir fichero de abreviaturas:</label>
						<input type="checkbox" id="cbSiglas" name="cbSiglas" onclick="javascript:checkAbreviaturas(this)"/>
						<br />
						
						<div id="divSiglas" style="display:none">
							<label for="tSiglas">Fichero de abreviaturas:</label>
							<input type="file" id="tSiglas" name="tSiglas"/>
							<span id="errorSiglas" class="error"></span>
							<br />
						</div>

						<label for="tPosicion">Posición de la cadena</label>
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
						
						
						<input type="submit" value="Enviar" />
			</fieldset>
</form>


<?php

	include_once("pie.inc");

?>