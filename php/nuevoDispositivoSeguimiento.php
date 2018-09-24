<?php
	

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();



	$op=$HTTP_GET_VARS["operacion"];

	$nombre =$HTTP_GET_VARS["nombre"];
	$tipoDispositivo =$HTTP_GET_VARS["tipoDispositivo"];
	$modelo =$HTTP_GET_VARS["modelo"];
	$marca =$HTTP_GET_VARS["marca"];
	$estado =$HTTP_GET_VARS["estado"];
	$serial =$HTTP_GET_VARS["serial"];
	$nombre_proveedor_aliado =$HTTP_GET_VARS["nombre_proveedor_aliado"];
	$responsable_revisiones =$HTTP_GET_VARS["responsable_revisiones"];
	$dependencia_responsable =$HTTP_GET_VARS["dependencia_responsable"];
	$ciudad =$HTTP_GET_VARS["ciudad"];
	$asignado_a =$HTTP_GET_VARS["asignado_a"];
	$numero_placa =$HTTP_GET_VARS["numero_placa"];
	$dominio =$HTTP_GET_VARS["dominio"];
	$validez_revision =$HTTP_GET_VARS["validez_revision"];
	$vida_util =$HTTP_GET_VARS["vida_util"];
	$observaciones =$HTTP_GET_VARS["observaciones"];
	$ultima_fecha_revision =$HTTP_GET_VARS["ultima_fecha_revision"];
	$clasificacion =$HTTP_GET_VARS["clasificacion"];
	$tipo_sistema =$HTTP_GET_VARS["tipo_sistema"];

	if ( $op == "guardar" ){

		$sql="INSERT INTO disp_soporte_seguimiento (nombre, tipo, marca, modelo, serial, numero_placa, tipo_sistema, estado, dominio, nombre_proveedor_aliado, clasificacion, dependencia_responsable, responsable_revisiones, asignado_a, ciudad, ultima_fecha_revision, validez_revision, vida_util, observaciones) VALUES ('$nombre', '$tipoDispositivo', '$marca', '$modelo', '$serial', '$numero_placa', '$tipo_sistema', '$estado', '$dominio', '$nombre_proveedor_aliado', '$clasificacion', '$dependencia_responsable', '$responsable_revisiones', '$asignado_a', '$ciudad', '$ultima_fecha_revision', '$validez_revision', '$vida_util', '$observaciones');";

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
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:location.href=\"./listadoDispositivosSeguimiento.php\";'>";
		echo "</p>";
		echo "</body>";
		echo "</html>";

		return;
	}

	$titulo="Nuevo";
	if ( $op == "editar" ){
		$id=$HTTP_GET_VARS["id_dispositivo"];
		$sql="select nombre, tipo, marca, modelo, serial, numero_placa, tipo_sistema, estado, dominio, nombre_proveedor_aliado, clasificacion, dependencia_responsable, responsable_revisiones, asignado_a, ciudad, ultima_fecha_revision, validez_revision, vida_util, observaciones  from disp_soporte_seguimiento where id=".$id;
		$result = pg_query($sql);

		$nombre = pg_result($result,0,0);
    		$tipoDispositivo = pg_result($result,0,1);
	    	$marca = pg_result($result,0,2);
	    	$modelo = pg_result($result,0,3);
	    	$serial = pg_result($result,0,4);

	    	$numero_placa = pg_result($result,0,5);
	    	$tipo_sistema = pg_result($result,0,6);
	    	$estado = pg_result($result,0,7);
	    	$dominio =  pg_result($result,0,8);
	    	$nombre_proveedor_aliado=pg_result($result,0,9);

		$clasificacion=pg_result($result,0,10);
		$dependencia_responsable=pg_result($result,0,11);
		$responsable_revisiones=pg_result($result,0,12);
		$asignado_a=pg_result($result,0,13);
		$ciudad=pg_result($result,0,14);
		$ultima_fecha_revision=pg_result($result,0,15);
		$validez_revision=pg_result($result,0,16);
		$vida_util=pg_result($result,0,17);
		$observaciones=pg_result($result,0,18);

     	    $titulo="Editar";
	}

	if( $op == "editar2" ){
		$id=$HTTP_GET_VARS["id"];
		$sql="UPDATE disp_soporte_seguimiento set nombre='".$nombre."', tipo='".$tipoDispositivo."', modelo='".$modelo."', estado='".$estado."', serial='$serial', marca='$marca', numero_placa='$numero_placa',tipo_sistema='$tipo_sistema', dominio='$dominio',nombre_proveedor_aliado='$nombre_proveedor_aliado',clasificacion='$clasificacion',dependencia_responsable='$dependencia_responsable',responsable_revisiones='$responsable_revisiones',asignado_a='$asignado_a',ciudad='$ciudad',ultima_fecha_revision='$ultima_fecha_revision',validez_revision='$validez_revision',vida_util='$vida_util',observaciones='$observaciones' ".
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
		echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:location.href=\"./listadoDispositivosSeguimiento.php\";'>";
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
                echo "<input name='regresar' type='button' id='regresar' value='Regresar' onClick='javascript:location.href=\"./listadoDispositivosSeguimiento.php\";'>";
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

	$sql="select id,nombre from tipos_dispositivos";

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

        var nombre = document.getElementById("nombre").value;
        var tipoDispositivo = document.getElementById("tipoDispositivo").value;
        var modelo = document.getElementById("modelo").value;
        var marca = document.getElementById("marca").value;
        var estado = document.getElementById("estado").options[document.getElementById("estado").selectedIndex].value;
        var serial = document.getElementById("serial").value;
        var nombre_proveedor_aliado = document.getElementById("nombre_proveedor_aliado").value;
        var responsable_revisiones = document.getElementById("responsable_revisiones").value;
        var dependencia_responsable = document.getElementById("dependencia_responsable").value;
        var ciudad = document.getElementById("ciudad").value;
        var asignado_a = document.getElementById("asignado_a").value;
        var numero_placa = document.getElementById("numero_placa").value;
        var dominio = document.getElementById("dominio").value;
        var validez_revision = document.getElementById("validez_revision").value;
        var vida_util = document.getElementById("vida_util").value;
        var observaciones = document.getElementById("observaciones").value;
        var ultima_fecha_revision = document.getElementById("ultima_fecha_revision").value;
        var clasificacion = document.getElementById("clasificacion").value;
        var tipo_sistema = document.getElementById("tipo_sistema").value;


	var id='<? echo $id; ?>';

	var url="./nuevoDispositivoSeguimiento.php?operacion=editar2&id="+id;

	location.href=url+"&nombre="+nombre+"&tipoDispositivo="+tipoDispositivo+"&modelo="+modelo+"&estado="+estado+"&serial="+serial+"&marca="+marca+"&responsable_revisiones="+responsable_revisiones+"&clasificacion="+clasificacion+"&nombre_proveedor_aliado="+nombre_proveedor_aliado+"&dependencia_responsable="+dependencia_responsable+"&ciudad="+ciudad+"&asignado_a="+asignado_a+"&numero_placa="+numero_placa+"&dominio="+dominio+"&validez_revision="+validez_revision+"&vida_util="+vida_util+"&observaciones="+observaciones+"&ultima_fecha_revision="+ultima_fecha_revision+"&clasificacion="+clasificacion+"&tipo_sistema="+tipo_sistema;

}


function guardar(){

	var nombre = document.getElementById("nombre").value;
	var tipoDispositivo = document.getElementById("tipoDispositivo").value;
	var modelo = document.getElementById("modelo").value;
	var marca = document.getElementById("marca").value;
	var estado = document.getElementById("estado").value;
	var serial = document.getElementById("serial").value;
	var nombre_proveedor_aliado = document.getElementById("nombre_proveedor_aliado").value;
	var responsable_revisiones = document.getElementById("responsable_revisiones").value;
	var dependencia_responsable = document.getElementById("dependencia_responsable").value;
	var ciudad = document.getElementById("ciudad").value;
	var asignado_a = document.getElementById("asignado_a").value;
	var numero_placa = document.getElementById("numero_placa").value;
	var dominio = document.getElementById("dominio").value;
	var validez_revision = document.getElementById("validez_revision").value;
	var vida_util = document.getElementById("vida_util").value;
	var observaciones = document.getElementById("observaciones").value;
	var ultima_fecha_revision = document.getElementById("ultima_fecha_revision").value;
	var clasificacion = document.getElementById("clasificacion").value;
	var tipo_sistema = document.getElementById("tipo_sistema").value;


	var url="./nuevoDispositivoSeguimiento.php?operacion=guardar";

	//alert("codigo sap: "+codigo_sap);

	location.href=url+"&nombre="+nombre+"&tipoDispositivo="+tipoDispositivo+"&modelo="+modelo+"&estado="+estado+"&serial="+serial+"&marca="+marca+"&responsable_revisiones="+responsable_revisiones+"&clasificacion="+clasificacion+"&nombre_proveedor_aliado="+nombre_proveedor_aliado+"&dependencia_responsable="+dependencia_responsable+"&ciudad="+ciudad+"&asignado_a="+asignado_a+"&numero_placa="+numero_placa+"&dominio="+dominio+"&validez_revision="+validez_revision+"&vida_util="+vida_util+"&observaciones="+observaciones+"&ultima_fecha_revision="+ultima_fecha_revision+"&clasificacion="+clasificacion+"&tipo_sistema="+tipo_sistema;
}
</script>

</head>

<body>
<div align="center">
  <p><strong><font size="+3"><? echo $titulo; ?> Equipo Medida de Soporte y Seguimiento</font></strong></p>

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
				if($tipoDispositivo==$id)
					$sel="selected";

				echo "<option value='$id' $sel;>$nombre</option>";
        		}

		?>
            <!--option value="SIPLEXPRO" >SIPLEXPRO</option-->
          </select>
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
        <select id="tipo_sistema">
                <option value="Analogico" <? if($tipo_sistema=="Analogico") echo " 'selected'" ?>>Analogico</option>
                <option value="Digital" <? if($tipo_sistema=="Digital") echo "'selected'" ?>>Digital</option>
        </select>

</div></td>
    </tr>


<tr>
      <td bgcolor="#DDDBDB"><font size="3">Nombre Proveedor o Aliado:</font></td>
      <td> <div align="center"> <input name="nombre_proveedor_aliado" type="text" id="nombre_proveedor_aliado"  value="<? echo $nombre_proveedor_aliado; ?>">  </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Persona o Contrato Responsable del Elemento:</font></td>
      <td> <div align="center"><input name="responsable_revisiones" type="text" id="responsable_revisiones"  value="<? echo $responsable_revisiones; ?>"></div></td>
    </tr>

    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Área Responsable:</font></td>
      <td> <div align="center">
          <input name="dependencia_responsable" type="text" id="dependencia_responsable"  value="<? echo $dependencia_responsable; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Ciudad:</font></td>
      <td> <div align="center">
          <input name="ciudad" type="text" id="ciudad"  value="<? echo $ciudad; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Asignado a:</font></td>
      <td> <div align="center">
          <input name="asignado_a" type="text" id="asignado_a"  value="<? echo $asignado_a; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Numero de Placa:</font></td>
      <td> <div align="center">
          <input name="numero_placa" type="text" id="numero_placa"  value="<? echo $numero_placa; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Propiedad o Dominio:</font></td>
      <td> <div align="center">
          <input name="dominio" type="text" id="dominio"  value="<? echo $dominio; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Periodo de Calibración:</font></td>
      <td> <div align="center">
          <input name="validez_revision" type="text" id="validez_revision"  value="<? echo $validez_revision; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Vida Útil:</font></td>
      <td> <div align="center">
          <input name="vida_util" type="text" id="vida_util"  value="<? echo $vida_util; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Observaciones:</font></td>
      <td> <div align="center">
          <input name="observaciones" type="text" id="observaciones"  value="<? echo $observaciones; ?>">
        </div></td>
    </tr>


    <tr>
      <td bgcolor="#DDDBDB"><font size="3">Fecha de la Última Revisión:</font></td>
      <td> <div align="center">
          <input name="ultima_fecha_revision" type="text" id="ultima_fecha_revision"  value="<? echo $ultima_fecha_revision; ?>">
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

