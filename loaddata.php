<?php
	include 'include/db_credentials.php';
	$con = $db->conn;
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
	<?php
	echo("<h1>Connecting to database.</h1><p>");
	if( $con === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
	$fileName = "./data/SQLServer_orderdb.ddl";
	$file = file_get_contents($fileName, true);
	$lines = explode(";", $file);
	echo("<ol>");
	foreach ($lines as $line){
		$line = trim($line);
		if($line != ""){
			echo("<li>".$line . ";</li><br/>");
			sqlsrv_query($con, $line, array());
		}
	}
	sqlsrv_close($con);
	echo("</p><h2>Database loading complete!</h2>");
?>
</body>
</html>