<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	$dispositivo=$HTTP_GET_VARS["dispositivo"];
	$operacion=$HTTP_GET_VARS["operacion"];

        $fecha = date("Y-m-d");

        $operacion=$HTTP_GET_VARS["operacion"];
        $variable=$HTTP_GET_VARS["variable"];
        $nombre_variable=$HTTP_GET_VARS["nombre_variable"];
	

	if ($operacion=="generarReporte") {
		$sql="select count(*), $variable from dispositivo group by $variable order by 1 asc";
		$result = pg_query($sql);
	}

?>

<html>
<head>
<title>Reportes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="calendar.js" type="text/javascript"></script>
<script language="JavaScript">

addCalendar("DateIni", "calIni", "fechaIni", "forma1");
addCalendar("DateFin", "calFin", "fechaFin", "forma1");

</script>
<style type="text/css">
a:link { font-weight: bold; font-size: 15; text-decoration: none; color: blue }
a:visited { font-weight: bold; font-size: 15; text-decoration: none; color: blue }
a:hover {color: BLACK; font-weight: bold;}

</style>

<script language="JavaScript">

function buscar(){
	var selectvars = document.getElementById("vars");
	var variable = selectvars.options[selectvars.selectedIndex].value;
	var nombre_variable = selectvars.options[selectvars.selectedIndex].text;
	
	var request="&variable="+variable+"&nombre_variable="+nombre_variable;

	location.href="./reportes.php?operacion=generarReporte"+request;
}

function nuevo(){
	location.href="./nuevoEvento.php";
}

function editar(id){
	location.href="./nuevoEvento.php?operacion=editar&id_evento="+id;
}

function consultar(parametroBusqueda){
	var variable="<? echo $variable; ?>";
	var request="&paramSearch="+variable+"&valueSearch="+parametroBusqueda;

	location.href="./listadoDispositivos.php?operacion=consulta"+request;
	
}

</script>

</head>

<body>
<p>&nbsp;</p>
<p align="center"><strong><font size="+3">Reportes</font></strong></p>

<p>&nbsp;</p>

<center>Seleccione una variable:

<select id="vars">
	<option value="modelo" <? if($variable=="modelo") echo "selected"; ?>>Modelo</option>
	<option value="vendor" <? if($variable=="vendor") echo "selected"; ?>>Proveedor de Equipos</option>
	<option value="marca" <? if($variable=="marca") echo "selected"; ?>>Marca</option>
	<option value="responsable_disp" <? if($variable=="responsable_disp") echo "selected"; ?>>Área Responsable</option>
	<option value="responsable_revisiones" <? if($variable=="responsable_revisiones") echo "selected"; ?>>Contrato o Persona Responsable del Elemento</option>
</select>
&nbsp;
&nbsp;
<input type="button" value="Consultar" onclick="javascript:buscar();">
</center>
<br>
<?
    if ($operacion=="generarReporte") {
	
	
	echo "<table align='center'>";
	echo "<tr bgcolor='red'>";
        echo "<td align='center'><b><font color='white'>$nombre_variable</font></b></td>";
        echo "<td align='center'><b><font color='white'>Cantidad</font></b></td>";
	echo "</tr>";
	
	$j=1;
        $bg="#CCCCCC";
        $rows=pg_numrows($result);
	
	$contador=0;

        for($i=0;$i<$rows;$i++){
                $j=$j+1;
                $conteo = pg_result($result,$i,0);
                $var = pg_result($result,$i,1);
		$contador=$contador+$conteo;

                if( $j % 2 == 0 ){
                        $bg="#DDDBDB";
                } else {
                        $bg="#FFFFFF";
                }
       		echo "<tr bgcolor='".$bg."'>";
                        echo "<td><div align='center'>".$var."</div></td>";
                        echo "<td><div align='center'><a href='javascript:consultar(\"$var\");'>".$conteo."</a></div></td>";
		echo "</tr>";

        }//end while
	
	echo "<tr bgcolor='black'>";
   		echo "<td><div align='center'><b><font color='white'>TOTAL</font></b></div></td>";
                echo "<td><div align='center'><b><font color='white'>$contador</font></b></div></td>";
      	echo "</tr>";

   }
?>

<tr>

</tr>
</table>

</body>
</html>
