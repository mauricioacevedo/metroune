<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	$dispositivo=$HTTP_GET_VARS["dispositivo"];
	$operacion=$HTTP_GET_VARS["operacion"];

        $fecha = date("Y-m-d");


	if ($operacion=="consultaIndicador"){
		$indicador1=$HTTP_GET_VARS["indicador"];
		$year=$HTTP_GET_VARS["year"];
		$trimestre=$HTTP_GET_VARS["trimestre"];
		
		$fechaIni="2010-01-01";
		$fechaFin="2010-01-01";
		if($trimestre=="Q1"){
			$fechaIni="$year-01-01";
			$fechaFin="$year-03-31";
		}
        if($trimestre=="Q2"){
            $fechaIni="$year-04-01";
            $fechaFin="$year-06-30";
        }

        if($trimestre=="Q3"){
            $fechaIni="$year-07-01";
            $fechaFin="$year-09-30";
        }

        if($trimestre=="Q4"){
            $fechaIni="$year-10-01";
            $fechaFin="$year-12-31";
        }
		//echo "fechaIni: $fechaIni - fechaFin: $fechaFin";
		$sql1="select distinct responsable_disp from dispositivo";
		$result1 = pg_query($sql1);
		//$rows=pg_numrows($result1);
		
		if($indicador1=='I1'){
			//denominador
			$sql2="select count(*) from dispositivo where id in (select a.id_dispositivo from evento_por_dispositivo a where a.proxima_fecha_calibracion between '$fechaIni' and '$fechaFin' and a.id_evento != 1 )  ";


			//numerador
						
			$sql3="select count(*) from dispositivo where id in (select a.id_dispositivo from evento_por_dispositivo a where a.proxima_fecha_calibracion between '$fechaIni' and '$fechaFin' and (a.fecha_puesta_produccion != '' and a.fecha_puesta_produccion is not NULL  and a.fecha_puesta_produccion != ' ') and a.id_evento != 1)";
		
//NUEVO SQL: select id_dispositivo,proxima_fecha_calibracion,fecha_salida_operacion,(date(proxima_fecha_calibracion) - date(fecha_salida_operacion))  from evento_por_dispositivo a where a.proxima_fecha_calibracion between '2011-04-01' and '2011-06-30' and a.proxima_fecha_calibracion != '' and a.fecha_salida_operacion != '' having (date(proxima_fecha_calibracion) - date(fecha_salida_operacion)) between 0 and 200

	
			$sql3="select count(*)  from evento_por_dispositivo a where a.proxima_fecha_calibracion between '$fechaIni' and '$fechaFin' and a.proxima_fecha_calibracion != '' and a.fecha_salida_operacion != '' and (date(proxima_fecha_calibracion) - date(fecha_salida_operacion)) between 0 and 200 and a.id_evento != 1";

			//echo "<br>sql numerador: $sql3";
			//echo "<br>sql denominador: $sql2";
	
			$result2= pg_query($sql2);
			$result3= pg_query($sql3);

			$denominador=pg_result($result2,0,0);
			$numerador=pg_result($result3,0,0);
			
			//$arr[$responsable]=$numerador/$denominador*100;
			
			$resultado=$numerador/$denominador*100;

			$indicador= "<br><center><h3>Indicador 1: <b><font color='red'>Cumplimiento del plan de Calibracion:</font></b> ($numerador / $denominador): ($resultado %)</h3></center>";

			$sqlTodos="select (select b.serial from dispositivo b where b.id=a.id_dispositivo),a.ultima_fecha_calibracion,a.proxima_fecha_calibracion,a.fecha_salida_operacion,a.id_evento from evento_por_dispositivo a where a.proxima_fecha_calibracion between '$fechaIni' and '$fechaFin' and a.id_evento != 1 ";
			//echo "SQL de todos: $sqlTodos";			
			$result=pg_query($sqlTodos);

		    $j=1;
    		$bg="#CCCCCC";
    		$rows=pg_numrows($result);
    	
			$tabla="<table align='center'>";
            $tabla = $tabla."<tr bgcolor='black'>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Serial</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Fecha Ultima Calibracion</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Fecha Proxima Calibracion</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Fecha Retiro de Operacion</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Operacion</b></font></td>";
            $tabla = $tabla."</tr>";

	
			for($i=0;$i<$rows;$i++){
        		$j=$j+1;
        		$serial = pg_result($result,$i,0);
            	$ultima_fecha_calibracion = pg_result($result,$i,1);
            	$proxima_fecha_calibracion = pg_result($result,$i,2);
            	$fecha_salida_operacion = pg_result($result,$i,3);
            	$id_evento = pg_result($result,$i,4);


        		if( $j % 2 == 0 ){
            		$bg="#DDDBDB";
        		} else {
            		$bg="#FFFFFF";
        		}
				$tabla = $tabla."<tr bgcolor='".$bg."'>";
				$tabla = $tabla."<td align='center'>$serial</td>";
				$tabla = $tabla."<td align='center'>$ultima_fecha_calibracion</td>";
				$tabla = $tabla."<td align='center'>$proxima_fecha_calibracion</td>";
				$tabla = $tabla."<td align='center'>$fecha_salida_operacion</td>";
				$tabla = $tabla."<td align='center'><a href='javascript:editar(\"$id_evento\");'>editar</a></td>";
				$tabla = $tabla."</tr>";
			}
			
			$tabla= $tabla."</tabla>";


			$indicador= $indicador."<br>".$tabla;

		}
		if ($indicador1=='I2'){
			//este indicador se da por aca equipo, podriamos tomar un promedio..
			
			//1. obtener todos los equipos del trimestre analizado.
			$sql1="select (select b.serial from dispositivo b where b.id=a.id_dispositivo),a.fecha_puesta_produccion, a.fecha_salida_operacion,a.id_evento, a.id_dispositivo from evento_por_dispositivo a where a.fecha_puesta_produccion is not null and  a.fecha_puesta_produccion != '' and a.proxima_fecha_calibracion between '$fechaIni' and '$fechaFin' and a.id_evento != 1;";

			


			#$sql1="select (select b.serial from dispositivo b where b.id=a.id_dispositivo),a.fecha_puesta_produccion, a.fecha_salida_operacion,(date(a.fecha_puesta_produccion) - date(a.fecha_salida_operacion)),to_char(((date(a.fecha_puesta_produccion) - date(a.fecha_salida_operacion))::double precision/365)*100,'09D99'),a.id_evento from evento_por_dispositivo a where a.fecha_puesta_produccion is not null and  a.fecha_puesta_produccion != '' and a.proxima_fecha_calibracion between '$fechaIni' and '$fechaFin' and a.id_evento != 1;";

			//echo $sql1;
			$result1= pg_query($sql1);

            $indicador= "<br><center><h3>Indicador 2: <b><font color='red'>Oportunidad de la operación</font></b></h3></center>";


            $j=1;
            $bg="#CCCCCC";
            $rows=pg_numrows($result1);

            $tabla="<table align='center'>";

			$tabla=$tabla."<tr bgcolor='white'><td align='center' colspan=6><b>Nota:</b> los registros en color rojo pertenecen a equipos que solo tienen un evento de calibracion en el sistema.</td></tr>";


            $tabla = $tabla."<tr bgcolor='black'>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Serial</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Fecha de Entrada de operación</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Fecha Retiro de operación</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Calculo Indicador</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Resultado</b></font></td>";
            $tabla = $tabla."<td align='center'><font color='white'><b>Operacion</b></font></td>";
            $tabla = $tabla."</tr>";

           for($i=0;$i<$rows;$i++){
                $j=$j+1;
                $serial = pg_result($result1,$i,0);
                $fecha_entrada_operacion = pg_result($result1,$i,1);
                $fecha_retiro_operacion = pg_result($result1,$i,2);
                $id_evento = pg_result($result1,$i,3);
				$id_dispositivo = pg_result($result1,$i,4);

		//2. con el id del equipo debo consultar en los eventos todos los que tengan ese id, con el fin de tener los 2 ultimos y hacer el calculo

				$sql2="select id_dispositivo,fecha_puesta_produccion,fecha_salida_operacion from evento_por_dispositivo where id_dispositivo=$id_dispositivo AND fecha_puesta_produccion !='' AND fecha_salida_operacion != '' order by fecha_puesta_produccion DESC";

				$result2= pg_query($sql2);

				//3. tengo  los registros por equipo, deben haber 2 como minimo..

				$rows2=pg_numrows($result2);

				if($rows2>=2){
					//4. en las 2 primeras pocisiones esta la informacion para hacer la resta..

					$salida_operacion_actual = pg_result($result2,0,2);//fecha retiro operacion año actual
					$entrada_operacion_last_year= pg_result($result2,1,1);//fecha entrada a operacion año pasado

					$offset = strtotime($salida_operacion_actual . " UTC") - strtotime($entrada_operacion_last_year . " UTC");
					$resultado_resta = $offset/60/60/24;
					
					$fecha_entrada_operacion=$entrada_operacion_last_year;
					$fecha_retiro_operacion=$salida_operacion_actual;
					
					$resultado_indicador=$resultado_resta/365;
					$resultado_indicador=number_format($resultado_indicador*100, 2, '.', '');
					
					}else{
						//do some shit here!!
                	    $bg="#FF0000";
            	    	
						//como al parecer este equipo solo tiene un evento en el sistema coloco la informacion del evento que tenga
						$fecha_entrada_operacion=pg_result($result2,0,1);
	        	        $tabla = $tabla."<tr bgcolor='".$bg."'>";
    		            $tabla = $tabla."<td align='center'>$serial</td>";
	    	            $tabla = $tabla."<td align='center'>$fecha_entrada_operacion</td>";
            	    	$tabla = $tabla."<td align='center'>$fecha_retiro_operacion</td>";
            		    $tabla = $tabla."<td align='center'>--</td>";
        	        	$tabla = $tabla."<td align='center'>--</td>";
	    	            $tabla = $tabla."<td align='center'><a href='javascript:editar(\"$id_evento\");'>editar</a></td>";
		                $tabla = $tabla."</tr>";
						continue;
						//shit already done!!
					}


                if( $j % 2 == 0 ){
                    $bg="#DDDBDB";
                } else {
                    $bg="#FFFFFF";
                }
                $tabla = $tabla."<tr bgcolor='".$bg."'>";
                $tabla = $tabla."<td align='center'>$serial</td>";
                $tabla = $tabla."<td align='center'>$fecha_entrada_operacion</td>";
                $tabla = $tabla."<td align='center'>$fecha_retiro_operacion</td>";
                $tabla = $tabla."<td align='center'>($resultado_resta)/365 * 100</td>";
				$tabla = $tabla."<td align='center'>$resultado_indicador</td>";
                $tabla = $tabla."<td align='center'><a href='javascript:editar(\"$id_evento\");'>editar</a></td>";
                $tabla = $tabla."</tr>";
            }
			
            $tabla= $tabla."</tabla>";


            $indicador= $indicador."<br>".$tabla;

			




		}//end if

	}

	if ($operacion=="generarReporte") {
		$sql="select count(*), $variable from dispositivo group by $variable order by 1 asc";
		$result = pg_query($sql);
	}

?>

<html>
<head>
<title>Indicadores</title>
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
	location.href="./nuevoEvento2.php?operacion=editar&id_evento="+id;
}

function consultar(parametroBusqueda){
	var variable="<? echo $variable; ?>";
	var request="&paramSearch="+variable+"&valueSearch="+parametroBusqueda;

	location.href="./listadoDispositivos.php?operacion=consulta"+request;
	
}

function calcularIndicador() {
	var indicador=document.getElementById("indicador");
	indicador=indicador.options[indicador.selectedIndex].value;
	var year=document.getElementById("year");
	year=year.options[year.selectedIndex].value;
	var trimestre=document.getElementById("trimestre");
	trimestre=trimestre.options[trimestre.selectedIndex].value;

	var request="&indicador="+indicador+"&year="+year+"&trimestre="+trimestre;
	location.href="./indicadores.php?operacion=consultaIndicador"+request;
}


</script>

</head>

<body>
<p>&nbsp;</p>
<p align="center"><strong><font size="+3">Indicadores</font></strong></p>

<table align="center">
<tr><td>Indicador
<select id='indicador'>
<option value='I1' <? if ($indicador1=='I1') echo "selected"; ?> >Indicador 1</option>
<!--option value='I2' <? if ($indicador1=='I2') echo "selected"; ?>>Indicador 2</option-->
</select>

</td><td>&nbsp;Año: 
<select id='year'>
<option value='2010' <? if ($year=='2010') echo "selected"; ?>>2010</option>
<option value='2011' <? if ($year=='2011') echo "selected"; ?>>2011</option>
<option value='2012' <? if ($year=='2012') echo "selected"; ?>>2012</option>
<option value='2013' <? if ($year=='2013') echo "selected"; ?>>2013</option>
</select>

</td><td>&nbsp;Trimestre: 
<select id='trimestre'>
<option value='Q1' <? if ($trimestre=='Q1') echo "selected"; ?>>Trimestre 1</option>
<option value='Q2' <? if ($trimestre=='Q2') echo "selected"; ?>>Trimestre 2</option>
<option value='Q3' <? if ($trimestre=='Q3') echo "selected"; ?>>Trimestre 3</option>
<option value='Q4' <? if ($trimestre=='Q4') echo "selected"; ?>>Trimestre 4</option>
</select>


</td><td>&nbsp;<input type="button" value="buscar" onclick="javascript:calcularIndicador();"></td><tr>
<tr><td></td><tr>
</table>

<?echo $indicador;?>

</body>
</html>
