<?php

	session_start();
	include_once('conexion.php');

	$dispositivo=$HTTP_GET_VARS["dispositivo"];
	$operacion=$HTTP_GET_VARS["operacion"];
	$conexion_bd = getConnectionGestionPedidos();

        $fecha = date("Y-m-d");

        $fechaIni=$HTTP_GET_VARS["fechaIni"];
        $fechaFin=$HTTP_GET_VARS["fechaFin"];
        $varBusqueda=$HTTP_GET_VARS["campo"];
        $valorBusqueda=$HTTP_GET_VARS["valorCampo"];

        $fecha = date("Y-m-d");
        if (!isset($fechaIni) || $fechaIni == "") {
                $fechaIni = $fecha;
        }
        if (!isset($fechaFin) || $fechaFin == "") {
                $fechaFin = $fecha;
        }


	$sql="SELECT nombre, id,serial FROM dispositivo order by nombre ASC";
	$result = pg_query($sql);
	$rows=pg_numrows($result);
?>

<html>
<head>
<title>Eventos de Equipos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="calendar.js" type="text/javascript"></script>
<script language="JavaScript">

addCalendar("DateIni", "calIni", "fechaIni", "forma1");
addCalendar("DateFin", "calFin", "fechaFin", "forma1");

</script>

<script language="JavaScript">

function buscar(){
	var variable = document.getElementById("variables").value;
	var valor = document.getElementById("valor").value;
	var fechaIni=document.getElementById("fechaIni").value;
        var fechaFin=document.getElementById("fechaFin").value;

 	if (variable =='-1') valor='';

	 var request="&fechaIni="+fechaIni+"&fechaFin="+fechaFin+"&campo="+variable+"&valorCampo="+valor;
	//para la paginacion
	//&pagina="+pagina+"&txtRegistrosPagina="+regXpag;

	location.href="./eventos.php?operacion=eventos"+request;
}

function nuevo(){
	location.href="./nuevoEvento.php";
}

function editar(id){
	location.href="./nuevoEvento.php?operacion=editar&id_evento="+id;
}

</script>

</head>

<body>
<p>&nbsp;</p>
<p align="center"><strong><font size="+3">Eventos de Equipos</font></strong></p>

<center>

<input name="Agregar" type="submit" id="Agregar" value="Nuevo Evento" onClick="javascript:nuevo();">

</center>
<br>
<form name="forma1">
<table align="center">
<tr>
<td align="center"><font size="3">Seleccione:</font>
  <select name="variables" id="variables">
	<option value='-1'>Ninguno</option>
	<option value='nombre' <? if($varBusqueda=="nombre") echo "selected"; ?>>Nombre</option>
        <option value='tipo' <? if($varBusqueda=="tipo") echo "selected"; ?>>Tipo</option>
        <option value='responsable' <? if($varBusqueda=="responsable") echo "selected"; ?>>Responsable</option>
	<option value='estado' <? if($varBusqueda=="estado") echo "selected"; ?>>Estado</option>
  </select>
</td>
<td align="center">
  &nbsp;&nbsp;

  <input name="valor" type="text" id="valor" value="<? echo $valorBusqueda; ?>" size="10" style="background-color: rgb(255, 255, 160);">
</td>
<!--td align="center"> Desde: <input type="text" name="fechaIni" id="fechaIni" value="<? echo $fechaIni; ?>" maxlength="10" size="10" style="background-color: rgb(255, 255, 160);">
        <span title="Click Para Abrir El Calendario">
	<a href="javascript:showCal('DateIni', 5, 5)" style="color: black;">(editar)</a>
	</span>
<div id="calIni" style="position:relative; visibility: hidden;" >
</td>
<td align="center">

<font color="black">Hasta:</font>
        <input type="text" name="fechaFin" id="fechaFin" value="<? echo $fechaFin; ?>" maxlength="10" size="10" style="background-color: rgb(255, 255, 160);">
         <span title="Click Para Abrir El Calendario"><a href="javascript:showCal('DateFin', 5, 5)"  style="color: black;">(editar)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <div id="calFin" style="position:relative; visibility: hidden;">

</td-->
<td align="center">
  <input name="buscar2" type="button" id="buscar2" value="Buscar" onClick="javascript:buscar();">
</td>
</table>

</form>

<table width="100%" border="0" cellspacing="2" cellpadding="0" align="center">
  <tr bgcolor="#FF0000">
    <!--td><div align="center"><font color="#FFFFFF"><strong>Fecha</strong></font></div></td-->

    <td> <div align="center"><font color="#FFFFFF"><strong>Nombre del Equipo</strong></font></div></td>

    <td> <div align="center"><font color="#FFFFFF"><strong>Tipo de Elemento</strong></font></div></td>

    <td> <div align="center"><font color="#FFFFFF"><strong>Responsable del Elemento</strong></font></div></td>

    <td> <div align="center"><font color="#FFFFFF"><strong>Estado</strong></font></div></td>
  </tr>

  <?
  	//if($dispositivo!=''){ } else { return; }

	$where=" a.fecha between '$fechaIni' and '$fechaFin' ";//se le quito las horas para que la consulta trajera valores
	//como el campo fecha de la tabla no tiene horas no traia registros del mismo dia.
        if($varBusqueda!="" && $valorBusqueda!=""){
		if($varBusqueda=='nombre'){
			$sql="select id from dispositivo where nombre='$valorBusqueda'";
			$result = pg_query($sql);
			$id = pg_result($result,0,0);
			$where=$where." and a.dispositivo_id='$id'";
		} else if($varBusqueda=='tipo'){
			$where=$where." and a.dispositivo_id IN (select c.id from dispositivo c where c.tipo='$valorBusqueda')";

		} else if($varBusqueda=='responsable'){
                        $where=$where." and lower(a.$varBusqueda) like lower('%$valorBusqueda%')";

                }  else $where=$where." and a.$varBusqueda='$valorBusqueda'";
        }



	$sql="select a.fecha,(select b.nombre from dispositivo b where b.id=a.dispositivo_id),(select b.tipo from dispositivo b where b.id=a.dispositivo_id),".
	" a.evento,a.descripcion,a.responsable,a.estado,a.id from itinerario_eventos a where $where order by a.fecha DESC";

	//echo $sql;
	//no esta funcinoando el lower!!!
	$result = pg_query($sql);
  	$j=1;
  	$bg="#CCCCCC";
	$rows=pg_numrows($result);
  for($i=0;$i<$rows;$i++){
		$j=$j+1;
		$fecha = pg_result($result,$i,0);
    	$nombre = pg_result($result,$i,1);
	    $tipo = pg_result($result,$i,2);
	    $evento = pg_result($result,$i,3);
	    $descripcion = pg_result($result,$i,4);
	    $responsable = pg_result($result,$i,5);
	    $estado= pg_result($result,$i,6);
	    $id=pg_result($result,$i,7);

  		if( $j % 2 == 0 ){
			$bg="#DDDBDB";
		} else {
			$bg="#FFFFFF";
		}

		if($estado=='PENDIENTE'){
			$estado="<a href='javascript:editar(\"$id\");'>PENDIENTE</a>";
		}


		  echo "<tr bgcolor='".$bg."'>";
			echo "<td><div align='center'>".$fecha."</div></td>";
			echo "<td><div align='center'>".$nombre."</div></td>";
			echo "<td><div align='center'>".$tipo."</div></td>";
			echo "<td><div align='center'><span title='$descripcion'>".$evento."</span></div></td>";
			echo "<td><div align='center'>".$responsable."</div></td>";
			echo "<td><div align='center'>".$estado."</div></td>";
		  echo "</tr>";

  	}//end while
  ?>
</table>
<p>&nbsp;</p>
<p align="center">&nbsp; </p>
<p align="center">&nbsp;</p>
<p align="center"><strong><font size="+3"></font></strong></p>
</body>
</html>
