<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	//	location.href=url+"?operacion=guardar&fecha="+fecha+"&dispositivo="+disp+"&evento="+evento+"&descripcion="+desc+"&responsable="+responsable;

	$fecha=$HTTP_GET_VARS["fecha"];
	$dispositivo=$HTTP_GET_VARS["dispositivos"];
	$evento=$HTTP_GET_VARS["evento"];
	$observaciones=$HTTP_GET_VARS["observaciones"];
	$responsable=$HTTP_GET_VARS["responsable"];

	
	$fecha=$fecha." ".date("H").":".date("i").":".date("s");


	$operacion=$HTTP_GET_VARS["operacion"];

	if($operacion=="guardar"){
		//fecha 	estado 	tipo_evento 	responsable_evento 	observaciones

		$sql="select nextval('sec_eventos')";
		$result = pg_query($sql);
		$tam=pg_numrows($result);
		$id_evento="0";
		if($tam>0){
			$id_evento=pg_result($result,0,0);
		}else{
			echo "No se pudo generar un nuevo numero de evento. Revisar..";
			return;
		}


		$sql="INSERT INTO eventos (id, fecha, tipo_evento, observaciones, responsable_evento) VALUES ".
		"($id_evento,'$fecha', '$evento', '$observaciones','$responsable')";
		$result = pg_query($sql);

		//ahora divido la cadena con la informacion de los equipos para almacenarlos en la tabla evento_por_dispositivo
		
		$idEquipos=split(";",$dispositivo);

		$size=count($idEquipos);

		for($i=0; $i < $size ; $i++){
			$id=$idEquipos[$i];
			
			if($id==""){
				continue;
			}

			$sql= "select proxima_fecha_calibracion,fecha_ultima_calibracion from dispositivo where id=$id";
			$result2=pg_query($sql);
			$rows=pg_numrows($result2);
			
			if($rows>0){
                $proxima_fecha_calibracion = pg_result($result2,0,0);
                $fecha_ultima_calibracion = pg_result($result2,0,1);
			}else{
				//no se encontro el equipo
				echo "Sin informacion para el equipo con ID: $id";
				continue;
			}


			$sql="insert into evento_por_dispositivo(id_evento,id_dispositivo,proxima_fecha_calibracion,ultima_fecha_calibracion) values ($id_evento,$id,'$proxima_fecha_calibracion','$fecha_ultima_calibracion')";
			pg_query($sql);
			//echo "<br>SQL: $sql";
		}		

		echo "<html>";
		echo "<head><title>Resultado</title></head>";

		echo "<body>";
		echo "<p>&nbsp;</p>";
		echo "<p align='center'><strong><font size='+3'>Mensaje</font></strong></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'><font size='5'>Se creo el evento satisfactoriamente.</font></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'>";
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:window.back();'>";
		echo "</p>";
		echo "</body>";
		echo "</html>";

		return;
	} elseif($operacion=="editar"){
		$id_evento=$HTTP_GET_VARS["id_evento"];

		$sql="select dispositivo_id,evento,descripcion,responsable,estado,costo,proveedor_evento,variables_analizadas,fecha from itinerario_eventos where id=$id_evento";

		$result = pg_query($sql);
		$rows=pg_numrows($result);

                if($rows>0){
	         	$dispositivo = pg_result($result,0,0);
		        $evento = pg_result($result,0,1);
			$descripcion =pg_result($result,0,2);
			$responsable =pg_result($result,0,3);
			$estado =pg_result($result,0,4);
			$costo=pg_result($result,0,5);
			$proveedor=pg_result($result,0,6);
			$variables=pg_result($result,0,7);
			$fecha_ini=pg_result($result,0,8);
                }

	} elseif($operacion=="doEditar"){
		$id_evento=$HTTP_GET_VARS["id_evento"];
		$estado=$HTTP_GET_VARS["estado"];
		$sql="update itinerario_eventos set fecha='$fecha', dispositivo_id='$dispositivo',evento='$evento',descripcion='$descripcion', responsable='$responsable', estado='$estado', costo='$costo',proveedor_evento='$proveedor',variables_analizadas='$variables', fecha_final=to_char(now(),'YYYY-MM-DD HH24:MI:SS')  where id=$id_evento";

                $result = pg_query($sql);
                $rows=pg_numrows($result);


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



	$sql="SELECT nombre, id,serial FROM dispositivo order by nombre ASC";
	$result = pg_query($sql);
	$rows=pg_numrows($result);

	//$fecha=date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");
	$fecha=date("Y")."-".date("m")."-".date("d");

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
		$titulo="Nuevo";
		$js="guardar";
	}

?>
<title><? echo $titulo; ?> Evento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript">


function guardar(){

	var fecha=document.getElementById("fechaIni").value;
	var dsel=document.getElementById("dispositivosSeleccionados");
	var evento=document.getElementById("evento").value;
	var observaciones=document.getElementById("observaciones").value;
	var responsable=document.getElementById("responsable").value;

	var disp=getValues(dsel);
	
	var url="./nuevoEvento.php";

	location.href=url+"?operacion=guardar&fecha="+fecha+"&dispositivos="+disp+"&evento="+evento+"&observaciones="+observaciones+"&responsable="+responsable;
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


function editar(){

        var fecha=document.getElementById("fecha").value;
        //var disp=document.getElementById("dispositivo").value;
        var evento=document.getElementById("evento").value;
        var desc=document.getElementById("descripcion").value;
        var responsable=document.getElementById("responsable").value;
	var estado=document.getElementById("estado").value;
	
	var dsel=document.getElementById("dispositivosSeleccionados");
	var disp=getValues(dsel);

        var costo=document.getElementById("costo").value;
        var proveedor=document.getElementById("proveedor").value;
        var variables=document.getElementById("variables").value;

        var url="./nuevoEvento.php";
	var id = "<? echo $id_evento; ?>";
        location.href=url+"?operacion=doEditar&id_evento="+id+"&fecha="+fecha+"&dispositivo="+disp+"&evento="+evento+"&descripcion="+desc+"&responsable="+responsable+"&estado="+estado+"&costo="+costo+"&proveedor="+proveedor+"&variables="+variables;
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

<script language="JavaScript" src="calendar.js" type="text/javascript"></script>
<script language="JavaScript">

addCalendar("DateIni", "calIni", "fechaIni", "forma1");
addCalendar("DateFin", "calFin", "fechaFin", "forma1");

</script>



</head>

<body>
<p align="center"><strong><font size="+3"><? echo $titulo; ?> Evento</font></strong></p>

<form name='forma1'>
<table width="80%" border="0" cellspacing="2" cellpadding="1" align="center">
  <tr bgcolor="#FF0000">
    <td> <div align="center"><font color="#FFFFFF"><strong>Campo</strong></font></div></td>
    <td> <div align="center"><font color="#FFFFFF"><strong>Valor</strong></font></div></td>
  </tr>

<tr>

	<td bgcolor="#DDDBDB" align='center'><strong>Fecha de Generacion del Evento</strong></td>
	<td align='center'><input type="text" name="fechaIni" id="fechaIni" value="" maxlength="10" size="10" style="background-color: rgb(255, 255, 160);">
<span title="Click Para Abrir El Calendario">
	<a href="javascript:showCal('DateIni', 200, 0)" style="color: black;">(editar)</a>
	</span>
<div id="calIni" style="position:relative; visibility: hidden;" >
</td>

</tr>

<tr>

	<td bgcolor="#DDDBDB" align='center'><strong>Responsable</strong></td>
	<td align='center'> <input id="responsable"type="text" value="ISABEL ARBOLEDA"></td>

</tr>

<tr>

        <td bgcolor="#DDDBDB" align='center'><strong>Observaciones</strong></td>
		<td align='center'><textarea id="observaciones" rows='6' cols='40'></textarea></td>

</tr>

  <tr>
    <td bgcolor="#DDDBDB" align='center'><strong>Equipos Disponibles: </strong></td>
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

			echo "<option value='".$id."' ".$sel.">".$serial." - ".$nombre."i</option>\n";
		}
  ?>
        </select>
	&nbsp;<input type="button" value=">>" onclick="javascript:addOption();">
      </div></td>
  </tr>
  <tr>
    <td bgcolor="#DDDBDB" align='center'><strong>Equipos Seleccionados: </strong></td>
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
    <td bgcolor="#DDDBDB" align='center'><strong>Evento:</strong></td>
    <td> <div align="center">
        <select name="evento" id="evento">
          <option value="CALIBRACION">Calibración</option>
          <option value="MANTENIMIENTO">Mantenimiento</option>
          <option value="REPARACION">Reparación</option>

        </select>
      </div></td>
  </tr>
</table>
</form>
<br>
<center>
  <input name="boton" type="button" id="boton2" value="Guardar" onClick="javascript:<? echo $js; ?>();">
</center>
</body>
<script language="JavaScript">update_fecha();</script>
</html>
