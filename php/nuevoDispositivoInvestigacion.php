<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	$nombre=$HTTP_GET_VARS["nombre"];
	$tipo=$HTTP_GET_VARS["tipo"];
	$vendor=$HTTP_GET_VARS["vendor"];
	$modelo=$HTTP_GET_VARS["modelo"];
	$estado=$HTTP_GET_VARS["estado"];
	$op=$HTTP_GET_VARS["operacion"];
	$serial=$HTTP_GET_VARS["serial"];
	$codigo_sap=$HTTP_GET_VARS["codigo_sap"];
	$marca=$HTTP_GET_VARS["marca"];
	$metodo=$HTTP_GET_VARS["metodo"];
	$responsable_disp=$HTTP_GET_VARS["responsable_disp"];
	$ultima_fecha_cal=$HTTP_GET_VARS["ultima_fecha_cal"];
	$ciudad=$HTTP_GET_VARS["ciudad"];
	$observaciones=$HTTP_GET_VARS["observaciones"];
	$datos_contacto=$HTTP_GET_VARS["datos_contacto"];


	$resolucion=$HTTP_GET_VARS["resolucion"];
	$rango_medicion=$HTTP_GET_VARS["rango_medicion"];
	$incertidumbre_fabricacion=$HTTP_GET_VARS["incertidumbre_fabricacion"];
	$error_maximo=$HTTP_GET_VARS["error_maximo"];
	$criterios_aceptacion=$HTTP_GET_VARS["criterios_aceptacion"];
	$condiciones_ambientales=$HTTP_GET_VARS["condiciones_ambientales"];
	$responsable_revisiones=$HTTP_GET_VARS["responsable_revisiones"];
	$trazabilidad=$HTTP_GET_VARS["trazabilidad"];
	$garantia_vigente=$HTTP_GET_VARS["garantia_vigente"];
	
	$propiedad=$HTTP_GET_VARS["propiedad"];
	$tercero=$HTTP_GET_VARS["tercero"];
	$tipo_equipo=$HTTP_GET_VARS["tipo_equipo"];
	$clasificacion=$HTTP_GET_VARS["clasificacion"];

	$proxima_fecha_calibracion=$HTTP_GET_VARS["proxima_fecha_calibracion"];
	$validez_calibracion=$HTTP_GET_VARS["validez_calibracion"];
	$fecha_salida_operacion=$HTTP_GET_VARS["fecha_salida_operacion"];
	$fecha_envio_equipo=$HTTP_GET_VARS["fecha_envio_equipo"];
	$fecha_entrega_equipo=$HTTP_GET_VARS["fecha_entrega_equipo"];
	$fecha_puesta_produccion=$HTTP_GET_VARS["fecha_puesta_produccion"];
	$placa_activo=$HTTP_GET_VARS["placa_activo"];
	$fecha = date("Y-m-d");//fecha para la ultima fecha de calibracion que no puede ir null

	if($ultima_fecha_cal==''){
		$ultima_fecha_cal=$fecha;
	}


	if ( $op == "guardar" ){

		$sql="INSERT INTO dispositivo (nombre, tipo, vendor, modelo, estado,codigo_sap,serial,marca,metodo_de_verificacion,responsable_disp,resolucion,rango_medicion,incertidumbre_fabricacion,error_maximo,criterios_aceptacion,condiciones_ambientales,responsable_revisiones,trazabilidad,garantia_vigente,fecha_ultima_calibracion,propiedad,propiedad_tercero,tipo_equipo,clasificacion,validez_calibracion,proxima_fecha_calibracion,fecha_salida_operacion,fecha_envio_equipo,fecha_entrega_equipo,fecha_puesta_produccion,placa_activo,ciudad,observaciones,datos_contacto) VALUES ('".$nombre."','".$tipo."','".$vendor."','".$modelo."','".$estado."','$codigo_sap','$serial','$marca','$metodo','$responsable_disp','$resolucion','$rango_medicion','$incertidumbre_fabricacion','$error_maximo','$criterios_aceptacion','$condiciones_ambientales','$responsable_revisiones','$trazabilidad','$garantia_vigente','$ultima_fecha_cal','$propiedad','$tercero','$tipo_equipo','$clasificacion',$validez_calibracion,'$proxima_fecha_calibracion','$fecha_salida_operacion','$fecha_envio_equipo','$fecha_entrega_equipo','$fecha_puesta_produccion','$placa_activo','$ciudad','$observaciones','$datos_contacto');";
		//echo $sql;
		$result = pg_query($sql);
		$rows=pg_numrows($result);

		echo "<html>";
		echo "<head><title>Resultado</title></head>";

		echo "<body>";
		echo "<p>&nbsp;</p>";
		echo "<p align='center'><strong><font size='+3'>Mensaje</font></strong></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'><font size='4'>Se creo el equipo <font color='red'>".$nombre."</font> satisfactoriamente.</font></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'>";
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:location.href=\"./listadoDispositivos.php\";'>";
		echo "</p>";
		echo "</body>";
		echo "</html>";

		return;
	}

	$titulo="Nuevo";
	if ( $op == "editar" ){
		$id=$HTTP_GET_VARS["id_dispositivo"];
		$sql="select nombre, tipo, vendor, modelo, estado,codigo_sap,serial,marca,metodo_de_verificacion,responsable_disp,resolucion,rango_medicion,incertidumbre_fabricacion,error_maximo,criterios_aceptacion,condiciones_ambientales,responsable_revisiones,trazabilidad,garantia_vigente,fecha_ultima_calibracion,propiedad,propiedad_tercero,tipo_equipo,clasificacion,validez_calibracion,proxima_fecha_calibracion,fecha_salida_operacion,fecha_envio_equipo,fecha_entrega_equipo,fecha_puesta_produccion,placa_activo,ciudad,observaciones,datos_contacto from dispositivos_en_proceso where id=".$id;
		$result = pg_query($sql);

		$nombre = pg_result($result,0,0);
    	$tipo = pg_result($result,0,1);
	    $vendor = pg_result($result,0,2);
	    $modelo = pg_result($result,0,3);
	    $estado = pg_result($result,0,4);
	    $codigo_sap = pg_result($result,0,5);
	    $serial = pg_result($result,0,6);
	    $marca = pg_result($result,0,7);
	    $metodo =  pg_result($result,0,8);
	    $responsable_disp=pg_result($result,0,9);


		$resolucion=pg_result($result,0,10);
		$rango_medicion=pg_result($result,0,11);
		$incertidumbre_fabricacion=pg_result($result,0,12);
		$error_maximo=pg_result($result,0,13);
		$criterios_aceptacion=pg_result($result,0,14);
		$condiciones_ambientales=pg_result($result,0,15);
		$responsable_revisiones=pg_result($result,0,16);
		$trazabilidad=pg_result($result,0,17);
		$garantia_vigente=pg_result($result,0,18);
		$ultima_fecha_cal=pg_result($result,0,19);

		$propiedad=pg_result($result,0,20);
		$tercero=pg_result($result,0,21);
		
		$tipo_equipo=pg_result($result,0,22);
		$clasificacion=pg_result($result,0,23);
		$validez_calibracion=pg_result($result,0,24);
		$proxima_fecha_calibracion=pg_result($result,0,25);
		$fecha_salida_operacion=pg_result($result,0,26);
		$fecha_envio_equipo=pg_result($result,0,27);
		$fecha_entrega_equipo=pg_result($result,0,28);
		$fecha_puesta_produccion=pg_result($result,0,29);
		$placa_activo=pg_result($result,0,30);
		$ciudad=pg_result($result,0,31);
		$observaciones=pg_result($result,0,32);
		$datos_contacto=pg_result($result,0,33);

     	    $titulo="Editar";
	}

	if( $op == "editar2" ){
		$id=$HTTP_GET_VARS["id"];
		$sql="UPDATE dispositivos_en_proceso set nombre='".$nombre."', tipo='".$tipo."', vendor='".$vendor."', modelo='".$modelo."', estado='".$estado."', codigo_sap='$codigo_sap', serial='$serial', marca='$marca', metodo_de_verificacion='$metodo', responsable_disp='$responsable_disp',resolucion='$resolucion',rango_medicion='$rango_medicion',incertidumbre_fabricacion='$incertidumbre_fabricacion',error_maximo='$error_maximo',criterios_aceptacion='$criterios_aceptacion',condiciones_ambientales='$condiciones_ambientales',responsable_revisiones='$responsable_revisiones',trazabilidad='$trazabilidad',garantia_vigente='$garantia_vigente', fecha_ultima_calibracion='$ultima_fecha_cal', propiedad='$propiedad', propiedad_tercero='$tercero',tipo_equipo='$tipo_equipo',clasificacion='$clasificacion',validez_calibracion=$validez_calibracion,proxima_fecha_calibracion='$proxima_fecha_calibracion',fecha_salida_operacion='$fecha_salida_operacion', fecha_envio_equipo='$fecha_envio_equipo', fecha_entrega_equipo='$fecha_entrega_equipo', fecha_puesta_produccion='$fecha_puesta_produccion', placa_activo='$placa_activo',ciudad='$ciudad',observaciones='$observaciones',datos_contacto='$datos_contacto' ".
		" where id=".$id;
		//echo $sql;
		$result = pg_query($sql);
		$rows=pg_numrows($result);

		echo "<html>";
		echo "<head><title>Resultado</title></head>";

		echo "<body>";
		echo "<p>&nbsp;</p>";
		echo "<p align='center'><strong><font size='+3'>Mensaje</font></strong></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'><font size='5'>Se actualizo el dispositivo <font color='red'>".$nombre."</font> satisfactoriamente.</font></p>";
		echo "<p align='center'>&nbsp;</p>";
		echo "<p align='center'>";
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:location.href=\"./listadoDispositivosEnProceso.php\";'>";
		echo "</p>";
		echo "</body>";
		echo "</html>";

		return;

	}
	if( $op == "eliminar" ){
		$id=$HTTP_GET_VARS["id_dispositivo"];
                $sql="delete from dispositivo where id=".$id;
                $result = pg_query($sql);

		$sql="delete from itinerario_eventos where dispositivo_id=$id";
		$result = pg_query($sql);

		$sql="delete from programacion_automatica where dispositivo_id=$id";
                $result = pg_query($sql);


                echo "<html>";
                echo "<head><title>Resultado</title></head>";

                echo "<body>";
                echo "<p>&nbsp;</p>";
                echo "<p align='center'><strong><font size='+3'>Mensaje</font></strong></p>";
                echo "<p align='center'>&nbsp;</p>";
                echo "<p align='center'><font size='5'>Se elimino el dispositivo <font color='red'>".$nombre."</font>.</font></p>";
                echo "<p align='center'>&nbsp;</p>";
                echo "<p align='center'>";
                echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:window.back();'>";
                echo "</p>";
                echo "</body>";
                echo "</html>";

		return;
	}
	if($op!="editar"){
		$nombre = "";
    	$tipo = "";
		$vendor = "";
		$modelo = "";
		$estado = "";
		$modelo = "";
		$responsable_disp="";

                $resolucion="";
                $rango_medicion="";
                $incertidumbre_fabricacion="";
                $error_maximo="";
                $criterios_aceptacion="";
                $condiciones_ambientales="";
                $responsable_revisiones="";
                $trazabilidad="";
    		$garantia_vigente="";
		$propiedad="";
		$tercero="";
		
		$tipo_equipo="";
		$clasificacion="";
	}

	$sql="select * from tipos_dispositivos";

        $result = pg_query($sql);
        $rows=pg_numrows($result);
  	/*
	for($i=0;$i<$rows;$i++){
        	$nombre = pg_result($result,$i,1);
        	$id = pg_result($result,$i,0);
	}*/


?>

<html>
<head>
<title><? echo $titulo; ?> Equipo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

        <LINK rel="stylesheet" href="../javascript/tabs/ui.tabs.css" type="text/css" media="print, projection, screen">
        <STYLE type="text/css" media="screen, projection">
            /* Not required for Tabs, just to make this demo look better... */
            body, h1, h2 {
                font-family: "Trebuchet MS", Trebuchet, Verdana, Helvetica, Arial, sans-serif;
            }
            h1 {
                margin: 1em 0 1.5em;
                font-size: 18px;
            }
            h2 {
                margin: 2em 0 1.5em;
                font-size: 16px;
            }
            p {
                margin: 0;
            }
            pre, pre+p, p+p {
                margin: 1em 0 0;
            }
            code {
                font-family: "Courier New", Courier, monospace;
            }
        </STYLE>

        <SCRIPT src="../javascript/tabs/jquery-1.2.6.js" type="text/javascript"></SCRIPT>
        <SCRIPT src="../javascript/tabs/ui.core.js" type="text/javascript"></SCRIPT>
        <SCRIPT src="../javascript/tabs/ui.tabs.js" type="text/javascript"></SCRIPT>
        <SCRIPT type="text/javascript">
            $(function() {
                $('#rotate > ul').tabs({ fx: { opacity: 'toggle' } });
				//.tabs('rotate', 5000);
            });
        </SCRIPT>

<script language="JavaScript">

function editar(){
	var nombre1=document.getElementById("nombre");
	var tipoDispositivo1=document.getElementById("tipoDispositivo");
	var vendor1=document.getElementById("vendor");
	var modelo=document.getElementById("modelo");
	var estado1=document.getElementById("estado");
	var id='<? echo $id; ?>';
	var codigo_sap1=document.getElementById("codigo_sap");
	var serial1=document.getElementById("serial");
	var marca1=document.getElementById("marca");
	var metodo1=document.getElementById("metodo");//metodo de verificacion
	var responsable1=document.getElementById("responsable");
	var ultima_fecha_cal=document.getElementById("ultima_fecha_cal").value;


	var resolucion1=document.getElementById("resolucion");
	var rango_medicion1=document.getElementById("rango_medicion");
	var incertidumbre_fabricacion1=document.getElementById("incertidumbre_fabricacion");
	var error_maximo1=document.getElementById("error_maximo");
	var criterios_aceptacion1=document.getElementById("criterios_aceptacion");
	var condiciones_ambientales1=document.getElementById("condiciones_ambientales");
	var responsable_revisiones1=document.getElementById("responsable_revisiones");
	var trazabilidad1=document.getElementById("trazabilidad");
	var garantia_vigente1=document.getElementById("garantia_vigente");

	var propiedad1=document.getElementById("propiedad");
	var tercero1=document.getElementById("tercero");
	var tipo_equipo1=document.getElementById("tipo_equipo");
	var clasificacion1=document.getElementById("clasificacion");
	
	//2010-07-13 nuevos campos
        var proxima_fecha_calibracion=document.getElementById("proxima_fecha_calibracion").value;
        var validez_calibracion=document.getElementById("validez_calibracion").value;
        var fecha_salida_operacion=document.getElementById("fecha_salida_operacion").value;
        var fecha_envio_equipo=document.getElementById("fecha_envio_equipo").value;
        var fecha_entrega_equipo=document.getElementById("fecha_entrega_equipo").value;
        var fecha_puesta_produccion=document.getElementById("fecha_puesta_produccion").value;
	var placa_activo=document.getElementById("placa_activo").value;

	//2011-03-20 nuevos campos
	var ciudad=document.getElementById("ciudad").value;
	var observaciones=document.getElementById("observaciones").value;
	var datos_contacto=document.getElementById("datos_contacto").value;

	var nombre=nombre1.value;
	var tipoDispositivo=tipoDispositivo1.value;
	var vendor=vendor1.value;
	var modelo1=modelo.value;
	var codigo_sap=codigo_sap1.value;
	var estado=estado1.value;
	var serial=serial1.value;
	var marca=marca1.value;
	var metodo=metodo1.value;
	var responsable=responsable1.value;


        var resolucion=resolucion1.value;
        var rango_medicion=rango_medicion1.value;
        var incertidumbre_fabricacion=incertidumbre_fabricacion1.value;
        var error_maximo=error_maximo1.value;
        var criterios_aceptacion=criterios_aceptacion1.value;
        var condiciones_ambientales=condiciones_ambientales1.value;
        var responsable_revisiones=responsable_revisiones1.value;
        var trazabilidad=trazabilidad1.value;
        var garantia_vigente=garantia_vigente1.value;

        var propiedad=propiedad1.value;
        var tercero=tercero1.value;
	var tipo_equipo=tipo_equipo1.value;
	var clasificacion=clasificacion1.value;

	//alert("codigo sap: "+codigo_sap);

	var url="./nuevoDispositivoInvestigacion.php?operacion=editar2&id="+id;
	location.href=url+"&nombre="+nombre+"&tipo="+tipoDispositivo+"&vendor="+vendor+"&modelo="+modelo1+"&estado="+estado+"&codigo_sap="+codigo_sap+"&serial="+serial+"&marca="+marca+"&metodo="+metodo+"&responsable_disp="+responsable+
"&resolucion="+resolucion+"&rango_medicion="+rango_medicion+"&incertidumbre_fabricacion="+incertidumbre_fabricacion+"&error_maximo="+error_maximo+"&criterios_aceptacion="+criterios_aceptacion+"&condiciones_ambientales="+condiciones_ambientales+"&responsable_revisiones="+responsable_revisiones+"&trazabilidad="+trazabilidad+"&garantia_vigente="+garantia_vigente+"&ultima_fecha_cal="+ultima_fecha_cal+"&propiedad="+propiedad+"&tercero="+tercero+"&tipo_equipo="+tipo_equipo+"&clasificacion="+clasificacion+"&proxima_fecha_calibracion="+proxima_fecha_calibracion+"&validez_calibracion="+validez_calibracion+"&fecha_salida_operacion="+fecha_salida_operacion+"&fecha_envio_equipo="+fecha_envio_equipo+"&fecha_entrega_equipo="+fecha_entrega_equipo+"&fecha_puesta_produccion="+fecha_puesta_produccion+"&placa_activo="+placa_activo+"&ciudad="+ciudad+"&observaciones="+observaciones+"&datos_contacto="+datos_contacto;

}


function guardar(){
	var nombre1=document.getElementById("nombre");
	var tipoDispositivo1=document.getElementById("tipoDispositivo");
	var vendor1=document.getElementById("vendor");
	var modelo=document.getElementById("modelo");
	var estado1=document.getElementById("estado");
	var codigo_sap1=document.getElementById("codigo_sap");
	var serial1=document.getElementById("serial");
	var marca1=document.getElementById("marca");
	var metodo1=document.getElementById("metodo");//metodo de verificacion
	var responsable1=document.getElementById("responsable");
	var ultima_fecha_cal=document.getElementById("ultima_fecha_cal").value;

        var resolucion1=document.getElementById("resolucion");
        var rango_medicion1=document.getElementById("rango_medicion");
        var incertidumbre_fabricacion1=document.getElementById("incertidumbre_fabricacion");
        var error_maximo1=document.getElementById("error_maximo");
        var criterios_aceptacion1=document.getElementById("criterios_aceptacion");
        var condiciones_ambientales1=document.getElementById("condiciones_ambientales");
        var responsable_revisiones1=document.getElementById("responsable_revisiones");
        var trazabilidad1=document.getElementById("trazabilidad");
        var garantia_vigente1=document.getElementById("garantia_vigente");
		
	var propiedad1=document.getElementById("propiedad");
        var tercero1=document.getElementById("tercero");

        var tipo_equipo1=document.getElementById("tipo_equipo");
        var clasificacion1=document.getElementById("clasificacion");
	
	//2010-07-13: nuevos campos
	var proxima_fecha_calibracion=document.getElementById("proxima_fecha_calibracion").value;
	var validez_calibracion=document.getElementById("validez_calibracion").value;
	var fecha_salida_operacion=document.getElementById("fecha_salida_operacion").value;
	var fecha_envio_equipo=document.getElementById("fecha_envio_equipo").value;
	var fecha_entrega_equipo=document.getElementById("fecha_entrega_equipo").value;
	var fecha_puesta_produccion=document.getElementById("fecha_puesta_produccion").value;
	var placa_activo=document.getElementById("placa_activo").value;	
	
	//2011-03-20 nuevos campos
    var ciudad=document.getElementById("ciudad").value;
    var observaciones=document.getElementById("observaciones").value;
    var datos_contacto=document.getElementById("datos_contacto").value;


	var nombre=nombre1.value;
	var tipoDispositivo=tipoDispositivo1.value;
	var vendor=vendor1.value;
	var modelo1=modelo.value;
	var estado=estado1.value;
	var codigo_sap=codigo_sap1.value;
	var serial=serial1.value;
	var marca=marca1.value;
	var metodo=metodo1.value;
	var responsable=responsable1.value;

        var resolucion=resolucion1.value;
        var rango_medicion=rango_medicion1.value;
        var incertidumbre_fabricacion=incertidumbre_fabricacion1.value;
        var error_maximo=error_maximo1.value;
        var criterios_aceptacion=criterios_aceptacion1.value;
        var condiciones_ambientales=condiciones_ambientales1.value;
        var responsable_revisiones=responsable_revisiones1.value;
        var trazabilidad=trazabilidad1.value;
        var garantia_vigente=garantia_vigente1.value;

        var propiedad=propiedad1.value;
        var tercero=tercero1.value;

        var tipo_equipo=tipo_equipo1.value;
        var clasificacion=clasificacion1.value;
	


	var url="./nuevoDispositivo.php?operacion=guardar";

	//alert("codigo sap: "+codigo_sap);

	location.href=url+"&nombre="+nombre+"&tipo="+tipoDispositivo+"&vendor="+vendor+"&modelo="+modelo1+"&estado="+estado+"&codigo_sap="+codigo_sap+"&serial="+serial+"&marca="+marca+"&metodo="+metodo+"&responsable_disp="+responsable+
"&resolucion="+resolucion+"&rango_medicion="+rango_medicion+"&incertidumbre_fabricacion="+incertidumbre_fabricacion+"&error_maximo="+error_maximo+"&criterios_aceptacion="+criterios_aceptacion+"&condiciones_ambientales="+condiciones_ambientales+"&responsable_revisiones="+responsable_revisiones+"&trazabilidad="+trazabilidad+"&garantia_vigente="+garantia_vigente+"&ultima_fecha_cal="+ultima_fecha_cal+"&propiedad="+propiedad+"&tercero="+tercero+"&tipo_equipo="+tipo_equipo+"&clasificacion="+clasificacion+"&proxima_fecha_calibracion="+proxima_fecha_calibracion+"&validez_calibracion="+validez_calibracion+"&fecha_salida_operacion="+fecha_salida_operacion+"&fecha_envio_equipo="+fecha_envio_equipo+"&fecha_entrega_equipo="+fecha_entrega_equipo+"&fecha_puesta_produccion="+fecha_puesta_produccion+"&placa_activo="+placa_activo+"&ciudad="+ciudad+"&observaciones="+observaciones+"&datos_contacto="+datos_contacto;
}
</script>

</head>

<body>
<div align="center">
  <p><strong><font size="+3"><? echo $titulo; ?> Equipo en Investigacion</font></strong></p>
<br>

        <DIV id="rotate">
            <UL class="ui-tabs-nav">
                <LI class="ui-tabs-selected"><A href="<? echo $_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']; ?>#fragment-1"><SPAN>Información General</SPAN></A></LI>
                <LI class=""><A href="<? echo $_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']; ?>#fragment-2"><SPAN>Información Técnica</SPAN></A></LI>
                <LI class=""><A href="<? echo $_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']; ?>#fragment-3"><SPAN>Fechas de Actualización</SPAN></A></LI>
                <!--LI class=""><A href="http://stilbuero.de/jquery/tabs_3/rotate.html#fragment-3"><SPAN>Section 3</SPAN></A></LI-->
                <!--LI class=""><A href="http://stilbuero.de/jquery/tabs_3/rotate.html#fragment-4"><SPAN>Section 4</SPAN></A></LI>
                <LI class=""><A href="http://stilbuero.de/jquery/tabs_3/rotate.html#fragment-5"><SPAN>Section 5</SPAN></A></LI-->
            </UL>
            <DIV id="fragment-1" class="ui-tabs-panel ui-tabs-hide" style="">

             <table width="60%" border="0" cellspacing="2" cellpadding="1" align="center">
    <tr bgcolor="#FF0000">
      <td>
        <div align="center"><font color="#FFFFFF"><strong>Campo</strong></font></div></td>
      <td>
        <div align="center"><font color="#FFFFFF"><strong>Valor</strong></font></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Nombre del Elemento:</font> </td>
      <td> <div align="center">
          <input name="nombre" type="text" id="nombre" value="<? echo $nombre; ?>">
        </div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Tipo de Elemento:</font>
        </td>
      <td> <div align="center">
          <select name="tipoDispositivo" id="tipoDispositivo">

                <? $result = pg_query($sql);
                        $rows=pg_numrows($result);
                        for($i=0;$i<$rows;$i++){
                                $nombre = pg_result($result,$i,1);
                                $id = pg_result($result,$i,0);
                                $sel="";
                                if($tipo==$id)
                                        $sel="selected";

                                echo "<option value='$id' $sel;>$nombre</option>";
                        }

                ?>
            <!--option value="SIPLEXPRO" >SIPLEXPRO</option>
            <option value="FAST" >FAST</option>
            <option value="INDIGO">INDIGO</option-->
          </select>
        </div></td>
    </tr>

   <tr>
      <td bgcolor="#DDDBDB"><font size="3">Proveedor:</font></td>
      <td> <div align="center">
          <input name="vendor" type="text" id="vendor"  value="<? echo $vendor; ?>">
        </div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Modelo:</font></td>
      <td> <div align="center">
          <input type="text" name="modelo" id="modelo" value="<? echo $modelo; ?>">
        </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Marca:</font></td>
      <td> <div align="center">
          <input type="text" name="marca" id="marca" value="<? echo $marca; ?>">
        </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Estado:</font></td>
      <td> <div align="center">
          <select name="estado" id="estado">
            <option value="ACTIVO"  <? if ($estado=="ACTIVO") echo "selected"; ?>>ACTIVO</option>
	        <option value="PROGRAMADO"  <? if ($estado=="PROGRAMADO") echo "selected"; ?>>PROGRAMADO ACTIVO</option>
	        <option value="PROGRAMADO INACTIVO"  <? if ($estado=="PROGRAMADO INACTIVO") echo "selected"; ?>>PROGRAMADO INACTIVO</option>
            <option value="EN CALIBRACION"  <? if ($estado=="EN CALIBRACION") echo "selected"; ?>>EN CALIBRACION</option>
	        <option value="VENCIMIENTO CERCANO"  <? if ($estado=="VENCIMIENTO CERCANO") echo "selected"; ?>>VENCIMIENTO CERCANO</option>
            <option value="ENTREGA VENCIDA"  <? if ($estado=="ENTREGA VENCIDA") echo "selected"; ?>>ENTREGA VENCIDA</option>
            <option value="INACTIVO"  <? if ($estado=="INACTIVO") echo "selected"; ?> >INACTIVO</option>
            <option value="EN GARANTIA DE CALIBRACION"  <? if ($estado=="EN GARANTIA DE CALIBRACION") echo "selected"; ?> >EN GARANTIA DE CALIBRACIÓN</option>
            <option value="EN GARANTIA DE COMPRA"  <? if ($estado=="EN GARANTIA DE COMPRA") echo "selected"; ?> >EN GARANTIA DE COMPRA</option>
            <option value="HURTADO"  <? if ($estado=="HURTADO") echo "selected"; ?> >HURTADO</option>
            <option value="AVERIADO"  <? if ($estado=="AVERIADO") echo "selected"; ?> >AVERIADO</option>
            <option value="EXTRAVIADO"  <? if ($estado=="EXTRAVIADO") echo "selected"; ?> >EXTRAVIADO</option>
            <option value="EN EVALUACION POR GARANTIA DE CALIBRACION"  <? if ($estado=="EN EVALUACION POR GARANTIA DE CALIBRACION") echo "selected"; ?> >EN EVALUACION POR GARANTIA DE CALIBRACION</option>
            <option value="EN EVALUACION POR GARANTIA DE COMPRA"  <? if ($estado=="EN EVALUACION POR GARANTIA DE COMPRA") echo "selected"; ?> >EN EVALUACION POR GARANTIA DE COMPRA</option>
<!-->
AMARILLO - PROGRAMADO-despues del primer mensaje
VERDE    - EN CALIBRACION - despues de asignar la fecha de salida de operacion
NARANJA  - VENCIMIENTO CERCANO, SE COLOCA CUANDO se haga el calculo de los 10 dias contra la fecha ultima de calibracion..
ROJO     - ENTREGA VENCIDA: cuando se pasa del tiempo de ultimafechade calibracion y el equipo no se ha entegado al proveedor o el equipo aun esta operativo.
<-->

          </select>
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Activo:</font></td>
      <td> <div align="center">
          <input name="codigo_sap" type="text" id="codigo_sap"  value="<? echo $codigo_sap; ?>">
        </div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Placa</font></td>
      <td> <div align="center">
          <input name="responsable" type="text" id="placa_activo"  value="<? echo $placa_activo; ?>">
        </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Serial:</font></td>
      <td> <div align="center">
          <input name="serial" type="text" id="serial"  value="<? echo $serial; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Clasificación:</font></td>
      <td> <div align="center">
        <select id="clasificacion"><!-- estos datos se deben cambiar tambien en la pantalla de listadoGeneral -->
                <option value="0">Seleccione</option>
                <option value="EMC" <? if($clasificacion=="EMC") echo "selected"; ?>>Elemento de Medida Crítica(EMC)</option>
                <option value="EMSS" <? if($clasificacion=="EMSS") echo "selected"; ?>>Elemento de Medida para Soporte y Seguimiento(EMSS)</option>
                <option value="VMSS" <? if($clasificacion=="VMSS") echo "selected"; ?>>Variable de Medida para Soporte y Seguimiento(VMSS)</option>
                <option value="VMC" <? if($clasificacion=="VMC") echo "selected"; ?>>Variable de Medida Crítica(VMC)</option>

        </select>

        </div></td>
    </tr>

        <tr>
      <td bgcolor="#DDDBDB"><font size="3">Tipo de Sistema</font></td>
      <td> <div align="center">
        <select id="tipo_equipo">
                <option value="Analogico" <? if($propiedad=="Analogico") echo " 'selected'" ?>>Analogico</option>
                <option value="Digital" <? if($propiedad=="Digital") echo "'selected'" ?>>Digital</option>
        </select>

</div></td>
    </tr>



        <tr>
      <td bgcolor="#DDDBDB"><font size="3">Propiedad o Dominio:</font></td>
      <td> <div align="center">
        <select id="propiedad">
                <option value="Une" <? if($propiedad=="Une") echo "selected" ?>>Une</option>
                <option value="Tercero" <? if($propiedad=="Tercero") echo "selected" ?>>Tercero</option>
        </select>

</div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Nombre de Tercero:</font></td>
      <td> <div align="center"><input name="tercero" type="text" id="tercero"  value="<? echo $tercero; ?>"></div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Persona o Contrato Responsable del Elemento:</font></td>
      <td> <div align="center"><input name="responsable_revisiones" type="text" id="responsable_revisiones"  value="<? echo $responsable_revisiones; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Trazabilidad:</font></td>
      <td> <div align="center"><input name="trazabilidad" type="text" id="trazabilidad"  value="<? echo $trazabilidad; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Garantía Vigente:</font></td>
      <td> <div align="center"><input name="garantia_vigente" type="text" id="garantia_vigente"  value="<? echo $garantia_vigente; ?>"></div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Área Responsable:</font></td>
      <td> <div align="center">
          
			<select name="responsable" id="responsable">
<option value="Sub Multimedia y Serv. Avanzados"  <? if($responsable_disp=='Sub Multimedia y Serv. Avanzados') echo "selected"; ?> >Sub Multimedia y Serv. Avanzados</option>
<option value="Sub NOC"  <? if($responsable_disp=='Sub NOC') echo "selected"; ?> >Sub NOC</option>
<option value="Sub Operacion Regional Centro"  <? if($responsable_disp=='Sub Operacion Regional Centro') echo "selected"; ?> >Sub Operacion Regional Centro</option>
<option value="Sub Operacion Regional Centro Santanderes"  <? if($responsable_disp=='Sub Operacion Regional Centro Santanderes') echo "selected"; ?> >Sub Operacion Regional Centro Santanderes</option>
<option value="Sub Operacion Regional Nor-Occi"  <? if($responsable_disp=='Sub Operacion Regional Nor-Occi') echo "selected"; ?> >Sub Operacion Regional Nor-Occi</option>
<option value="Sub Operacion Regional Nor-Occi Eje Cafetero"  <? if($responsable_disp=='Sub Operacion Regional Nor-Occi Eje Cafetero') echo "selected"; ?> >Sub Operacion Regional Nor-Occi Eje Cafetero</option>
<option value="Sub Operacion Regional Norte"  <? if($responsable_disp=='Sub Operacion Regional Norte') echo "selected"; ?> >Sub Operacion Regional Norte</option>
<option value="Sub Operacion Regional Sur"  <? if($responsable_disp=='Sub Operacion Regional Sur') echo "selected"; ?> >Sub Operacion Regional Sur</option>
<option value="Area Operacion Infraestructura Nor-Occi"  <? if($responsable_disp=='Area Operacion Infraestructura Nor-Occi') echo "selected"; ?> >Area Operacion Infraestructura Nor-Occi</option>
			</select>
			<!--input name="responsable" type="text" id="responsable"  value="<x? echo $responsable_disp; ?x>"-->
        </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Ciudad:</font></td>
      <td> <div align="center">
          <input name="ciudad" type="text" id="ciudad"  value="<? echo $ciudad; ?>">
        </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Observaciones:</font></td>
      <td> <div align="center">
          <input name="observaciones" type="text" id="observaciones" value="<? echo $observaciones; ?>">
        </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Datos de Contacto:</font></td>
      <td> <div align="center">
			<select name="datos_contacto" id="datos_contacto">
			<option value="Astrid Janeth Galvis Carmona"  <? if($datos_contacto=='Astrid Janeth Galvis Carmona') echo "selected"; ?> >Astrid Janeth Galvis Carmona</option>
<option value="Carlos Arturo Ospino"  <? if($datos_contacto=='Carlos Arturo Ospino') echo "selected"; ?> >Carlos Arturo Ospino</option>
<option value="Haymer Rendon Prado"  <? if($datos_contacto=='Haymer Rendon Prado') echo "selected"; ?> >Haymer Rendon Prado</option>
<option value="Hector Alonso Betancur Toro"  <? if($datos_contacto=='Hector Alonso Betancur Toro') echo "selected"; ?> >Hector Alonso Betancur Toro</option>
<option value="Julio Roberto Mejia Perez"  <? if($datos_contacto=='Julio Roberto Mejia Perez') echo "selected"; ?> >Julio Roberto Mejia Perez</option>
<option value="Luisa Fernanda Marin Bermudez"  <? if($datos_contacto=='Luisa Fernanda Marin Bermudez') echo "selected"; ?> >Luisa Fernanda Marin Bermudez</option>
<option value="Marcos Antonio Cantillo Diaz"  <? if($datos_contacto=='Marcos Antonio Cantillo Diaz') echo "selected"; ?> >Marcos Antonio Cantillo Diaz</option>
<option value="Raul Antonio Carrillo Gonzalez"  <? if($datos_contacto=='Raul Antonio Carrillo Gonzalez') echo "selected"; ?> >Raul Antonio Carrillo Gonzalez</option>
			</select>
          <!--input name="datos_contacto" type="text" id="datos_contacto"  value="<x? echo $datos_contacto; ?x>"-->
        </div></td>
    </tr>



	</table>


	   </DIV>
            <DIV id="fragment-2" class="ui-tabs-panel" style="">
           

  <table width="60%" border="0" cellspacing="2" cellpadding="1" align="center">
    <tr bgcolor="#FF0000">
      <td>
        <div align="center"><font color="#FFFFFF"><strong>Campo</strong></font></div></td>
      <td>
        <div align="center"><font color="#FFFFFF"><strong>Valor</strong></font></div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Método de Verificación:</font></td>
      <td> <div align="center"><input type="text" name="metodo" id="metodo" value="<? echo $metodo; ?>">  </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Resolución:</font></td>
      <td> <div align="center"><input name="resolucion" type="text" id="resolucion"  value="<? echo $resolucion; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Rango de Medicion:</font></td>
      <td> <div align="center"><input name="rango_medicion" type="text" id="rango_medicion"  value="<? echo $rango_medicion; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Incertidumbre de Fabricación:</font></td>
      <td> <div align="center"><input name="incertidumbre_fabricacion" type="text" id="incertidumbre_fabricacion"  value="<? echo $incertidumbre_fabricacion; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Error Máximo:</font></td>
      <td> <div align="center"><input name="error_maximo" type="text" id="error_maximo"  value="<? echo $error_maximo; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Criterios de Aceptación:</font></td>
      <td> <div align="center"><input name="criterios_aceptacion" type="text" id="criterios_aceptacion"  value="<? echo $criterios_aceptacion; ?>"></div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Condiciones Ambientales:</font></td>
      <td> <div align="center"><input name="condiciones_ambientales" type="text" id="condiciones_ambientales"  value="<? echo $condiciones_ambientales; ?>"></div></td>
    </tr>

   </table>






	   </DIV>


            <DIV id="fragment-3" class="ui-tabs-panel" style="">
          



 <table width="60%" border="0" cellspacing="2" cellpadding="1" align="center">
    <tr bgcolor="#FF0000">
      <td>
        <div align="center"><font color="#FFFFFF"><strong>Campo</strong></font></div></td>
      <td>
        <div align="center"><font color="#FFFFFF"><strong>Valor</strong></font></div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Fecha de Última Calibración:</font></td>
      <td> <div align="center">
          <input name="ultima_fecha_cal" type="text" id="ultima_fecha_cal"  value="<? echo $ultima_fecha_cal; ?>">
        </div></td>
    </tr>

    <tr>
         <td bgcolor="#DDDBDB"><font size="3">Fecha de la Próxima Calibración:</font></td>
          <td> <div align="center">
          <input name="proxima_fecha_calibracion" type="text" id="proxima_fecha_calibracion"  value="<? echo $proxima_fecha_calibracion; ?>">
        </div></td>
    </tr>


    <tr>
         <td bgcolor="#DDDBDB"><font size="3">Periodo de Calibración:</font></td>
          <td> <div align="center">
          <select id="validez_calibracion">
                <option value="12" <? if($validez_calibracion=='12') echo "selected"; ?>>12 Meses</option>
                <option value="24" <? if($validez_calibracion=='24') echo "selected"; ?>>24 Meses</option>
                <option value="36" <? if($validez_calibracion=='36') echo "selected"; ?>>36 Meses</option>
        </select>

</div></td>
    </tr>

   
       <td bgcolor="#DDDBDB"><font size="3">Fecha Retiro de Operación:</font></td>
          <td> <div align="center">
          <input disabled="disabled" name="fecha_salida_operacion" type="text" id="fecha_salida_operacion"  value="<? echo $fecha_salida_operacion; ?>">
        </div></td>
    </tr>

    <tr>
         <td bgcolor="#DDDBDB"><font size="3">Fecha de Entrega al Laboratorio de Calibración:</font></td>
          <td> <div align="center">
          <input disabled="disabled"  name="fecha_envio_equipo" type="text" id="fecha_envio_equipo"  value="<? echo $fecha_envio_equipo; ?>">
        </div></td>
    </tr>

   <tr>
         <td bgcolor="#DDDBDB"><font size="3">Fecha de Recepción desde el Laboratorio de Calibración:</font></td>
          <td> <div align="center">
          <input disabled="disabled" name="fecha_entrega_equipo" type="text" id="fecha_entrega_equipo"  value="<? echo $fecha_entrega_equipo; ?>">
        </div></td>
    </tr>


    <tr>
         <td bgcolor="#DDDBDB"><font size="3">Fecha Entrada en Operación:</font></td>
          <td> <div align="center">
          <input disabled="disabled" name="fecha_puesta_produccion" type="text" id="fecha_puesta_produccion"  value="<? echo $fecha_puesta_produccion; ?>">
        </div></td>

    </table>

 
	   </DIV>

	</DIV>


	  <p><strong><font size="3">
  <?
  	if($op==""){
		echo "<input name='boton' type='button' id='boton' value='Guardar' onClick='javascript:guardar();'>";
	} else {
		echo "<input name='boton' type='button' id='boton' value='Editar' onClick='javascript:editar();'>";
	}
	?>
    </font></strong></p>
</div>
</body>
</html>

