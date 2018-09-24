<?php

	session_start();//$_SESSION['user_nombre'] = $nombre;
	include_once('conexion.php');

        $conexion_bd = getConnectionGestionPedidos();
	$login=$HTTP_GET_VARS["user"];
	$pwd=$HTTP_GET_VARS["pwd"];
	$rows=-1;
	
	$sql="select nombre from usuarios where usuario='$login' and password='$pwd'";
	echo $sql;
	$result = pg_query($sql);
        $rows=pg_numrows($result);
	echo "rows: $rows";
	if($rows<=0){
		//devolver a pagina inicial con mensaje
                echo "<script language='javascript'>";
		$msg="Usuario o contrasena incorrecto, por favor verifique su informacion.";
                echo "location.href='/metroune/index.php?msg=$msg'";
                echo "</script>";
	} else {
		$nombre = pg_result($result,0,0);
		$_SESSION['user_nombre'] = $nombre;

		echo "<script language='javascript'>";
		echo "location.href='/metroune/html/index2.php'";
		echo "</script>";
	}



?>

