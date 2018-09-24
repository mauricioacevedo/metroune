<?php

	session_start();
	include_once('conexion.php');
        include("excelwriter.inc.php");

	$conexion_bd=getConnectionGestionPedidos();
	//$conexion_bd=pg_connect("host=10.65.140.67 dbname=metroune user=postgres password=Animo");

	
	$sql="SELECT nombre,( select a.nombre from tipos_dispositivos a where a.id=tipo) as disp,vendor,modelo,estado,id,serial,responsable_disp,responsable_revisiones,fecha_ultima_calibracion,proxima_fecha_calibracion,tipo_equipo,propiedad,clasificacion,propiedad_tercero,validez_calibracion,to_char(date(fecha_ultima_calibracion),'MM'),ciudad,placa_activo,observaciones,datos_contacto,codigo_sap FROM dispositivo order by serial ASC";

			#echo "SQL: $sql";
		if (!isset($_GET['operacion'])) $_GET['operacion'] = '';
		$operacion=$_GET['operacion'];
		if (!isset($_GET['modo'])) $_GET['modo'] = '';
		$modo=$_GET["modo"];
			
		if($operacion=="consulta"){
			if (!isset($_GET['valueSearch'])) $_GET['valueSearch'] = '';
			$valueSearch=$_GET["valueSearch"];
			if (!isset($_GET['paramSearch'])) $_GET['paramSearch'] = '';
			$paramSearch=$_GET["paramSearch"];

			$pos = strstr($valueSearch, '%');


			$comparador = "=";

			if($pos != false){//si entro por aca es porque encontro
					  // el char %, cambiamos por like
				$comparador="ilike";
			}
			$valueSearch2=$valueSearch;
			if($paramSearch=="serial"){//si buscan un serial utilizo un like por la cuestion de los ceros de la izquierda
				$comparador="ilike";
				$valueSearch="%".$valueSearch;
			}
			

			$sql="SELECT nombre,( select a.nombre from tipos_dispositivos a where a.id=tipo) as disp,vendor,modelo,estado,id,serial,responsable_disp,responsable_revisiones,fecha_ultima_calibracion,proxima_fecha_calibracion,tipo_equipo,propiedad,clasificacion,propiedad_tercero,validez_calibracion,to_char(date(fecha_ultima_calibracion),'MM'),ciudad,placa_activo,observaciones,datos_contacto,codigo_sap FROM dispositivo where $paramSearch $comparador '$valueSearch'  order by serial ASC";
			$valueSearch=$valueSearch2;

			#echo "SQL: $sql";

		}
			
		if($modo=="exportar"){
			//en $sql tengo la consulta lista!!!!
		

			$result = pg_query($sql);
    		$j=1;
    		$bg="#CCCCCC";
    		$rows=pg_numrows($result);

			$nombre_reporte="./documentos/Reporte General Equipos Metrologia.xls";
			$filename=$nombre_reporte;
		        $fh = fopen($filename, 'w') or die("can't open file");
                        fclose($fh);
   	                if (file_exists($filename)) {//borro el archivo
                        	unlink($filename);
                	}
		
	               $excel=new ExcelWriter($nombre_reporte);
			
			//$myArr=array("Nombre del Elemento","Tipo de elemento","Modelo","Serial","Ciudad","Placa Activo","Mes de Programacion","Tipo de Sistema","Estado","Propiedad o Dominio","Clasificacion","Area Responsable","Persona o Contrato Responsable del Elemento","Fecha de Ultima Calibracion","Periodo de Calibracion","Observaciones","Datos de Contacto");

			$myArr=array("Nombre del Elemento","Tipo de elemento","Modelo","Serial","Placa","Activo","Tipo de Sistema","Clasificacion","Estado","Propiedad o Dominio","Area Responsable","Persona o Contrato Responsable del Elemento","Datos de Contacto","Ciudad","Fecha de Ultima Calibracion","Periodo de Calibracion","Mes de Programacion","Observaciones");


                $excel->writeLine($myArr);


    		for($i=0;$i<$rows;$i++){
        		$j=$j+1;
        		$nombre = pg_result($result,$i,0);
            	$tipo = pg_result($result,$i,1);
            	$vendor = pg_result($result,$i,2);
            	$modelo = pg_result($result,$i,3);
            	$estado = pg_result($result,$i,4);
            	$id = pg_result($result,$i,5);
            	$serial = pg_result($result,$i,6);
            	$responsable= pg_result($result,$i,7);
            	$responsable_rev= pg_result($result,$i,8);
            	$fecha_ultima_calibracion= pg_result($result,$i,9);
            	$fecha_proxima=pg_result($result,$i,10);
        		$tipo_equipo = pg_result($result,$i,11);
        		$propiedad = pg_result($result,$i,12);
        		$clasificacion = pg_result($result,$i,13);
        		$propiedad_tercero = pg_result($result,$i,14);
				$periodo_calibracion=pg_result($result,$i,15);
				$mes_calibracion=pg_result($result,$i,16);
				$ciudad=pg_result($result,$i,17);
				$placa_activo=pg_result($result,$i,18);
				$observaciones=pg_result($result,$i,19);
				$datos_contacto=pg_result($result,$i,20);
				$codigo_sap=pg_result($result,$i,21);

        		if( $j % 2 == 0 ){
            		$bg="#DDDBDB";
        		} else {
            		$bg="#FFFFFF";
        		}

		        //calculo de las fechas de ultima y proxima calibracion

        		//si esta condicion se cumple es porque el equipo es de un tercero
        		if($propiedad!='Une'){
		            $propiedad=$propiedad_tercero;
        		}



        		if($clasificacion=="EMC"){
            		$clasificacion="Elemento de Medida Crítica(EMC)";
        		}

        		if($clasificacion=="EMSS"){
                        $clasificacion="Elemento de Medida para Soporte y Seguimiento(EMSS)";
                }
        		if($clasificacion=="VMSS"){
                        $clasificacion="Variable de Medida para Soporte y Seguimiento(VMSS)";
                }
        		if($clasificacion=="VMC"){
                        $clasificacion="Variable de Medida Crítica(VMC)";
                }

          		$ultima_fecha=$fecha_ultima_calibracion;
          		$proxima_fecha=$fecha_proxima;
			
				if($estado=="PROGRAMADO"){
					$estado="PROGRAMADO ACTIVO";
				}	

			//$myArr=array($nombre,$tipo,$modelo,$serial,$ciudad,$placa_activo,$mes_calibracion,$tipo_equipo,$estado,$propiedad,$clasificacion,$responsable,$responsable_rev,$ultima_fecha,$periodo_calibracion,$observaciones,$datos_contacto);
			$myArr=array($nombre,$tipo,$modelo,$serial,$placa_activo,$codigo_sap,$tipo_equipo,$clasificacion,$estado,$propiedad,$responsable,$responsable_rev,$datos_contacto,$ciudad,$ultima_fecha,$periodo_calibracion,$mes_calibracion,$observaciones);

                        $excel->writeLine($myArr);


          	
    }//end while
	$excel->close();
       	echo "<script>location.href='$nombre_reporte';</script>";
       	return;
	

		//echo "</table>";	
		
		}


?>

<?php

function buscarDispositivo($dispositivos,$disp_find){
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





<title>Listado de Equipos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
a:link { font-weight: bold; font-size: 15; text-decoration: none; color: blue }
a:visited { font-weight: bold; font-size: 15; text-decoration: none; color: blue }
a:hover {color: BLACK; font-weight: bold;}

</style>


<script language="javascript">


	function eliminarDispositivo(id,nombre){
		if(confirm('Se eliminara el dispositivo '+nombre+' y toda su informacion relacionada.\nDesea continuar?')){
			location.href="./nuevoDispositivo.php?operacion=eliminar&id_dispositivo="+id;
		}
	}

	function consulta(modo2){
		var parametroBusqueda=document.getElementById("parametroBusqueda");
		var selectBusqueda=document.getElementById("selectBusqueda");

		var optionSearch=selectBusqueda.options[selectBusqueda.selectedIndex].value;

		if(parametroBusqueda.value=="" && optionSearch!="todos"){
			alert("Ingrese un valor para consultar");
			parametroBusqueda.focus();
			return;
		}


		var operacion="consulta";
		if (optionSearch =="todos") {
			operacion="";
		}
		var modo="";
		if(modo2=="exportar"){
			modo="exportar";
		}
		location.href="./listadoEquiposGeneral.php?operacion="+operacion+"&paramSearch="+optionSearch+"&valueSearch="+parametroBusqueda.value+"&modo="+modo;
	}
	

	function abrirAyuda(){
		var win = window.open("../html/ayudaListadoGeneral.html","Ayuda","width=850, height=650, left=300, top=100,scrollbars=no, menubar=no, location=no, resizable=no");
		win.focus();
	}

	function exportarExcel(){

		location.href="./listadoEquiposGeneral.php?operacion=exportar";

	}

</script>

</head>

<body>
<center><img src="../img/cabecera.png"></center>
<center><h2>PLAN DE CALIBRACI&Oacute;N Y AJUSTE <br> PROCESO CONTROL METROL&Oacute;GICO</h2></center>
<p align="center"><strong><font size="+2">Listado de Equipos</font> </strong><a href="javascript:abrirAyuda();"><img src="../img/interrogacion21.jpg" width="20" height="20"></a></p>

<!--center><a href="nuevoDispositivo.php" title="Nuevo Equipo">Nuevo Equipo</a>&nbsp;&nbsp;&nbsp;<a href="listadoTiposDispositivos.php" title="Tipos de Equipo">Tipos de Equipo</a></center-->
<center> Buscar equipos por:
<select id="selectBusqueda">
<option value="todos" <? if($paramSearch=="todos") echo "selected";  ?> >Todos</option>
<option value="serial" <? if($paramSearch=="serial") echo "selected";  ?> >Serial</option>
<option value="modelo" <? if($paramSearch=="modelo") echo "selected";  ?> >Modelo</option>
<option value="nombre" <? if($paramSearch=="nombre") echo "selected";  ?> >Nombre del Elemento</option>
<option value="estado" <? if($paramSearch=="estado") echo "selected";  ?> >Estado</option>
<option value="responsable_disp" <? if($paramSearch=="responsable_disp") echo "selected";  ?> >Área Responsable</option>
<option value="responsable_revisiones" <? if($paramSearch=="responsable_revisiones") echo "selected";  ?> >Persona o Contrato Responsable del Elemento</option>
<option value="placa_activo" <? if($paramSearch=="placa_activo") echo "selected";  ?> >Placa</option>
<option value="codigo_sap" <? if($paramSearch=="codigo_sap") echo "selected";  ?> >Activo</option>
<option value="propiedad" <? if($paramSearch=="propiedad") echo "selected";  ?> >Propiedad o Dominio</option>
<option value="datos_contacto" <? if($paramSearch=="datos_contacto") echo "selected";  ?> >Datos de Contacto</option>
<option value="ciudad" <? if($paramSearch=="ciudad") echo "selected";  ?> >Ciudad</option>
<option value="" <? if($paramSearch=="") echo "selected";  ?> ></option>


</select>
<input type="text" name="parametroBusqueda" id="parametroBusqueda" value="<? if ( isset($valueSearch) ) echo $valueSearch;  ?>">

<input type="button" name="doit" value="Consultar" onclick="javascript:consulta('consulta');">
&nbsp;&nbsp;
 <a href="javascript:consulta('exportar');">Exportar: <img src='../img/icono-excel.jpg' width="20" height="20"></a>
<input type="button" name="ba" value="Regresar" onclick="javascript:location.href='./../index.php';">

</center>
<br>
<table width="100%" border="0" cellspacing="2" cellpadding="1" align="center">
  <tr bgcolor="#FF0000">
    <!--td>
      <div align="center"><font color="#FFFFFF"><strong>Item</strong></font></div></td-->
    <td>
      <div align="center"><font color="#FFFFFF"><b>Nombre del Elemento</font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><b>Tipo de elemento</font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><b>Modelo</font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><b>Serial</font></div></td>

    <td><div align="center"><font color="#FFFFFF"><b>Placa</font></div></td>
    <td><div align="center"><font color="#FFFFFF"><b>Activo</font></div></td>

      <td><div align="center"><font color="#FFFFFF"><b>Tipo de Sistema</font></div></td>
      <td><div align="center"><font color="#FFFFFF"><b>Clasificación</font></div></td>
      <td><div align="center"><font color="#FFFFFF"><b>Estado</font></div></td>
      <td><div align="center"><font color="#FFFFFF"><b>Propiedad o Dominio</font></div></td>

<td> <div align="center"><font color="#FFFFFF"><b>Área Responsable</font></div></td>

<td> <div align="center"><font color="#FFFFFF"><b>Persona o Contrato Responsable del Elemento</font></div></td>
    <td><div align="center"><font color="#FFFFFF"><b>Datos de Contacto</b></font></div></td>
    <td><div align="center"><font color="#FFFFFF"><b>Ciudad</font></div></td> 

<td>  <div align="center"><font color="#FFFFFF"><b>Fecha de Última Calibración</font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><b>Periodo de Calibración<br><b>(MESES)</b></font></div></td>
    <td><div align="center"><font color="#FFFFFF"><b>Mes de Programacion</font></div></td>
    <td><div align="center"><font color="#FFFFFF"><b>Observaciones</font></div></td>

    <!--td>
      <div align="center"><font color="#FFFFFF"><strong>Operación</strong></font></div></td-->
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
	    	$modelo = pg_result($result,$i,3);
	    	$estado = pg_result($result,$i,4);
	    	$id = pg_result($result,$i,5);
			$serial = pg_result($result,$i,6);
			$responsable= pg_result($result,$i,7);
			$responsable_rev= pg_result($result,$i,8);
			$fecha_ultima_calibracion= pg_result($result,$i,9);
			$fecha_proxima=pg_result($result,$i,10);
		$tipo_equipo = pg_result($result,$i,11);
		$propiedad = pg_result($result,$i,12);
		$clasificacion = pg_result($result,$i,13);
		$propiedad_tercero = pg_result($result,$i,14);
		$periodo_calibracion= pg_result($result,$i,15);
            $mes_calibracion=pg_result($result,$i,16);
            $ciudad=pg_result($result,$i,17);
            $placa_activo=pg_result($result,$i,18);
            $observaciones=pg_result($result,$i,19);
            $datos_contacto=pg_result($result,$i,20);
			$codigo_sap=pg_result($result,$i,21);

  		if( $j % 2 == 0 ){
			$bg="#DDDBDB";
		} else {
			$bg="#FFFFFF";
		}
		
		//calculo de las fechas de ultima y proxima calibracion
		
		//si esta condicion se cumple es porque el equipo es de un tercero
		if($propiedad!='Une'){
			$propiedad=$propiedad_tercero;
		}
		
		if($clasificacion=="EMC"){
			$clasificacion="Elemento de Medida Crítica(EMC)";
		}

		if($clasificacion=="EMSS"){
                        $clasificacion="Elemento de Medida para Soporte y Seguimiento(EMSS)";
                }
		if($clasificacion=="VMSS"){
                        $clasificacion="Variable de Medida para Soporte y Seguimiento(VMSS)";
                }
		if($clasificacion=="VMC"){
                        $clasificacion="Variable de Medida Crítica(VMC)";
                }


                //codigo de colores para el estado:
                /*    
                AMARILLO - PROGRAMADO-despues del primer mensaje
                VERDE    - EN CALIBRACION - despues de asignar la fecha de salida de operacion
                NARANJA  - VENCIMIENTO CERCANO, SE COLOCA CUANDO se haga el calculo de los 10 dias contra la fecha ultima de calibracion..
                ROJO     - ENTREGA VENCIDA: cuando se pasa del tiempo de ultimafechade calibracion y el equipo no se ha entegado al proveedor o el equipo aun esta operativo
		INACTIVO - COLOR PURPURA.
                */
                $bgrow="";
                if($estado=='PROGRAMADO'){
                        $bgrow="bgcolor='yellow'";
						$estado=='PROGRAMADO ACTIVO';
                }

                if($estado=='EN CALIBRACION'){
                        $bgrow="bgcolor='green'";
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





		  $ultima_fecha=$fecha_ultima_calibracion;
		  $proxima_fecha=$fecha_proxima;
		  echo "<tr bgcolor='".$bg."'>";
			//echo "<td><div align='center'>".$id."</div></td>";
			echo "<td><div align='center'>".$nombre."</div></td>";
			echo "<td><div align='center'>".$tipo."</div></td>";
            echo "<td><div align='center'>".$modelo."</div></td>";
			echo "<td><div align='center'>".$serial."</div></td>";

			echo "<td><div align='center'>".$placa_activo."</div></td>";
		    echo "<td><div align='center'>".$codigo_sap."</div></td>";
			echo "<td><div align='center'>".$tipo_equipo."</div></td>";
            echo "<td><div align='center'>".$clasificacion."</div></td>";
			echo "<td $bgrow><div align='center'>".$estado."</div></td>";
            echo "<td><div align='center'>".$propiedad."</div></td>";

			echo "<td><div align='center'>".$responsable."</div></td>";
			echo "<td><div align='center'>".$responsable_rev."</div></td>";
			echo "<td><div align='center'>".$datos_contacto."</div></td>";
			echo "<td><div align='center'>".$ciudad."</div></td>";
			echo "<td><div align='center'>".$ultima_fecha."</div></td>";
			echo "<td><div align='center'>".$periodo_calibracion."</div></td>";
			echo "<td><div align='center'>".$mes_calibracion."</div></td>";
			echo "<td><div align='center'>".$observaciones."</div></td>";

			//$operacion="<a href='./nuevoDispositivo.php?operacion=editar&id_dispositivo=".$id."'>Editar</a>";
			//$operacion="$operacion<br><a href='javascript:eliminarDispositivo(\"$id\",\"$nombre\")'>Eliminar</a>";
			//echo "<td><div align='center'>".$operacion."</div></td>";
		  echo "</tr>";

  	}//end while
  ?>
</table>
<p align="center"><strong><font size="+3"></font></strong></p>
</body>
</html>
