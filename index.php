<?php
	session_start();

	$msg=$HTTP_GET_VARS["msg"];
?>

<html>
<head>

<script language="javascript">

	function ingresar(){
		var user=document.getElementById('login');
		var pwd=document.getElementById('pwd');

		<?php

		?>


		/*
		if(window.activexobject){
			var wshshell=new ActiveXObject("wscript.shell");
			var username=wshshell.ExpandEnvironmentStrings("%username%");
			alert("USERNAME: "+username);
		}
		else {
			var shell = WScript.CreateObject("WScript.Shell");
			var username=shell.Exec("echo %USERNAME%");
			alert("USERNAME: "+username);
			//WScript.StdOut.Write(exec.StdOut.ReadAll())


		}
		*/
		var url="./php/ingreso.php?user="+user.value+"&pwd="+pwd.value;

		location.href=url;
	}

</script>

</head>
<body>
<center><img src="img/cabecera.png" height="141" width="888"></center>

<br>
<center><h2>PLAN DE CALIBRACI&Oacute;N Y AJUSTE <br> PROCESO CONTROL METROL&Oacute;GICO</h2></center>
<br>

<center><a href="./php/listadoEquiposGeneral.php">
<font color="red" size="+2">Equipos de Medida en Control Metrológico</font>
</a></center>
<br>

<center><a href="./php/listadoEquiposGeneralSeguimiento.php">
<font color="red" size="+2">Equipos de Medida de Soporte y Seguimiento</font>
</a></center>
<br>


<table align="center" border="0">
<tr bgcolor="lightgray">
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td align="left">Usuario</td><td><input type="text" id="login" size="15"></td>
</tr>
<tr>
<td align="left">Contrasena</td><td><input type="password" id="pwd" size="15"></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="button" value="Ingresar" onclick="javascript:ingresar();"></td>
</tr>

</table>

<?php
	echo "<center><br><font color='red'>$msg</font></center>";

?>

<br>

</body>
</html>
