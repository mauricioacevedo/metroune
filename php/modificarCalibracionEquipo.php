<?php
        session_start();
        include_once('conexion.php');
        $conexion_bd=getConnectionGestionPedidos();

        $id_equipo=$HTTP_GET_VARS["id_equipo"];
        $evento_id=$HTTP_GET_VARS["evento_id"];
	
	$sql="select (select nombre from dispositivo where id=a.id_dispositivo),(select serial from dispositivo where id=a.id_dispositivo),a.fecha_salida_operacion,a.fecha_envio_equipo,a.fecha_entrega_equipo,a.fecha_puesta_produccion,a.novedades,a.costo,a.proxima_fecha_calibracion,a.ultima_fecha_calibracion,a.id_file from evento_por_dispositivo a where a.id_evento=$evento_id and a.id_dispositivo=$id_equipo";
	
        $result = pg_query($sql);
        $rows=pg_numrows($result);

	if($rows > 0){
		
                $nombre=pg_result($result,0,0);
                $serial=pg_result($result,0,1);
                $fecha_salida_operacion=pg_result($result,0,2);
                $fecha_envio_equipo=pg_result($result,0,3);
                $fecha_entrega_equipo=pg_result($result,0,4);
                $fecha_puesta_produccion=pg_result($result,0,5);
                $novedades=pg_result($result,0,6);
                $costo=pg_result($result,0,7);
				$proxima_fecha_calibracion=pg_result($result,0,8);
				$ultima_fecha_calibracion=pg_result($result,0,9);
				$id_file=pg_result($result,0,10);

	}else{
		echo "<script>alert('No se encontraron registros del equipo $id_equipo en el evento $evento_id.');";
		echo "Modalbox.hide();</script>";
	}

	
	
?>


<html>
<script language="JavaScript" src="calendar.js" type="text/javascript"></script>
<script language="JavaScript">

//addCalendar("Date_fecha_salida_operacion", "cal_fecha_salida_operacion", "fecha_salida_operacion", "forma1");
//addCalendar("DateFin", "calFin", "fechaFin", "forma1");

</script>

<body>
<!<--form name="forma1"-->

<form enctype="multipart/form-data" action="nuevoEvento2.php" method="post" name="forma1" id="forma1">

<input type="hidden" id="operationHidden" name="operationHidden" value="doModificarEquipoCalibracion">

<input type="hidden" name="id_evento" value="<? echo $evento_id; ?>">
<input type="hidden" name="id_equipo" value="<? echo $id_equipo; ?>">
<input type="hidden" name="borrarArchivo2" id="borrarArchivo2" value="no">

<input type="hidden" name="ultima_fecha_calibracion" id="ultima_fecha_calibracion" value="<? echo $ultima_fecha_calibracion;?>">
<input type="hidden" name="proxima_fecha_calibracion" id="proxima_fecha_calibracion" value="<? echo $proxima_fecha_calibracion;?>">



<input name="MAX_FILE_SIZE" value="300000000" type="hidden">

<table width="700" border="0" cellspacing="2" cellpadding="1" align="center">
<tr bgcolor="#FF0000">
<td align='center' width="60%"><font color="#FFFFFF"><strong>Campo</strong></td>
<td align='center' width="40%"><font color="#FFFFFF"><strong>Valor</strong></font></td>
</tr>
<tr>
        <td bgcolor="#DDDBDB" align='center'>Nombre del Elemento</td>
        <td align='center'><? echo $nombre; ?></td>
</tr>
<tr>
        <td bgcolor="#DDDBDB" align='center'>Serial</td>
        <td align='center'> <? echo $serial; ?></td>
</tr>

<tr>
        <td bgcolor="#DDDBDB" align='center'>Fecha Retiro de Operación</td>
        <td align='center'>

<input id="fecha_salida_operacion" name="fecha_salida_operacion" type="text" value="<? echo $fecha_salida_operacion; ?>">

</td>
</tr>

<tr>
        <td bgcolor="#DDDBDB" align='center'>Fecha de Entrega al Laboratorio de Calibración</td>
        <td align='center'><input id="fecha_envio_equipo" name="fecha_envio_equipo" type="text" value="<? echo $fecha_envio_equipo; ?>"></td>
</tr>

<tr>
        <td bgcolor="#DDDBDB" align='center'>Fecha de Recepción desde el Laboratorio de Calibración</td>
        <td align='center'> <input id="fecha_entrega_equipo" name="fecha_entrega_equipo" type="text" value="<? echo $fecha_entrega_equipo; ?>"></td>
</tr>

<tr>
        <td bgcolor="#DDDBDB" align='center'>Fecha Entrada en Operación</td>
        <td align='center'> <input id="fecha_puesta_produccion" name="fecha_puesta_produccion" type="text" value="<? echo $fecha_puesta_produccion; ?>"></td>
</tr>


<tr>
        <td bgcolor="#DDDBDB" align='center'>Fecha de Última Calibración</td>
        <td align='center'> <input disabled="disabled" id="ultima_fecha_calibracion2" name="ultima_fecha_calibracion2" type="text" value="<? echo $ultima_fecha_calibracion; ?>"></td>
</tr>

<tr>
        <td bgcolor="#DDDBDB" align='center'>Fecha de la Próxima Calibración</td>
        <td align='center'> <input disabled="disabled" id="proxima_fecha_calibracion2" name="proxima_fecha_calibracion2" type="text" value="<? echo $proxima_fecha_calibracion; ?>"></td>
</tr>


<tr>
        <td bgcolor="#DDDBDB" align='center'>Novedades</td>
        <td align='center'> <input id="novedades" name="novedades" type="text" value="<? echo $novedades; ?>"></td>
</tr>
<tr>
        <td bgcolor="#DDDBDB" align='center'>Costo</td>
        <td align='center'> <input id="costo" name="costo" type="text" value="<? echo $costo; ?>"></td>
</tr>

<? if($id_file!=""){
	echo '<tr>';
	echo        '<td bgcolor="#DDDBDB" align="center">Certificado Actual</td>';
	$id_file_splited=split("-_-",$id_file);
	echo        "<td align='center'><a href='/metroune/upload_files/$id_file'>$id_file_splited[0]</a>";
	echo        '&nbsp;<font color="red" size="2">Eliminar?</font><input type="checkbox" name="borrarArchivo" id="borrarArchivo">';
}  

echo '</td></tr>';

?>
<tr>
        <td bgcolor="#DDDBDB" align='center'>Cargar Certificado</td>
       <td align='center'> <input name="userfile" style="background-color: rgb(255, 255, 160);"  size="25" type="file"></td>
</tr>


</table>

</form>
<center><input type="button" value="Guardar" onclick="javascript:guardarInfoEquiposCalibracion('<? echo $id_equipo; ?>');"></center>
</body>
</html>
