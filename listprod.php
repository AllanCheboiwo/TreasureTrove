<?php
	include 'include/db_credentials.php';

	/** Get product name to search for **/
	if (isset($_GET['pname'])){
		$pname = $_GET['pname'];
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>YOUR NAME Grocery</title>
</head>
<body>

<h1>Search for the products you want to buy:</h1>

<form method="get" action="listprod.php">
<input type="text" name="pname" size="50">
<input type="submit" value="Submit"><input type="reset" value="Reset"> (Leave blank for all products)
</form>
<?php

	/** $name now contains the search string the user entered
	 * Use it to build a query and print out the results. **/
	$sql = "SELECT * FROM product WHERE productName LIKE '%$pname%'";

	/** Create and validate connection **/
	$db = new DB();
	if( $db->conn === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
	/** Print out the ResultSet **/

	$pstmt = sqlsrv_query($db->conn, $sql, array());
	if($pstmt === false){
		die( print_r( sqlsrv_errors(), true));
	}

	/** 
	* For each product create a link of the form
	* addcart.php?id=<productId>&name=<productName>&price=<productPrice>
	* Note: As some product names contain special characters, you may need to encode URL parameter for product name like this: urlencode($productName)
	**/
	echo("<table border='1'>");
	echo("<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Add to Cart</th></tr>");
	while($row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC)){
		echo("<tr><td>".$row['productName']."</td><td>".number_format($row['productPrice'], 2)."</td><td><input type='text' size='3' value='1'></td><td><a href='addcart.php?pid=".$row['productId']."&pname=".urlencode($row['productName'])."&pprice=".$row['productPrice']."'>Add to Cart</a></td></tr>");
	}
	echo("</table>");
	/** Close connection **/
	sqlsrv_close($db->conn);
	/**
        Useful code for formatting currency:
	       number_format(yourCurrencyVariableHere,2)
     **/
?>

</body>
</html>