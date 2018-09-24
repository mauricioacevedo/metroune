<?php

	session_start();
	include_once('conexion.php');
	$conexion_bd=getConnectionGestionPedidos();

	$sql="SELECT nombre,( select a.nombre from tipos_dispositivos a where a.id=tipo) as disp,vendor,modelo,estado,id FROM dispositivo ";

	//echo $sql;

	$operacion=$HTTP_GET_VARS["operacion"];

	if($operacion=="doIngresarTipo"){
		$nombre=$HTTP_GET_VARS["nombre"];
		$sql="insert into tipos_dispositivos(nombre) values ('$nombre')";
		$result = pg_query($sql);

		$msg="Se ingreso la informacion del tipo satisfactoriamente.";
	}
	if($operacion=="eliminarTipo"){
		$id=$HTTP_GET_VARS["id_tipo"];
		$sql="delete from tipos_dispositivos where id=$id";
		$result = pg_query($sql);
                $msg="Se elimino el tipo satisfactoriamente.";

	}

?>

<html>
<head>
<title>Listado Dispositivos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
a:link { font-weight: bold; font-size: 15; text-decoration: none; color: blue }
a:visited { font-weight: bold; font-size: 15; text-decoration: none; color: blue }
a:hover {color: BLACK; font-weight: bold;}

</style>

<script language="javascript">

	function ingresarTipo(){

		var nombre=prompt("Ingrese El nombre del nuevo tipo de equipo");

		if(nombre==""||nombre == null){
			alert("Ingrese un valor para el nombre del tipo de equipo");
			return;
		}

		location.href="./listadoTiposDispositivos.php?operacion=doIngresarTipo&nombre="+nombre;
	}

	function eliminarTipo(id,nombre){

		if(confirm("Se eliminara el tipo "+nombre+". Desea continuar?")){
			location.href="./listadoTiposDispositivos.php?operacion=eliminarTipo&id_tipo="+id;
		}
	}
</script>
</head>

<body>

<p>&nbsp;</p>
<p align="center"><strong><font size="+3">Tipos de Equipos</font></strong></p>
<center><b><font color="red"><? echo $msg; ?></font></b></center>
<br>
<center><a href="javascript:ingresarTipo();" title="Nuevo Tipo">Nuevo Tipo</a></center>
<br>
<table width="50%" border="0" cellspacing="2" cellpadding="1" align="center">
  <tr bgcolor="#FF0000">
    <td>
      <div align="center"><font color="#FFFFFF"><strong>ID</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Nombre</strong></font></div></td>
    <td>
      <div align="center"><font color="#FFFFFF"><strong>Opcion</strong></font></div></td>
  </tr>
  <?
  	$sql="select id, nombre from tipos_dispositivos";
	$result = pg_query($sql);
  	$j=1;
  	$bg="#CCCCCC";
	$rows=pg_numrows($result);
  for($i=0;$i<$rows;$i++){
		$j=$j+1;
		$id = pg_result($result,$i,0);
    		$nombre = pg_result($result,$i,1);

  		if( $j % 2 == 0 ){
			$bg="#DDDBDB";
		} else {
			$bg="#FFFFFF";
		}
		  echo "<tr bgcolor='".$bg."'>";
			echo "<td><div align='center'>".$id."</div></td>";
			echo "<td><div align='center'>".$nombre."</div></td>";
			$operacion="<a href='javascript:eliminarTipo(\"$id\",\"$nombre\");'>Eliminar</a>";
			echo "<td><div align='center'>".$operacion."</div></td>";
		  echo "</tr>";

  	}//end while
  ?>
</table>
<p align="center"><strong><font size="+3"></font></strong></p>
</body>
</html>
