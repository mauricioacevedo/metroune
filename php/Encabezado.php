<?php
	session_start();	
	$nombre=$_SESSION['user_nombre'];	

?>

<html>
<head>
<title>Metrologia UNE</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<body>

<table cellpadding="0" cellspacing="0" width="100%">
<tr><td align='center'><img src="../img/cabecera.png"></td></tr>
<!--tr><td><img src="../img/cabecera.png" height="141" width="90%"></td></tr-->
<tr bgcolor="#DDDBDB"><td align="right"><b><font color="blue"><? echo $nombre; ?></font></b> - <a href="./salir.php" target="_parent">Salir</a></td></tr>
</table>
</body>
</html>
