<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();


	$sql="SELECT nombre,( select a.nombre from tipos_dispositivos a where a.id=tipo) as disp,vendor,modelo,estado,id,serial FROM dispositivos_en_proceso order by serial ASC";

		$operacion=$HTTP_GET_VARS["operacion"];


		if($operacion=="consulta"){
			$valueSearch=$HTTP_GET_VARS["valueSearch"];
			$paramSearch=$HTTP_GET_VARS["paramSearch"];

			$pos = strstr($valueSearch, '%');


			$comparador = "=";

			if($pos != false){//si entro por aca es porque encontro
					  // el char %, cambiamos por like
				$comparador="like";
			}

			$sql="SELECT nombre,( select a.nombre from tipos_dispositivos a where a.id=tipo) as disp,vendor,modelo,estado,id,serial FROM dispositivos_en_proceso where $paramSearch $comparador '$valueSearch'  order by serial ASC";
		}
		if($operacion=="normalizar"){
			$serial=$HTTP_GET_VARS["serial"];
			
			$sql1="insert into dispositivo (id, nombre, vendor, modelo, estado, serial, codigo_sap, tipo, marca , metodo_de_verificacion, responsable_disp, resolucion, rango_medicion, incertidumbre_fabricacion , error_maximo, criterios_aceptacion, condiciones_ambientales , responsable_revisiones, trazabilidad, garantia_vigente, fecha_ultima_calibracion, propiedad_tercero , propiedad , tipo_equipo , clasificacion , validez_calibracion , proxima_fecha_calibracion , fecha_salida_operacion, fecha_envio_equipo, fecha_entrega_equipo, fecha_puesta_produccion , placa_activo, ciudad, observaciones , datos_contacto) select id, nombre, vendor, modelo, estado, serial, codigo_sap, tipo, marca , metodo_de_verificacion, responsable_disp, resolucion, rango_medicion, incertidumbre_fabricacion , error_maximo, criterios_aceptacion, condiciones_ambientales , responsable_revisiones, trazabilidad, garantia_vigente, fecha_ultima_calibracion, propiedad_tercero , propiedad , tipo_equipo , clasificacion , validez_calibracion , proxima_fecha_calibracion , fecha_salida_operacion, fecha_envio_equipo, fecha_entrega_equipo, fecha_puesta_produccion , placa_activo, ciudad, observaciones , datos_contacto from dispositivos_en_proceso where serial='$serial'";

			//1. hago el insert en la tabla dispositivo
			$result1 = pg_query($sql1);

			//2. luego borro la informacion de la tabla de seguimiento
			$sql2="delete from dispositivos_en_proceso where serial='$serial'";
			$result2 = pg_query($sql2);
			
			$msg="Dispositivo con serial $serial se paso a la tabla principal de equipos.";
		}


?>

<html>
<head>
<title>Listado de Equipos En Investigacion</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
a:link { font-weight: bold; font-size: 15; text-decoration: none; color: blue }
a:visited { font-weight: bold; font-size: 15; text-decoration: none; color: blue }
a:hover {color: BLACK; font-weight: bold;}

</style>


<script language="javascript">
	

	function editarDispositivo(id){
		location.href="./nuevoDispositivoInvestigacion.php?operacion=editar&id_dispositivo="+id;
	}

	function eliminarDispositivo(id,nombre){
		if(confirm('Se eliminara el dispositivo '+nombre+' y toda su informacion relacionada.\nDesea continuar?')){
			location.href="./nuevoDispositivo.php?operacion=eliminar&id_dispositivo="+id;
		}
	}

	function consulta(){
		var parametroBusqueda=document.getElementById("parametroBusqueda");
		if(parametroBusqueda.value==""){
			alert("Ingrese un valor para consultar");
			parametroBusqueda.focus();
			return;
		}
		var selectBusqueda=document.getElementById("selectBusqueda");
		var optionSearch=selectBusqueda.options[selectBusqueda.selectedIndex].value;
		location.href="./listadoDispositivosEnProceso.php?operacion=consulta&paramSearch="+optionSearch+"&valueSearch="+parametroBusqueda.value;
	}

</script>

</head>

<body>

<p>&nbsp;</p>
<p align="center"><strong><font size="+3">Listado de Equipos de Medida en Investigaci&oacute;n</font></strong></p>
<p align="center"><font color="red"><? echo $msg; ?></p>
<!--center><a href="nuevoDispositivo.php" title="Nuevo Equipo">Nuevo Equipo</a>&nbsp;&nbsp;&nbsp;<a href="listadoTiposDispositivos.php" title="Tipos de Equipo">Tipos de Equipo</a></center-->
<br>
<center>
<select id="selectBusqueda">
<option value="serial" <? if($paramSearch=="serial") echo "selected";  ?> >Serial</option>
<option value="modelo" <? if($paramSearch=="modelo") echo "selected";  ?> >Modelo</option>
<option value="nombre" <? if($paramSearch=="nombre") echo "selected";  ?> >Nombre del Elemento</option>
<option value="vendor" <? if($paramSearch=="vendor") echo "selected";  ?> >Vendor</option>
<option value="estado" <? if($paramSearch=="estado") echo "selected";  ?> >Estado</option>
<option value="marca" <? if($paramSearch=="marca") echo "selected";  ?> >Marca</option>
<option value="responsable_disp" <? if($paramSearch=="responsable_disp") echo "selected";  ?> >Área Responsable</option>
<option value="responsable_revisiones" <? if($paramSearch=="responsable_revisiones") echo "selected";  ?> >Persona o Contrato Responsable del Elemento</option>

</select>
<input type="text" name="parametroBusqueda" id="parametroBusqueda" value="<? if ( isset($valueSearch) ) echo $valueSearch;  ?>">

<input type="button" name="doit" value="Consultar" onclick="javascript:consulta();">


</center>
<br>
<table width="100%" border="0" cellspacing="2" cellpadding="1" align="center">
  <tr bgcolor="#FF0000">
    <!--td>
      <div align="center"><font color="#FFFFFF"><strong>Item</strong></font></div></td-->
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Nombre del Elemento</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Tipo de elemento</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Modelo</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Estado</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Serial</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Operación</strong></font></div></td>
  </tr>
  <?
  	$result = pg_query($sql);
  	$j=1;
  	$bg="#CCCCCC";
	$rows=pg_numrows($result);
  	for($i=0;$i<$rows;$i++){
		$j=$j+1;
		$nombre = pg_result($result,$i,0);
    		$tipo = pg_result($result,$i,1);
	    	$vendor = pg_result($result,$i,2);
	    	$estado = pg_result($result,$i,4);
	    	$modelo = pg_result($result,$i,3);
	    	$id = pg_result($result,$i,5);
		$serial = pg_result($result,$i,6);

  		if( $j % 2 == 0 ){
			$bg="#DDDBDB";
		} else {
			$bg="#FFFFFF";
		}


                $bgrow="";


                if($estado=='PROGRAMADO'){
                        $bgrow="bgcolor='yellow'";
                }       
                        
                if($estado=='EN CALIBRACION'){
                        $bgrow="bgcolor=green";
                }       
                        
                        
                if($estado=='VENCIMIENTO CERCANO'){
                        $bgrow="bgcolor='orange'";
                }

                if($estado=='ENTREGA VENCIDA'){
                        $bgrow="bgcolor='red'";
                }
                if($estado=='INACTIVO'){
                        $bgrow="bgcolor='purple'";
                }



		  echo "<tr bgcolor='".$bg."'>";
			//echo "<td><div align='center'>".$id."</div></td>";
			echo "<td><div align='center'>".$nombre."</div></td>";
			echo "<td><div align='center'>".$tipo."</div></td>";
			echo "<td ><div align='center'>".$modelo."</div></td>";
			echo "<td $bgrow><div align='center'>".$estado."</div></td>";
			echo "<td><div align='center'>".$serial."</div></td>";
			$operacion="<a href='./listadoDispositivosEnProceso.php?operacion=normalizar&serial=".$serial."'>Normalizar</a>";
			$operacion="$operacion<br><a href='javascript:editarDispositivo(\"$id\")'>Editar</a>";
			echo "<td><div align='center'>".$operacion."</div></td>";
		  echo "</tr>";

  	}//end while
  ?>
</table>
<p align="center"><strong><font size="+3"></font></strong></p>
</body>
</html>
