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


	if ( $op == "guardar" ){

		$sql="INSERT INTO dispositivo (nombre, tipo, vendor, modelo, estado,codigo_sap,serial,marca,metodo_de_verificacion,responsable_disp,resolucion,rango_medicion,incertidumbre_fabricacion,error_maximo,criterios_aceptacion,condiciones_ambientales,responsable_revisiones,trazabilidad,garantia_vigente,fecha_ultima_calibracion,propiedad,propiedad_tercero,tipo_equipo,clasificacion) VALUES ('".$nombre."','".$tipo."','".$vendor."','".$modelo."','".$estado."','$codigo_sap','$serial','$marca','$metodo','$responsable_disp','$resolucion','$rango_medicion','$incertidumbre_fabricacion','$error_maximo','$criterios_aceptacion','$condiciones_ambientales','$responsable_revisiones','$trazabilidad','$garantia_vigente','$ultima_fecha_cal','$propiedad','$tercero','$tipo_equipo','$clasificacion');";

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
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:window.back();'>";
		echo "</p>";
		echo "</body>";
		echo "</html>";

		return;
	}

	$titulo="Nuevo";
	if ( $op == "editar" ){
		$id=$HTTP_GET_VARS["id_dispositivo"];
		$sql="select nombre, tipo, vendor, modelo, estado,codigo_sap,serial,marca,metodo_de_verificacion,responsable_disp,resolucion,rango_medicion,incertidumbre_fabricacion,error_maximo,criterios_aceptacion,condiciones_ambientales,responsable_revisiones,trazabilidad,garantia_vigente,fecha_ultima_calibracion,propiedad,propiedad_tercero,tipo_equipo,clasificacion  from dispositivo where id=".$id;
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

     	    $titulo="Editar";
	}

	if( $op == "editar2" ){
		$id=$HTTP_GET_VARS["id"];
		$sql="UPDATE dispositivo set nombre='".$nombre."', tipo='".$tipo."', vendor='".$vendor."', modelo='".$modelo."', estado='".$estado."', codigo_sap='$codigo_sap', serial='$serial', marca='$marca', metodo_de_verificacion='$metodo', responsable_disp='$responsable_disp',resolucion='$resolucion',rango_medicion='$rango_medicion',incertidumbre_fabricacion='$incertidumbre_fabricacion',error_maximo='$error_maximo',criterios_aceptacion='$criterios_aceptacion',condiciones_ambientales='$condiciones_ambientales',responsable_revisiones='$responsable_revisiones',trazabilidad='$trazabilidad',garantia_vigente='$garantia_vigente', fecha_ultima_calibracion='$ultima_fecha_cal', propiedad='$propiedad', propiedad_tercero='$tercero',tipo_equipo='$tipo_equipo',clasificacion='$clasificacion' ".
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
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:window.back();'>";
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

	var url="./nuevoDispositivo.php?operacion=editar2&id="+id;
	location.href=url+"&nombre="+nombre+"&tipo="+tipoDispositivo+"&vendor="+vendor+"&modelo="+modelo1+"&estado="+estado+"&codigo_sap="+codigo_sap+"&serial="+serial+"&marca="+marca+"&metodo="+metodo+"&responsable_disp="+responsable+
"&resolucion="+resolucion+"&rango_medicion="+rango_medicion+"&incertidumbre_fabricacion="+incertidumbre_fabricacion+"&error_maximo="+error_maximo+"&criterios_aceptacion="+criterios_aceptacion+"&condiciones_ambientales="+condiciones_ambientales+"&responsable_revisiones="+responsable_revisiones+"&trazabilidad="+trazabilidad+"&garantia_vigente="+garantia_vigente+"&ultima_fecha_cal="+ultima_fecha_cal+"&propiedad="+propiedad+"&tercero="+tercero+"&tipo_equipo="+tipo_equipo+"&clasificacion="+clasificacion;

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
"&resolucion="+resolucion+"&rango_medicion="+rango_medicion+"&incertidumbre_fabricacion="+incertidumbre_fabricacion+"&error_maximo="+error_maximo+"&criterios_aceptacion="+criterios_aceptacion+"&condiciones_ambientales="+condiciones_ambientales+"&responsable_revisiones="+responsable_revisiones+"&trazabilidad="+trazabilidad+"&garantia_vigente="+garantia_vigente+"&ultima_fecha_cal="+ultima_fecha_cal+"&propiedad="+propiedad+"&tercero="+tercero+"&tipo_equipo="+tipo_equipo+"&clasificacion="+clasificacion;
}
</script>

</head>

<body>
<div align="center">
  <p><strong><font size="+3"><? echo $titulo; ?> Equipo</font></strong></p>

  <table width="60%" border="0" cellspacing="2" cellpadding="1" align="center">
    <tr bgcolor="#FF0000">
      <td>
        <div align="center"><font color="#FFFFFF"><strong>Campo</strong></font></div></td>
      <td>
        <div align="center"><font color="#FFFFFF"><strong>Valor</strong></font></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Nombre del equipo:</font> </td>
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
            <option value="INACTIVO"  <? if ($estado=="INACTIVO") echo "selected"; ?> >INACTIVO</option>
          </select>
        </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Metodo de Verificacion:</font></td>
      <td> <div align="center"><input type="text" name="metodo" id="metodo" value="<? echo $metodo; ?>">  </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Código SAP:</font></td>
      <td> <div align="center">
          <input name="codigo_sap" type="text" id="codigo_sap"  value="<? echo $codigo_sap; ?>">
        </div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Serial:</font></td>
      <td> <div align="center">
          <input name="serial" type="text" id="serial"  value="<? echo $serial; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Resolucion:</font></td>
      <td> <div align="center"><input name="resolucion" type="text" id="resolucion"  value="<? echo $resolucion; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Rango de Medicion:</font></td>
      <td> <div align="center"><input name="rango_medicion" type="text" id="rango_medicion"  value="<? echo $rango_medicion; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Incertidumbre de Fabricacion:</font></td>
      <td> <div align="center"><input name="incertidumbre_fabricacion" type="text" id="incertidumbre_fabricacion"  value="<? echo $incertidumbre_fabricacion; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Error Maximo:</font></td>
      <td> <div align="center"><input name="error_maximo" type="text" id="error_maximo"  value="<? echo $error_maximo; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Criterios de Aceptacion:</font></td>
      <td> <div align="center"><input name="criterios_aceptacion" type="text" id="criterios_aceptacion"  value="<? echo $criterios_aceptacion; ?>"></div></td>
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
      <td bgcolor="#DDDBDB"><font size="3">Propiedad/Dominio:</font></td>
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
      <td bgcolor="#DDDBDB"><font size="3">Condiciones Ambientales:</font></td>
      <td> <div align="center"><input name="condiciones_ambientales" type="text" id="condiciones_ambientales"  value="<? echo $condiciones_ambientales; ?>"></div></td>
    </tr>
    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Responsable Revisiones:</font></td>
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
      <td bgcolor="#DDDBDB"><font size="3">Responsable Equipo:</font></td>
      <td> <div align="center">
          <input name="responsable" type="text" id="responsable"  value="<? echo $responsable_disp; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Última Fecha de Calibración:</font></td>
      <td> <div align="center">
          <input name="ultima_fecha_cal" type="text" id="ultima_fecha_cal"  value="<? echo $ultima_fecha_cal; ?>">
        </div></td>
    </tr>


  </table>
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

