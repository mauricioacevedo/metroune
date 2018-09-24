<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	$dispositivo=$HTTP_GET_VARS["dispositivo"];

        $fecha = date("Y-m-d");

        $fechaIni=$HTTP_GET_VARS["fechaIni"];
        $fechaFin=$HTTP_GET_VARS["fechaFin"];
        $varBusqueda=$HTTP_GET_VARS["campo"];
        $valorBusqueda=$HTTP_GET_VARS["valorCampo"];

	//$varBusqueda, $valorBusqueda
        $fecha = date("Y-m-d");
        if (!isset($fechaIni) || $fechaIni == "") {
                $fechaIni = $fecha;
        }
        if (!isset($fechaFin) || $fechaFin == "") {
                $fechaFin = $fecha;
        }


	$operacion=$HTTP_GET_VARS["operacion"];

	if($operacion=="eliminar"){

		$id_programacion=$HTTP_GET_VARS["id_programacion"];

		$sql="delete from programacion_automatica where id=$id_programacion";
		$result = pg_query($sql);



	}
	//(select b.nombre from dispositivo b where b.id=a.dispositivo_id)
	$sql="SELECT a.id,a.dispositivo_id,a.frecuencia,a.unidad_frecuencia,a.evento,a.duracion,a.unidad_duracion,a.responsable,a.fecha_inicio FROM programacion_automatica a order by a.id ASC";


//	echo $sql;

	$result = pg_query($sql);
	$rows=pg_numrows($result);
?>

<html>
<head>
<title>Rutinas de Calibraciones y Mantenimiento</title>
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

	location.href="./programacion_automatica.php?operacion="+request;
}

function nuevo(){
	location.href="./nuevaProgramacion.php";
}

function editar( id ) {
	location.href="./nuevaProgramacion.php?operacion=editar&id_programacion="+id;
}

function eliminar(id,dispositivo){

	if(confirm("Se eliminara la programacion sobre el equipo "+dispositivo+". Desea continuar?")){
		location.href="./programacion_automatica.php?operacion=eliminar&id_programacion="+id;
	}
}

</script>

</head>

<body>
<p>&nbsp;</p>
<p align="center"><strong><font size="+3">Rutinas de Calibración y Mantenimiento</font></strong></p>

<center>

<input name="Agregar" type="submit" id="Agregar" value="Nueva Programacion" onClick="javascript:nuevo();">

</center>
<br>
<form name="forma1">
<table align="center">
<tr>
<td align="center"><font size="3">Seleccione:</font>
  <select name="variables" id="variables">
	<option value='-1'>Ninguno</option>
	<option value='nombre' <? if ( $varBusqueda=="nombre") echo " selected";  ?>>Nombre</option>
        <option value='tipo' <? if ( $varBusqueda=="tipo") echo " selected";  ?>>Tipo</option>
	<option value='evento' <? if ( $varBusqueda=="evento") echo " selected";  ?>>Evento</option>
        <option value='responsable' <? if ( $varBusqueda=="responsable") echo " selected";  ?>>Responsable</option>

    <?/*
  	  	for($i=0;$i<$rows;$i++){
			$j=$j+1;
			$nombre = pg_result($result,$i,0);
    		$id = pg_result($result,$i,1);
			$sel="";
			if ( $id==$dispositivo){
				$sel="selected";
			}

			echo "<option value='".$id."' ".$sel.">".$nombre."</option>\n";
		}
	*/
  ?>
  </select>
</td>
<td align="center">
  &nbsp;&nbsp;

  <input name="valor" type="text" id="valor" value="<? echo $valorBusqueda; ?>" size="10" style="background-color: rgb(255, 255, 160);">
</td>
<td align="center">


         Desde: <input type="text" name="fechaIni" id="fechaIni" value="<? echo $fechaIni; ?>" maxlength="10" size="10" style="background-color: rgb(255, 255, 160);">
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

</td>
<td align="center">
  <input name="buscar2" type="button" id="buscar2" value="Buscar" onClick="javascript:buscar();">
</td>
</table>

</form>

<table width="100%" border="0" cellspacing="2" cellpadding="0" align="center">
  <tr bgcolor="#FF0000">
    <!--td>
      <div align="center"><font color="#FFFFFF"><strong>ID</strong></font></div></td-->
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Numero de <br> Equipos</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Evento</strong></font></div></td>
<td>
      <div align="center"><font color="#FFFFFF"><strong>Fecha Inicial</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Frecuencia</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Duracion</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Responsable</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Opcion</strong></font></div></td>
  </tr>

  <?

  	$j=1;
  	$bg="#CCCCCC";
	$rows=pg_numrows($result);
  	for($i=0;$i<$rows;$i++){
		$j=$j+1;
		$id = pg_result($result,$i,0);
    		$dispositivo = pg_result($result,$i,1);

		
		$array_disp=explode(";",$dispositivo);
        	$size=count($array_disp)-1;
        	//el menos uno es para que ignore el ultimo token ke siempre viene vacio
        	//el token viene vacio porke el explode considera el espacio nullo como
        	//un caracter a tener en cuenta como token
		/*
		for ($i=0;$i<$size-1;$i++){
                	$dispo=$array_disp[$i];

			$sql2="select b.nombre from dispositivo b where b.id=$dispo";
			$result2 = pg_query($sql2);
		        $rows2=pg_numrows($result2);
			
			$name = pg_result($result2,0,0);
                	$names = $names.$name."<br>";
        	}
		
*/

		$frecuencia = pg_result($result,$i,2);
		$unidad_frecuencia= pg_result($result,$i,3);
		$evento = pg_result($result,$i,4);
	    	$duracion = pg_result($result,$i,5);
		$unidad_duracion= pg_result($result,$i,6);
		$responsable= pg_result($result,$i,7);
		$fecha= pg_result($result,$i,8);
		if($unidad_frecuencia=='D'){
			$unidad_frecuencia="Dias";
		}elseif($unidad_frecuencia=='M'){
			$unidad_frecuencia="Meses";
		}elseif($unidad_frecuencia=='Y'){
                        $unidad_frecuencia="A&ntilde;os";
                }

		if($unidad_duracion=='N'){
                        $unidad_duracion="Indefinido";
			$duracion="";
                }elseif($unidad_duracion=='M'){
                        $unidad_duracion="Meses";
                }elseif($unidad_duracion=='Y'){
                        $unidad_duracion="A&ntilde;os";
                }


  		if( $j % 2 == 0 ){
			$bg="#DDDBDB";
		} else {
			$bg="#FFFFFF";
		}
		  echo "<tr bgcolor='".$bg."'>";
			echo "<!--td><div align='center'>".$id."</div></td-->";
			echo "<td><div align='center'>".$size."</div></td>";
			echo "<td><div align='center'>$evento</div></td>";
			echo "<td><div align='center'>$fecha</div></td>";
			echo "<td><div align='center'>$frecuencia $unidad_frecuencia</div></td>";
			echo "<td><div align='center'>$duracion $unidad_duracion</div></td>";
			echo "<td><div align='center'>$responsable</div></td>";
			echo "<td><div align='center'><a href='javascript:editar(\"$id\");'>Editar</a><br><a href='javascript:eliminar(\"$id\",\"$dispositivo\");'>Eliminar</a></div></td>";
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
