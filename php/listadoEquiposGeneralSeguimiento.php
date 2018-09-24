<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();
	
//$conexion_bd=pg_connect("host=10.65.140.67 dbname=metroune user=postgres password=Animo");

	$sql="SELECT nombre,( select a.nombre from tipos_dispositivos a where a.id=tipo) as disp,modelo, serial, tipo_sistema, estado,dependencia_responsable, responsable_revisiones, ultima_fecha_revision, validez_revision, vida_util,id FROM disp_soporte_seguimiento order by serial ASC";

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
			
			//$sql="SELECT nombre,( select a.nombre from tipos_dispositivos a where a.id=tipo) as disp,vendor,modelo,estado,id,serial,responsable_disp,responsable_revisiones,fecha_ultima_calibracion,date(to_date(fecha_ultima_calibracion, 'yyyy-mm-dd')+ interval '1 year'),tipo_equipo,propiedad,clasificacion,propiedad_tercero FROM dispositivo where $paramSearch $comparador '$valueSearch'  order by serial ASC";
			
			$sql="SELECT nombre,( select a.nombre from tipos_dispositivos a where a.id=tipo) as disp,modelo, serial, tipo_sistema, estado,dependencia_responsable, responsable_revisiones, ultima_fecha_revision, validez_revision, vida_util,id FROM disp_soporte_seguimiento  where $paramSearch $comparador '$valueSearch' order by serial ASC";
			
			
			$valueSearch=$valueSearch2;

			#echo "SQL: $sql";

		}
			
		if($modo=="exportar"){
			//en $sql tengo la consulta lista!!!!
		

			$result = pg_query($sql);
    		$j=1;
    		$bg="#CCCCCC";
    		$rows=pg_numrows($result);

			$nombre_reporte="Reporte General Equipos Metrologia";

			header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=$nombre_reporte.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
			

			echo "<table border=1><tr>";

			echo "<th>Nombre del Elemento</th>";
			echo "<th>Tipo de elemento</th>";
			echo "<th>Modelo</th>";
			echo "<th>Serial</th>";
			echo "<th>Tipo de Sistema</th>";
			echo "<th>Estado</th>";
			echo "<th>Área/Dependencia Responsable</th>";
		        echo "<th>Responsable Revisiones</th>";
            		//echo "<th>Última Fecha de Revisión</th>";
            		//echo "<th>Validez de Revisión</th>";
            		//echo "<th>Vida Útil del Equipo</th>";
           

    		for($i=0;$i<$rows;$i++){
        		$j=$j+1;
        		$nombre = pg_result($result,$i,0);
            		$tipo = pg_result($result,$i,1);
            		$modelo = pg_result($result,$i,2);
            		$serial = pg_result($result,$i,3);
            		$tipo_sistema = pg_result($result,$i,4);
            		$estado = pg_result($result,$i,5);
            		$dependencia_responsable = pg_result($result,$i,6);
            		$responsable_revisiones= pg_result($result,$i,7);
            		$ultima_fecha_revision= pg_result($result,$i,8);
            		$validez_revision= pg_result($result,$i,9);
            		$vida_util=pg_result($result,$i,10);
        		$id = pg_result($result,$i,11);
        		

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


			echo "<tr bgcolor='".$bg."'>";
            //echo "<td><div align='center'>".$id."</div></td>";
            echo "<td><div align='center'>".$nombre."</div></td>";
            echo "<td><div align='center'>".$tipo."</div></td>";
            echo "<td><div align='center'>".$modelo."</div></td>";
            echo "<td>".$serial."</td>";
            echo "<td><div align='center'>".$tipo_sistema."</div></td>";
            echo "<td><div align='center'>".$estado."</div></td>";
            echo "<td><div align='center'>".$dependencia_responsable."</div></td>";
            echo "<td><div align='center'>".$responsable_revisiones."</div></td>";
            //echo "<td><div align='center'>".$ultima_fecha_revision."</div></td>";
            //echo "<td><div align='center'>".$validez_revision."</div></td>";
            //echo "<td><div align='center'>".$vida_util."</div></td>";
            //$operacion="<a href='./nuevoDispositivo.php?operacion=editar&id_dispositivo=".$id."'>Editar</a>";
            //$operacion="$operacion<br><a href='javascript:eliminarDispositivo(\"$id\",\"$nombre\")'>Eliminar</a>";
            //echo "<td><div align='center'>".$operacion."</div></td>";
          echo "</tr>";

    }//end while



		echo "</table>";	
		return;	
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
		location.href="./listadoEquiposGeneralSeguimiento.php?operacion="+operacion+"&paramSearch="+optionSearch+"&valueSearch="+parametroBusqueda.value+"&modo="+modo;
	}
	

	function abrirAyuda(){
		var win = window.open("../html/ayudaListadoGeneral.html","Ayuda","width=400, height=550, left=300, top=100,scrollbars=no, menubar=no, location=no, resizable=no");
		win.focus();
	}

	function exportarExcel(){

		location.href="./listadoEquiposGeneralSeguimiento.php?operacion=exportar";

	}

</script>

</head>

<body>
<center><img src="../img/cabecera.png" ></center>
<center><h2>PLAN DE CALIBRACI&Oacute;N Y AJUSTE <br> PROCESO CONTROL METROLOGICO</h2></center>
<p align="center"><strong><font size="+2">Listado de Elementos de Medida de Soporte y Seguimiento
</font> </strong><!--a href="javascript:abrirAyuda();"><img src="../img/interrogacion21.jpg" width="20" height="20"></a--></p>

<!--center><a href="nuevoDispositivo.php" title="Nuevo Equipo">Nuevo Equipo</a>&nbsp;&nbsp;&nbsp;<a href="listadoEquiposGeneralSeguimiento.php" title="Tipos de Equipo">Tipos de Equipo</a></center-->
<center> Buscar equipos por:
<select id="selectBusqueda">
<option value="todos" <? if($paramSearch=="todos") echo "selected";  ?> >Todos</option>
<option value="serial" <? if($paramSearch=="serial") echo "selected";  ?> >Serial</option>
<option value="modelo" <? if($paramSearch=="modelo") echo "selected";  ?> >Modelo</option>
<option value="nombre" <? if($paramSearch=="nombre") echo "selected";  ?> >Nombre del Elemento</option>
<option value="dependencia_responsable" <? if($paramSearch=="responsable_disp") echo "selected";  ?> >Área Responsable</option>
<option value="responsable_revisiones" <? if($paramSearch=="responsable_revisiones") echo "selected";  ?> >Persona o Contrato Responsable del Elemento</option>

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
    <td> <div align="center"><font color="#FFFFFF">Nombre del Elemento</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Tipo de elemento</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Modelo</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Serial</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Tipo de Sistema</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Estado</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Área Responsable</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Persona o Contrato Responsable del Elemento</font></div></td>
    <!--td> <div align="center"><font color="#FFFFFF">Última Fecha Revisión</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Validez de Revisión</font></div></td>
    <td> <div align="center"><font color="#FFFFFF">Vida Útil</font></div></td-->

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
	    	$modelo = pg_result($result,$i,2);
	    	$serial = pg_result($result,$i,3);
	    	$tipo_sistema = pg_result($result,$i,4);
	    	$estado = pg_result($result,$i,5);
		$dependencia_responsable = pg_result($result,$i,6);
		$responsable_revisiones= pg_result($result,$i,7);
		$ultima_fecha_revision= pg_result($result,$i,8);
		$validez_revision= pg_result($result,$i,9);
		$vida_util=pg_result($result,$i,10);
  		
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
		
/*
		$sql2="select dispositivo_id,fecha_proxima,ultima_fecha from programacion_automatica where dispositivo_id like '%$id;%' and evento='CALIBRACION'";
		$result2 = pg_query($sql2);
		$rows2=pg_numrows($result2);

		$proxima_fecha="INACTIVO";
		$ultima_fecha="INACTIVO";
		for($k=0;$k<$rows2;$k++){
			$lista_disp = pg_result($result2,$k,0);
			$proxima_fecha22 = pg_result($result2,$k,1);
			$ultima_fecha222 = pg_result($result2,$k,2);
			$rta=buscarDispositivo($lista_disp,$id);

			if($rta=="OK"){
				$proxima_fecha=$proxima_fecha22;
				$ultima_fecha=$ultima_fecha222;
				break;
			}

		}


*/


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
		  echo "<tr bgcolor='".$bg."'>";

			//echo "<td><div align='center'>".$id."</div></td>";
			echo "<td><div align='center'>".$nombre."</div></td>";
			echo "<td><div align='center'>".$tipo."</div></td>";
                        echo "<td><div align='center'>".$modelo."</div></td>";
			echo "<td><div align='center'>".$serial."</div></td>";
			echo "<td><div align='center'>".$tipo_sistema."</div></td>";
			echo "<td><div align='center'>".$estado."</div></td>";
                        echo "<td><div align='center'>".$dependencia_responsable."</div></td>";
                        echo "<td><div align='center'>".$responsable_revisiones."</div></td>";
			//echo "<td><div align='center'>".$ultima_fecha_revision."</div></td>";
			//echo "<td><div align='center'>".$validez_revision."</div></td>";
			//echo "<td><div align='center'>".$vida_util."</div></td>";
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
