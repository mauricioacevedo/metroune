<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	$dispositivo=$HTTP_GET_VARS["dispositivo"];
	$operacion=$HTTP_GET_VARS["operacion"];

        $fecha = date("Y-m-d");

        $fechaIni=$HTTP_GET_VARS["fechaIni"];
        $fechaFin=$HTTP_GET_VARS["fechaFin"];
        $varBusqueda=$HTTP_GET_VARS["campo"];
        $valorBusqueda=$HTTP_GET_VARS["valorCampo"];

        $fecha = date("Y-m-d");
	$fecha_vacia="";
        if (!isset($fechaIni) || $fechaIni == "") {
                $fechaIni = $fecha;
		$fecha_vacia= "2008-01-01";
		//esta fecha se utiliza para mostrar todos los registros de esta forma cuando se inicia.
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
<p align="center"><strong><font size="+3">Modelos de Equipos</font></strong></p>

<p>&nbsp;</p>

<center><a href="./modelosEquipos.php">Modelos de equipos</a></center>
<p>&nbsp;</p>

<center><a href="">-</a></center>


<center>

</body>
</html>
