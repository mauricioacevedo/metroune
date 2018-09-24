<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	//	location.href=url+"?operacion=guardar&fecha="+fecha+"&dispositivo="+disp+"&evento="+evento+"&descripcion="+desc+"&responsable="+responsable;

	$fecha=$HTTP_GET_VARS["fecha"];
	$dispositivo=$HTTP_GET_VARS["dispositivo"];
	$evento=$HTTP_GET_VARS["evento"];
	$frecuencia=$HTTP_GET_VARS["frecuencia"];
	$unidadFrecuencia=$HTTP_GET_VARS["unidadFrecuencia"];
	$duracion=$HTTP_GET_VARS["duracion"];
	$unidadDuracion=$HTTP_GET_VARS["unidadDuracion"];
	$responsable=$HTTP_GET_VARS["responsable"];
	$recordatorio=$HTTP_GET_VARS["recordatorio"];
	$correos=$HTTP_GET_VARS["correos"];

	$operacion=$HTTP_GET_VARS["operacion"];




"&fecha="+fecha+"&dispositivo="+disp+"&evento="+evento+"&frecuencia="+frecuencia+"&unidadFrecuencia="+unidadFrecuencia+"&duracion="+duracion+"&unidadDuracion="+unidadDuracion+"&responsable="+responsable+"&recordatorio="+selectcorreo+"&correos="+correos;


	if($operacion=="guardar"){
		$sql="INSERT INTO programacion_automatica (fecha_inicio, dispositivo_id,frecuencia,unidad_frecuencia, evento, duracion,unidad_duracion, responsable,recordatorio,correos,ultima_fecha) VALUES ".
		"('$fecha', '$dispositivo','$frecuencia','$unidadFrecuencia', '$evento', '$duracion','$unidadDuracion', '$responsable','$recordatorio','$correos','$fecha')";



		$result = pg_query($sql);
		$oid=pg_GetLastOid($result);
		//SE GENERA LA FECHA DE LA PROXIMA ACTUALIZACION.

		$interval="";

		if($unidadFrecuencia=="D"){
			$interval="$frecuencia day";
		}elseif($unidadFrecuencia=="M"){
			$interval="$frecuencia month";
		}elseif($unidadFrecuencia=="Y"){
             $interval="$frecuencia year";
        }


		//se calcula la fecha del proximo mantenimiento/calibracion
		$sql= "update programacion_automatica set fecha_proxima=(date(fecha_inicio)+interval '$interval' )::date where oid=$oid";
		$result = pg_query($sql);

		$interval="";
		if($unidadDuracion=="M"){
			$interval="$duracion month";
		}elseif($unidadDuracion=="Y"){
			$interval="$duracion year";
		}elseif($unidadDuracion=="N"){
			$interval="1000 year";
		}

		//se calcula la fecha en la que termina la programacion

		$sql= "update programacion_automatica set fecha_fin=(date(fecha_inicio)+interval '$interval' )::date where oid=$oid";
		$result = pg_query($sql);

		//$sql="update programacion_automatica set fecha_fin=(date(fecha_inicio)+interval '')";
		echo "<html>";
		echo "<head><title>Resultado</title></head>";

		echo "<body>";
		echo "<p>&nbsp;</p>";
		echo "<p align='center'><strong><font size='+3'>Mensaje</font></strong></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'><font size='5'>Se creo la programacion satisfactoriamente.</font></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'>";
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:window.back();'>";
		echo "</p>";
		echo "</body>";
		echo "</html>";

		return;
	} elseif($operacion=="editar"){
		$id_programacion=$HTTP_GET_VARS["id_programacion"];

		$sql="select fecha_inicio,dispositivo_id,evento,frecuencia,unidad_frecuencia,duracion,unidad_duracion,responsable,recordatorio,correos from programacion_automatica where id=$id_programacion";
		$result = pg_query($sql);
		$rows=pg_numrows($result);

                if($rows>0){
		        $fecha=pg_result($result,0,0);
		        $dispositivo=pg_result($result,0,1);
		        $evento=pg_result($result,0,2);
		        $frecuencia=pg_result($result,0,3);
		        $unidadFrecuencia=pg_result($result,0,4);
		        $duracion=pg_result($result,0,5);
		        $unidadDuracion=pg_result($result,0,6);
		        $responsable=pg_result($result,0,7);
			$recordatorio=pg_result($result,0,8);
		        $correos=pg_result($result,0,9);

                }

	} elseif($operacion=="doEditar"){
		$id_programacion=$HTTP_GET_VARS["id_programacion"];
		$estado=$HTTP_GET_VARS["estado"];
		#INSERT INTO programacion_automatica (fecha_inicio, dispositivo_id,frecuencia,unidad_frecuencia, evento, duracion,unidad_duracion, responsable,recordatorio,correos)
		#"('$fecha', '$dispositivo','$frecuencia','$unidadFrecuencia', '$evento', '$duracion','$unidadDuracion', '$responsable','$recordatorio','$correos')"

		$sql="update programacion_automatica set fecha_inicio='$fecha', dispositivo_id='$dispositivo',frecuencia='$frecuencia',unidad_frecuencia='$unidadFrecuencia',evento='$evento',duracion='$duracion',unidad_duracion='$unidadDuracion', responsable='$responsable', recordatorio='$recordatorio',correos='$correos', estado='ACTIVO',ultima_fecha='$fecha'  where id=$id_programacion";
		//echo $sql;
                $result = pg_query($sql);
                $rows=pg_numrows($result);

                //SE GENERA LA FECHA DE LA PROXIMA ACTUALIZACION.
                $interval="";
                if($unidadFrecuencia=="D"){
                        $interval="$frecuencia day";
                }elseif($unidadFrecuencia=="M"){
                        $interval="$frecuencia month";
                }elseif($unidadFrecuencia=="Y"){
                        $interval="$frecuencia year";
                }


                //se calcula la fecha del proximo mantenimiento/calibracion
                $sql= "update programacion_automatica set fecha_proxima=(date(fecha_inicio)+interval '$interval' )::date where id=$id_programacion";
                $result = pg_query($sql);


				$interval="";
                if($unidadDuracion=="M"){
                        $interval="$duracion month";
                }elseif($unidadDuracion=="Y"){
                        $interval="$duracion year";
                }elseif($unidadDuracion=="N"){
                        $interval="1000 year";
                }

                //se calcula la fecha en la que termina la programacion

                $sql= "update programacion_automatica set fecha_fin=(date(fecha_inicio)+interval '$interval' )::date where id=$id_programacion";
                $result = pg_query($sql);



                echo "<html>";
                echo "<head><title>Resultado</title></head>";

                echo "<body>";
                echo "<p>&nbsp;</p>";
                echo "<p align='center'><strong><font size='+3'>Mensaje</font></strong></p>";
                echo "<p align='center'>&nbsp;</p>";
                echo "<p align='center'><font size='5'>Se modifico el evento satisfactoriamente.</font></p>";
                echo "<p align='center'>&nbsp;</p>";
                echo "<p align='center'>";
                echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:window.back();'>";
                echo "</p>";
                echo "</body>";
                echo "</html>";

		return;
	}



	$sql="SELECT nombre, id,serial FROM dispositivo order by serial ASC";
	$result = pg_query($sql);
	$rows=pg_numrows($result);

	//$fecha=date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");
	
	if($fecha=="")
		$fecha = date("Y-m-d");

?>


<?

function buscarDispositivoSel($dispositivos,$disp_find){
	$array_disp=explode(";",$dispositivos);
	$size=count($array_disp);
	//el menos uno es para que ignore el ultimo token ke siempre viene vacio
	//el token viene vacio porke el explode considera el espacio nullo como
	//un caracter a tener en cuenta como token
	for ($i=0;$i<$size-1;$i++){
		$dispo=$array_disp[$i];
		if($dispo==$disp_find){
			return "OK";
		}
	}
	return "FALSE";
}

?>

<html>
<head>
<?
	if ($operacion=="editar"){
		$titulo="Editar";
		$js="editar";
	} else {
		$titulo="Nueva";
		$js="guardar";
	}

?>
<title><? echo $titulo; ?> Evento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript">

function guardar(){

	var fecha=document.getElementById("fecha").value;
	//var disp=document.getElementById("dispositivo").value;
	var evento=document.getElementById("evento").value;

	var dsel=document.getElementById("dispositivosSeleccionados");
	var disp=getValues(dsel);

	var frecuencia=document.getElementById("frecuencia").value;
	var unidadFrecuencia=document.getElementById("unidadFrecuencia").value;

        var duracion=document.getElementById("duracion").value;
        var unidadDuracion=document.getElementById("unidadDuracion").value;

	var responsable=document.getElementById("responsable").value;

	var selectcorreo=document.getElementById("selectCorreos").value;
	var correos="";

	if(selectcorreo!="no"){
		correos=document.getElementById("correos").value;
	}


	var request="&fecha="+fecha+"&dispositivo="+disp+"&evento="+evento+"&frecuencia="+frecuencia+"&unidadFrecuencia="+unidadFrecuencia+"&duracion="+duracion+"&unidadDuracion="+unidadDuracion+"&responsable="+responsable+"&recordatorio="+selectcorreo+"&correos="+correos;
	var url="./nuevaProgramacion.php";


	//alert("DISPOSITIVOS: "+request);
	//return;

	location.href=url+"?operacion=guardar"+request;
}

function editar(){

	/*
        var fecha=document.getElementById("fecha").value;
        var disp=document.getElementById("dispositivo").value;
        var evento=document.getElementById("evento").value;
        var desc=document.getElementById("descripcion").value;
        var responsable=document.getElementById("responsable").value;
	var estado=document.getElementById("estado").value;
	*/

        var fecha=document.getElementById("fecha").value;
        //var disp=document.getElementById("dispositivo").value;
	
        var dsel=document.getElementById("dispositivosSeleccionados");
        var disp=getValues(dsel);
	
	//alert(disp);
	//return;

        var evento=document.getElementById("evento").value;

        var frecuencia=document.getElementById("frecuencia").value;
        var unidadFrecuencia=document.getElementById("unidadFrecuencia").value;

        var duracion=document.getElementById("duracion").value;
        var unidadDuracion=document.getElementById("unidadDuracion").value;

        var responsable=document.getElementById("responsable").value;

        var selectcorreo=document.getElementById("selectCorreos").value;
        var correos="";

        if(selectcorreo!="no"){
                correos=document.getElementById("correos").value;
        }


        var url="./nuevaProgramacion.php";
	var id = "<? echo $id_programacion; ?>";

	var request="&fecha="+fecha+"&dispositivo="+disp+"&evento="+evento+"&frecuencia="+frecuencia+"&unidadFrecuencia="+unidadFrecuencia+"&duracion="+duracion+"&unidadDuracion="+unidadDuracion+"&responsable="+responsable+"&recordatorio="+selectcorreo+"&correos="+correos+"&id_programacion="+id;
        var url="./nuevaProgramacion.php";

        location.href=url+"?operacion=doEditar"+request;

}

function validarCombo(){
	var selectCorreos=document.getElementById("selectCorreos").value;
	var campo=document.getElementById("correos");
	//alert("select: "+selectCorreos);
	if(selectCorreos=="no"){//no se quiere recordatorio
		//campo.readOnly="true";
		campo.disabled="true";
		campo.value="";
		//alert("Campo deshabilitar"+campo.disabled);
	}else{
		campo.disabled="";
		//campo.readOnly="";
		//alert("Campo habilitar"+campo.disabled);
	}
}




function getValues(sel){
	var seleccionados="";
        var kids = sel.childNodes;
        var numkids = kids.length;
        for (var i = 0; i < numkids; i++) {
		
		//if(kids[i].value==undefined) continue;
		if(isNaN(kids[i].value)) continue;
		seleccionados=seleccionados+kids[i].value+";";
		
		//alert(kids[i].value);
        }
	return seleccionados;
}

function addOption(){
	//esta funcion adhiere la opcion seleccionada en el combo de equipos seleccionados
	var selectdispositivo=document.getElementById("dispositivo");
	var dispositivosSeleccionados=document.getElementById("dispositivosSeleccionados");
        if(selectdispositivo.selectedIndex == -1){
                alert("Seleccione un Equipo.");
                return;
        }

	var text=selectdispositivo.options[selectdispositivo.selectedIndex].text;
	var value=selectdispositivo.options[selectdispositivo.selectedIndex].value;

	addSelectOption("dispositivosSeleccionados",value,text);
	removeSelectOption("dispositivo",value);


}


function addOption2(){
	//esta funcion retira la opcion del combo de seleccionados y lo pasa al general.

	var selectdispositivo=document.getElementById("dispositivo");
        var dispositivosSeleccionados=document.getElementById("dispositivosSeleccionados");
	//alert("length: "+dispositivosSeleccionados.selectedIndex);
	if(dispositivosSeleccionados.selectedIndex == -1){
		alert("Seleccione un Equipo.");
		return;
	}
        var text=dispositivosSeleccionados.options[dispositivosSeleccionados.selectedIndex].text;
        var value=dispositivosSeleccionados.options[dispositivosSeleccionados.selectedIndex].value;

        addSelectOption("dispositivo",value,text);
        removeSelectOption("dispositivosSeleccionados",value);

}



function addSelectOption(selectId, value, display) {
 	if (display == null) {
  		display = value;
 	}
    	var anOption = document.createElement('option');
    	anOption.value = value;
    	anOption.innerHTML = display;
    	document.getElementById(selectId).appendChild(anOption);
    	return anOption;
}

// removeSelectOption
//
// Remove the option with the specified value from the list of options
// in the selection list with the id specified
//
function removeSelectOption(selectId, value) {
 	var select = document.getElementById(selectId);
 	var kids = select.childNodes;
 	var numkids = kids.length;
 	for (var i = 0; i < numkids; i++) {
      		if (kids[i].value == value) {
   			select.removeChild(kids[i]);
   			break;
     		}
    	}
}





</script>

</head>

<body>
<p>&nbsp;</p>
<p align="center"><strong><font size="+3"><? echo $titulo; ?> Programacion</font></strong></p>
<table width="37%" border="0" cellspacing="2" cellpadding="1" align="center">
  <tr bgcolor="#FF0000">
    <td> <div align="center"><font color="#FFFFFF"><strong>Campo</strong></font></div></td>
    <td> <div align="center"><font color="#FFFFFF"><strong>Valor</strong></font></div></td>
  </tr>
  <tr>
    <td bgcolor="#DDDBDB"><strong>Fecha Inicio: </strong></td>
    <td> <div align="center">
        <input name="fecha" type="text" id="fecha" value="<? echo $fecha; ?>" size="10">
      </div></td>
  </tr>
  <!--tr>
    <td bgcolor="#DDDBDB"><strong>Equipo: </strong></td>
    <td> <div align="center">
        <select name="dispositivo" id="dispositivo">
          <?
  	  	for($i=0;$i<$rows;$i++){
			$j=$j+1;
			$nombre = pg_result($result,$i,0);
	    		$id = pg_result($result,$i,1);
			$serial = pg_result($result,$i,2);
			$sel="";
			if ( $id==$dispositivo){
				$sel="selected";
			}

			echo "<option value='".$id."' ".$sel.">".$serial." - ".$nombre."</option>\n";
		}
  ?>
        </select>
      </div></td>
  </tr-->







    <tr>
      <td bgcolor="#DDDBDB"><strong>Equipos Disponibles: </strong></td>
      <td> <div align="center" valign="center">
          <select name="dispositivo" id="dispositivo">
            <?
    	  	for($i=0;$i<$rows;$i++){
  			$j=$j+1;
  			$nombre = pg_result($result,$i,0);
  	    	$id = pg_result($result,$i,1);
	    	$serial = pg_result($result,$i,2);
  			$sel="";

  			$busq=buscarDispositivoSel($dispositivo,$id);

  			if($busq=="OK"){//fue encontrado, no lo coloco aca
  				continue;
  			}

  			//echo $dispositivo;
  			//if ( $id==$dispositivo){
  			//	$sel="selected";
  			//}

  			echo "<option value='".$id."' ".$sel.">".$serial." - ".$nombre."</option>\n";
  		}
    ?>
          </select>
  	&nbsp;<input type="button" value=">>" onclick="javascript:addOption();">
        </div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><strong>Equipos Seleccionados: </strong></td>
      <td> <div align="center">
          <select name="dispositivosSeleccionados" id="dispositivosSeleccionados" size="5">
  	<? 
		if($operacion=="editar"){
			$array_disp=explode(";",$dispositivo);
		        $size=count($array_disp);
        		//el menos uno es para que ignore el ultimo token ke siempre viene vacio
		        //el token viene vacio porke el explode considera el espacio nullo como
		        //un caracter a tener en cuenta como token
		        for ($i=0;$i<$size-1;$i++){
		                $dispo=$array_disp[$i];

        			$sql="SELECT nombre, id,serial FROM dispositivo where id=$dispo";
			        $result = pg_query($sql);
			        $rows=pg_numrows($result);
				$nombre = pg_result($result,0,0);
		                $id = pg_result($result,0,1);
                		$serial = pg_result($result,0,2);
				
				echo "<option value='".$id."' >".$serial." - ".$nombre."</option>\n";

	                }
	        }


	?>

          </select>
  	&nbsp;<input type="button" value=">>" onclick="javascript:addOption2();">
        </div></td>
  </tr>

















  <tr>
    <td bgcolor="#DDDBDB"><strong>Evento:</strong></td>
    <td> <div align="center">
        <select name="evento" id="evento">
          <option value="MANTENIMIENTO" <? if($evento=='MANTENIMIENTO') echo "selected"; ?>>Mantenimiento</option>
          <option value="CALIBRACION" <? if($evento=='CALIBRACION') echo "selected"; ?>>Calibración</option>
        </select>
      </div></td>
  </tr>
  <tr>
    <td bgcolor="#DDDBDB"><strong>Frecuencia</strong></td>
    <td> <div align="center">
        <input type="text" name="frecuencia" id="frecuencia" size="3" value="<? echo $frecuencia; ?>">
	&nbsp;
	<select name="unidadFrecuencia" id="unidadFrecuencia">
          <option value="D" <? if($unidadFrecuencia=='D') echo "selected"; ?>>Dias</option>
          <option value="M" <? if($unidadFrecuencia=='M') echo "selected"; ?>>Meses</option>
          <option value="Y" <? if($unidadFrecuencia=='Y') echo "selected"; ?>>A&ntilde;os</option>
        </select>

      </div></td>
  </tr>
  <tr>
    <td bgcolor="#DDDBDB"><strong>Duracion: </strong></td>
    <td><div align="center">

	<input type="text" name="duracion" id="duracion" size="3" value="<? echo $duracion; ?>">
        &nbsp;
    <select name="unidadDuracion" id="unidadDuracion">
          <option value="M" <? if($unidadDuracion=='M') echo "selected"; ?>>Meses</option>
          <option value="Y" <? if($unidadDuracion=='Y') echo "selected"; ?>>A&ntilde;os</option>
          <option value="N" <? if($unidadDuracion=='N') echo "selected"; ?>>Indefinido</option>
    </select>
	</div></td>
  </tr>
  <tr>
    <td bgcolor="#DDDBDB"><strong>Responsable: </strong></td>
    <td> <div align="center">
        <input name="responsable" type="text" id="responsable" value="<? echo $responsable; ?>">
      </div></td>
  </tr>

  <tr>
    <td bgcolor="#DDDBDB"><strong>Recordatorio: </strong></td>
    <td> <div align="center">

	<select name="selectCorreos" id="selectCorreos" onchange="javascript:validarCombo();">
          <option value="no" <? if($recordatorio=='no') echo "selected"; ?>>Sin mensaje</option>
          <option value="0" <? if($recordatorio=='0') echo "selected"; ?>>El mismo dia</option>
          <option value="1" <? if($recordatorio=='1') echo "selected"; ?>>1 dia antes</option>
          <option value="3" <? if($recordatorio=='3') echo "selected"; ?>>3 dias antes</option>
          <option value="5" <? if($recordatorio=='5') echo "selected"; ?>>5 dias antes</option>
        </select>


        <input name="correos" type="text" id="correos" value="<? echo $correos; ?>">
      </div></td>
  </tr>

</table>
<br>
<center>
  <input name="boton" type="button" id="boton2" value="Guardar" onClick="javascript:<? echo $js; ?>();">
</center>
</body>
<!--script language="JavaScript">update_fecha();</script-->
</html>
