<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	//	location.href=url+"?operacion=guardar&fecha="+fecha+"&dispositivo="+disp+"&evento="+evento+"&descripcion="+desc+"&responsable="+responsable;

	$responsable=$HTTP_GET_VARS["responsable"];
	//operacion=guardarInfoEvento&responsable="+responsable+"&observaciones="+observaciones+"&estado="+estado
	$observaciones=$HTTP_GET_VARS["observaciones"];
	$estado=$HTTP_GET_VARS["estado"];
	$evento=$HTTP_GET_VARS["evento"];
	$operacion=$HTTP_GET_VARS["operacion"];

	if($operacion=="guardarInfoEvento"){
		$id_evento=$HTTP_GET_VARS["id_evento"];
		$sql="update eventos set tipo_evento='$evento', responsable_evento='$responsable',observaciones='$observaciones',estado='$estado' where id=$id_evento";

		$result = pg_query($sql);

		echo "<html>";
		echo "<head><title>Resultado</title></head>";

		echo "<body>";
		echo "<p>&nbsp;</p>";
		echo "<p align='center'><strong><font size='+3'>Mensaje</font></strong></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'><font size='5'>Se actualizo el evento satisfactoriamente.</font></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'>";
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:window.history.go(-1);'>";
		echo "</p>";
		echo "</body>";
		echo "</html>";

		return;
	} elseif($operacion=="editar"){
		$id_evento=$HTTP_GET_VARS["id_evento"];

		$sql="select fecha,estado,responsable_evento,tipo_evento,observaciones from eventos where id=$id_evento";

		$result = pg_query($sql);
		$rows=pg_numrows($result);

                if($rows>0){
	         	$fecha = pg_result($result,0,0);
		        $estado = pg_result($result,0,1);
			$responsable =pg_result($result,0,2);
			$evento =pg_result($result,0,3);
			$observaciones =pg_result($result,0,4);
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

	} else{
        
		//SOLICITUDES POR POST
		$operacionPost=$_POST["operationHidden"];


        if($operacionPost=="doModificarEquipoCalibracion"){
				$id_evento= $HTTP_POST_VARS["id_evento"];
                $id_equipo=$HTTP_POST_VARS["id_equipo"];
                $fecha_salida_operacion=$HTTP_POST_VARS["fecha_salida_operacion"];
                $fecha_envio_equipo=$HTTP_POST_VARS["fecha_envio_equipo"];
                $fecha_entrega_equipo=$HTTP_POST_VARS["fecha_entrega_equipo"];
                $fecha_puesta_produccion=$HTTP_POST_VARS["fecha_puesta_produccion"];
                $novedades=$HTTP_POST_VARS["novedades"];
                $costo=$HTTP_POST_VARS["costo"];
		$proxima_fecha_calibracion=$HTTP_POST_VARS["proxima_fecha_calibracion"];
		$ultima_fecha_calibracion=$HTTP_POST_VARS["ultima_fecha_calibracion"];
		$borrarArchivo=$HTTP_POST_VARS["borrarArchivo2"];	
		//aca, antes del update tengo que verificar que: si colocan una fecha de retiro de operacion, el equipo debe estar en programado, coloco el estado del equipo en 'EN CALIBRACION'
		$sql="select estado from dispositivo where id=$id_equipo";
		
		echo "ultima_fecha_calibracion---: $ultima_fecha_calibracion";		
		//return;

		$result = pg_query($sql);
		$rows=pg_numrows($result);
		
		$estado="";

		if($rows>0){
			$estado2=pg_result($result,0,0);
		}else{
			echo "<font color='red'>Posible fallo</font>, no hay registros con  id_evento =$id_evento y id_dispositivo=$id_equipo";
		}
		
		//las fechas deben estar generadas con el formato yyyy-mm-dd

		if($fecha_salida_operacion!=""){
			$estado="PROGRAMADO INACTIVO";
		}if($fecha_envio_equipo!=""){
			$estado="EN CALIBRACION";
		}else{
			//no actualizo el estado.
			$estado="";
		}
			
		if($fecha_salida_operacion!="" && $fecha_envio_equipo!="" && $fecha_entrega_equipo!="" && $fecha_puesta_produccion!=""){
                        $estado="ACTIVO";
                }
			
		//aca hago las actividades para insertar el archivo con la certificacion
		$uploaddir = '/var/www/html/metroune/upload_files/';
		//$uploaddir = '/tmp/';
		$name_file = basename($_FILES['userfile']['name']);
	
		$end_name_file=date("Y").date("m").date("d").date("H").date("i").date("s");
	
		if($name_file==""){
			$no_file="yes";
		}
		$name_file3 = $end_name_file."-_-".$name_file;
		$size = $_FILES['userfile']['size'];
		$uploadfile = $uploaddir.$name_file3;
		$fechaUpload=date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");

		$mensajeError="";

		if(file_exists($uploadfile)){
			$mensajeError= "archivo <font color='red' size='4'> $name_file </font> ya existe, intente con un nombre diferente.";
		}elseif (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		{   // echo "File is valid, and was successfully uploaded.\n";
        	//echo "se cargo el archivo";
		}else {
	   		$mensajeError= "Tamaño de archivo superior al permitido por el servidor, $name_file= $size bytes!\n\n";   
		}
		$name_file=$name_file3;
/*
		$getCounter="select nextval('sec_files')";
		$result2=pg_query($getCounter);
		
		$identificador_file=pg_result($result2,0,0);
		
		$insertFile = "insert into files (id,nombre,size,fecha_carga,file) values ($identificador_file,'$name_file','$size','$fecha', lo_import('$uploadfile'))"
;
		echo $insertFile;
		$rta=exec("chmod 777 $uploadfile");
		echo "<br> respuesta: $rta";
		$res = pg_query($insertFile);

		

		echo "nombre archivo: $name_file, tamaño: $size";
		return;
*/

		if($mensajeError!="" || $no_file=="yes"){
			$name_file2="";
		}else {
			$name_file2=",id_file='$name_file' ";
		}
		
		if($name_file2==""){
			if($borrarArchivo=="yes"){
				//1. borro de la db
				$name_file2=",id_file='$name_file' ";
				//2. borrar archivo del disco
				$sql_namefile="select id_file from evento_por_dispositivo where id_evento=$id_evento and id_dispositivo=$id_equipo";
				$result = pg_query($sql_namefile);
				$rows=pg_numrows($result);

                if($rows>0){
               		$name_file_db = pg_result($result,0,0);
					//echo "Deleting: [".$uploaddir.$name_file_db."]";
					unlink($uploaddir.$name_file_db);
                }
				
			}
		}



                $sql="update evento_por_dispositivo set fecha_salida_operacion='$fecha_salida_operacion', fecha_envio_equipo='$fecha_envio_equipo', fecha_entrega_equipo='$fecha_entrega_equipo', fecha_puesta_produccion='$fecha_puesta_produccion', novedades='$novedades', costo='$costo', ultima_fecha_calibracion='$ultima_fecha_calibracion', proxima_fecha_calibracion='$proxima_fecha_calibracion' $name_file2 where id_evento=$id_evento and id_dispositivo=$id_equipo";

		//echo $sql;
	
		$result = pg_query($sql);

		//actualizo el estado del dispositivo si da pie a ello..

		if($estado!=$estado2&&$estado!=""){

			 //si el equipo cambio al estado ACTIVO es porque ya se hizo la calibracion exitosamente, asigno la proxima_fecha de calibracion.
                        //en este momento en proxima_fecha_calibracion esta la ultima_fecha_calibracion
                        $updateFechas="";
                        if($estado=="ACTIVO"){
                                $updateFechas=", fecha_ultima_calibracion=$proxima_fecha_calibracion ";
                        }


			if($id_evento != '1'){
				$sql="update dispositivo set estado='$estado' $updateFechas where id=$id_equipo";
				$result = pg_query($sql);
			}
		}
		
		//actualizo las fechas en el registro de dispositivo.
		if($id_evento != '1'){
			
            //2011-06-28: Isabel pide cambiar la funcionalidad para que siempre se actualice la ultima fecha de
            //            calibracion sobre la informacion general del dispositivo:
			$sql="update dispositivo set fecha_salida_operacion='$fecha_salida_operacion', fecha_envio_equipo='$fecha_envio_equipo', fecha_entrega_equipo='$fecha_entrega_equipo', fecha_puesta_produccion='$fecha_puesta_produccion', fecha_ultima_calibracion='$proxima_fecha_calibracion' where id=$id_equipo";
			echo $sql;	
			$result = pg_query($sql);
		}
                echo "<html>";
                echo "<head><title>Resultado</title></head>";

                echo "<body>";
                echo "<p>&nbsp;</p>";
                echo "<p align='center'><strong><font size='+3'>Mensaje</font></strong></p>";
                echo "<p align='center'>&nbsp;</p>";
				
				if($mensajeError!="" && $no_file!="yes"){
					echo "<p align='center'><font size='5'>Se modifico el evento, pero ocurrieron problemas con la carga del archivo:<br>$mensajeError</font></p>";
					
				} else {
	                echo "<p align='center'><font size='5'>Se modifico el evento satisfactoriamente.</font></p>";
				}
                echo "<p align='center'>&nbsp;</p>";
                echo "<p align='center'>";
                echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:window.history.go(-1);'>";
                echo "</p>";
                echo "</body>";
                echo "</html>";

                return;
	
	}

}//end del else

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
		$titulo="Edicion";
		$js="editar";
	} else {
		$titulo="Nuevo";
		$js="guardar";
	}

?>
<title><? echo $titulo; ?> Evento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script type="text/javascript" src="../javascript/modalbox/lib/prototype.js"></script>
        <script type="text/javascript" src="../javascript/modalbox/lib/scriptaculous.js?load=effects"></script>

        <script type="text/javascript" src="../javascript/modalbox/modalbox.js"></script>
        <link rel="stylesheet" href="../javascript/modalbox/modalbox.css" type="text/css" />

        <style type="text/css" media="screen">
                html, body {
                        width: 100%;
                        height: 100%;
                }
                #MB_loading {
                        font-size: 13px;
                }
                #errmsg {
                        margin: 1em;
                        padding: 1em;
                        color: #C30;
                        background-color: #FCC;
                        border: 1px solid #F00;
                }
        </style>

<script language="JavaScript">


function guardarInfo(){

	var responsable=document.getElementById("responsable").value;
	var observaciones=document.getElementById("observaciones").value;

	var estado=document.getElementById("selectEstado").value;
        //var evento=document.getElementById("selectEvento").value;
	var evento="CALIBRACION";
	var id_evento="<? echo $id_evento; ?>";	
	var url="./nuevoEvento2.php";

	location.href=url+"?operacion=guardarInfoEvento&responsable="+responsable+"&observaciones="+observaciones+"&estado="+estado+"&evento="+evento+"&id_evento="+id_evento;
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

        var url="./nuevoEvento2.php";
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


	function editarEquipo(equipo_id){
		Modalbox.show('modificarCalibracionEquipo.php?id_equipo='+equipo_id+"&evento_id=<? echo $id_evento; ?>", {title: 'Modificar calibracion de Equipo.',height: 450, width: 800 });
	}

	function guardarInfoEquiposCalibracion(id_equipo){
                
                var fecha_salida_operacion=document.getElementById("fecha_salida_operacion").value;
                var fecha_envio_equipo=document.getElementById("fecha_envio_equipo").value;
                var fecha_entrega_equipo=document.getElementById("fecha_entrega_equipo").value;
                var fecha_puesta_produccion=document.getElementById("fecha_puesta_produccion").value;
                var novedades=document.getElementById("novedades").value;
                var costo=document.getElementById("costo").value;
		var ultima_fecha_calibracion=document.getElementById("ultima_fecha_calibracion").value;
		var proxima_fecha_calibracion=document.getElementById("proxima_fecha_calibracion").value;
		var borrarArchivo=document.getElementById("borrarArchivo");
		var borrarArchivo2=document.getElementById("borrarArchivo2");
		if(borrarArchivo != null){	
			if(borrarArchivo.checked){
				borrarArchivo2.value="yes";
			}else{
				borrarArchivo2.value="no";
			}
		}
		if(fecha_salida_operacion!=""){
			if(!ValidateForm(fecha_salida_operacion)){
				document.getElementById("fecha_salida_operacion").focus();
				return;
			}
		}

		if(fecha_envio_equipo!=""){
                	if(!ValidateForm(fecha_envio_equipo)){
                        	document.getElementById("fecha_envio_equipo").focus();
                        	return;
                	}
		}

		if(fecha_entrega_equipo!=""){
			if(!ValidateForm(fecha_entrega_equipo)){
        	                document.getElementById("fecha_entrega_equipo").focus();
                	        return;
                	}
		}

		
		if(fecha_puesta_produccion!=""){
	                if(!ValidateForm(fecha_puesta_produccion)){
        	                document.getElementById("fecha_puesta_produccion").focus();
                	        return;
                	}
		}
		
		if(ultima_fecha_calibracion!=""){
	                if(!ValidateForm(ultima_fecha_calibracion)){
        	                document.getElementById("ultima_fecha_calibracion").focus();
                	        return;
                	}
		}

                if(proxima_fecha_calibracion!=""){
			if(!ValidateForm(proxima_fecha_calibracion)){
        	                document.getElementById("proxima_fecha_calibracion").focus();
                	        return;
                	}
		}
		
		//validacion de jerarquia de fechas: si la ultima fecha esta presente, las anteriores tambien deben estar

		if(fecha_puesta_produccion!=""){
			if(fecha_entrega_equipo!=""){
				if(fecha_envio_equipo!=""){
					if(fecha_salida_operacion!=""){
						//caso feliz, dejo continuar
					}else{
						alert("Falta la Fecha Retiro de Operación");
						return;
					}
				}else{
                                        alert("Falta la Fecha de Entrega al Laboratorio de Calibración");
                                       return;

				}
			} else {
				alert("Falta la Fecha de Recepción desde el Laboratorio de Calibración");
				return;
			}
		} else {
			if(fecha_entrega_equipo!=""){
                                if(fecha_envio_equipo!=""){
                                        if(fecha_salida_operacion!=""){
                                                //caso feliz, dejo continuar
                                        }else{
                                                alert("Falta la Fecha Retiro de Operación");
                                                return;
                                        }
                                }else{
                                        alert("Falta la Fecha de Entrega al Laboratorio de Calibración");
                                       return;

                                }
                        } else {

				                          
                                if(fecha_envio_equipo!=""){
                                        if(fecha_salida_operacion!=""){
                                                //caso feliz, dejo continuar
                                        }else{
                                                alert("Falta la Fecha Retiro de Operación");
                                                return;
                                        }
                                }else{
                                       
                                        if(fecha_salida_operacion!=""){
                                                //caso feliz, dejo continuar
                                        }else{
                                                
                                                //no pasa nada, ok!
                                        }


                                }


                        }

		}

	
		var id_evento = "<? echo $id_evento; ?>";
		Modalbox.hide();
		var url="./nuevoEvento2.php";

		//var operationHidden=document.getElementById("operationHidden");
		
		var forma=document.getElementById("forma1");
		forma.submit();
        //location.href=url+"?operacion=doModificarEquipoCalibracion&id_evento="+id_evento+"&id_equipo="+id_equipo+"&fecha_salida_operacion="+fecha_salida_operacion+"&fecha_envio_equipo="+fecha_envio_equipo+"&fecha_entrega_equipo="+fecha_entrega_equipo+"&fecha_puesta_produccion="+fecha_puesta_produccion+"&novedades="+novedades+"&costo="+costo+"&ultima_fecha_calibracion="+ultima_fecha_calibracion+"&proxima_fecha_calibracion="+proxima_fecha_calibracion;

	}


/**
 * DHTML date validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 */
// Declaring valid date character, minimum year and maximum year
var dtCh= "-";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	
	//var strMonth=dtStr.substring(0,pos1)
	//var strDay=dtStr.substring(pos1+1,pos2)
	//var strYear=dtStr.substring(pos2+1)
	
        var strYear=dtStr.substring(0,pos1)
        var strMonth=dtStr.substring(pos1+1,pos2)
        var strDay=dtStr.substring(pos2+1)


	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		alert("El formato de la fecha debe ser : yyyy-mm-dd")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
		alert("Por favor ingrese un mes valido")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Por favor ingrese un dia valido.")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("Por favor ingrese un año valido entre "+minYear+" y "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Por favor ingrese una fecha valida.")
		return false
	}
return true
}

function ValidateForm(dat){
	
	//var dt=document.frmSample.txtDate
	if (isDate(dat)==false){
		return false
	}
    return true
 }


</script>

</head>

<body>
<p>&nbsp;</p>
<p align="center"><strong><font size="+3"><? echo $titulo; ?> del Evento de <? echo $evento; ?>&nbsp;<font color="red"><? echo $id_evento; ?></font></font></strong></p>

<table width="600" border="0" cellspacing="2" cellpadding="1" align="center">
<tr bgcolor="#FF0000">
<td align='center'><font color="#FFFFFF"><strong>Campo</strong></td>
<td align='center'><font color="#FFFFFF"><strong>Valor</strong></font></td>
</tr>
<tr>
	<td bgcolor="#DDDBDB" align='center'>Fecha de Generacion del Evento</td>
	<td align='center'><? echo $fecha; ?></td>
</tr>
<tr>
	<td bgcolor="#DDDBDB" align='center'>Responsable</td>
	<td align='center'> <input id="responsable"type="text" value="<? echo $responsable; ?>"></td>
</tr>

<!--tr>
        <td bgcolor="#DDDBDB" align='center'>Tipo de Evento</td>
        <td align='center'>
                <select id="selectEvento">
                        <option value="CALIBRACION" <? if($evento=="CALIBRACION") echo "selected"; ?>>CALIBRACION</option>
                        <option value="MANTENIMIENTO" <? if($evento=="MANTENIMIENTO") echo "selected"; ?>>MANTENIMIENTO</option>
                </select>
        </td>
</tr-->

<tr>
        <td bgcolor="#DDDBDB" align='center'>Observaciones</td>
        <td align='center'><textarea id="observaciones" rows='10' cols='40'><? echo $observaciones;?></textarea></td>
</tr>

<tr>
        <td bgcolor="#DDDBDB" align='center'>Estado</td>
        <td align='center'> 
		<select id="selectEstado">
			<option value="PENDIENTE" <? if($estado=="PENDIENTE") echo "selected"; ?>>PENDIENTE</option>
                        <option value="CERRADO" <? if($estado=="CERRADO") echo "selected"; ?>>CERRADO</option>
		</select>
	</td>
</tr>


</table>
<center><input type="button" value="Guardar" onclick="javascript:guardarInfo();"></center>


<? 
if($id_evento==''||!isset($id_evento)){
	echo "</body></html>";
	return;
} 
?>

<br>

<center><h2>Equipos relacionados al evento</h2><center>

<table border="0" cellspacing="2" cellpadding="1" align="center" width="100%">
<tr bgcolor="#FF0000">
<td align='center'><font color='white'><b>Nombre del Elemento</b></font></td>
<td align='center'><font color='white'><b>Serial</b></font></td>
<td align='center'><font color='white'><b>Fecha Retiro de Operación</b></font></td>
<td align='center'><font color='white'><b>Fecha de Entrega al Laboratorio de Calibración</b></font></td>
<td align='center'><font color='white'><b>Fecha de Recepción desde el Laboratorio de Calibración</b></font></td>
<td align='center'><font color='white'><b>Fecha Entrada en Operación</b></font></td>
<td align='center'><font color='white'><b>Novedades</b></font></td>
<td align='center'><font color='white'><b>Costo</b></font></td>
<td align='center'><font color='white'><b>Archivo</b></font></td>
<td align='center'><font color='white'><b>Operación</b></font></td>
</tr>

<?
	$sql="select (select nombre from dispositivo where id=a.id_dispositivo),(select serial from dispositivo where id=a.id_dispositivo),a.fecha_salida_operacion,a.fecha_envio_equipo,a.fecha_entrega_equipo,a.fecha_puesta_produccion,a.novedades,a.costo,a.id_dispositivo,a.id_file from evento_por_dispositivo a where a.id_evento=$id_evento";
	//echo $sql;
	$result = pg_query($sql);
	$rows=pg_numrows($result);
	$j=1;
        for($i=0;$i<$rows;$i++){
		$j=$j+1;
		$nombre=pg_result($result,$i,0);
		$serial=pg_result($result,$i,1);
		$fecha_salida_operacion=pg_result($result,$i,2);
		$fecha_envio_equipo=pg_result($result,$i,3);
		$fecha_entrega_equipo=pg_result($result,$i,4);
		$fecha_puesta_produccion=pg_result($result,$i,5);
		$novedades=pg_result($result,$i,6);
		$costo=pg_result($result,$i,7);
		$id_dispositivo=pg_result($result,$i,8);
		$id_file = pg_result($result,$i,9);

                if( $j % 2 == 0 ){
                        $bg="#DDDBDB";
                } else {
                        $bg="#FFFFFF";
                }

		echo "<tr bgcolor='$bg'>";
		echo "<td>$nombre</td>";
                echo "<td>$serial</td>";
                echo "<td>$fecha_salida_operacion</td>";
                echo "<td>$fecha_envio_equipo</td>";
                echo "<td>$fecha_entrega_equipo</td>";
                echo "<td>$fecha_puesta_produccion</td>";
				echo "<td>$novedades</td><td>$costo</td>";
				$id_file_splited=split("-_-",$id_file);
				echo "<td><a href='/metroune/upload_files/$id_file'>$id_file_splited[1]</a></td>";
				echo "<td><input type='button' value='Editar' onclick='javscript:editarEquipo(\"$id_dispositivo\")'></td>";
				echo "</tr>";
	}

?>
</table>

</body>
</html>
