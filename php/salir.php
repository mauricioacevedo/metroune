<?php
        session_start();
	$nombre=$_SESSION['user_nombre'];
        $_SESSION['user_nombre']="";
        echo "<script language='javascript'>";
        $msg="El usuario $nombre ha salido del sistema.";
        echo "location.href='/metroune/index.php?msg=$msg'";
        echo "</script>";	

?>

