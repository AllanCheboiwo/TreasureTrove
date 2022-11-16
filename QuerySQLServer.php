<html>
<head>
<title>Query Microsoft SQL Server Using PHP</title>
</head>

<body>
<?php
	$username = "SA";
	$password = "YourStrong@Passw0rd";
	$database = "tempdb";
	$server = "db";
	$connectionInfo = array( "Database"=>$database, "UID"=>$username, "PWD"=>$password, "CharacterSet" => "UTF-8");

	$con = sqlsrv_connect($server, $connectionInfo);
	if( $con === false ) {
		die( print_r( sqlsrv_errors(), true));
	}

	$sql = "SELECT * FROM customer";
	$results = sqlsrv_query($con, $sql, array());
	echo("<table><tr><th>Name</th><th>Salary</th></tr>");
	while ($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
		echo("<tr><td>" . $row['firstName'] . "</td><td>" . $row['lastName'] . "</td></tr>");
	}
	echo("</table>");
?>
</body>
</html>
