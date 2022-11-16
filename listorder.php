<?php
include 'include/main.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>YOUR NAME Grocery Order List</title>
</head>
<body>

<h1>Order List</h1>
<?php
/** Create connection, and validate that it connected successfully **/

/**
* Useful code for formatting currency:
* number_format(yourCurrencyVariableHere,2)
**/

/** Write query to retrieve all order headers **/

/** For each order in the results
* Print out the order header information
* Write a query to retrieve the products in the order
* - Use sqlsrv_prepare($connection, $sql, array( &$variable ) 
* and sqlsrv_execute($preparedStatement) 
* so you can reuse the query multiple times (just change the value of $variable)
* For each product in the order
* Write out product information 
**/
$sql = "SELECT * FROM ordersummary";
$db = new DB();
if( $db->conn === false ) {
	die( print_r( sqlsrv_errors(), true));
}
$pstmt = sqlsrv_query($db->conn, $sql, array());
if($pstmt === false){
	die( print_r( sqlsrv_errors(), true));
}
echo("<table border='1'>");
echo("<tr><th>Order ID</th><th>Customer ID</th><th>Order Date</th><th>Order Total</th></tr>");
while($row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC)){
	echo("<tr><td>".$row['orderId']."</td><td>".$row['customerId']."</td><td>".$row['orderDate']->format('Y-m-d')."</td><td>".number_format($row['orderTotal'], 2)."</td></tr>");
	$sql = "SELECT * FROM orderDetail WHERE orderId = ?";
	$pstmt2 = sqlsrv_prepare($db->conn, $sql, array(&$row['orderId']));
	if($pstmt2 === false){
		die( print_r( sqlsrv_errors(), true));
	}
	if(sqlsrv_execute($pstmt2) === false){
		die( print_r( sqlsrv_errors(), true));
	}
	echo("<tr><th>Product ID</th><th>Product Name</th><th>Product Price</th><th>Quantity</th><th>Subtotal</th></tr>");
	while($row2 = sqlsrv_fetch_array($pstmt2, SQLSRV_FETCH_ASSOC)){
		echo("<tr><td>".$row2['productId']."</td><td>".$row2['productName']."</td><td>".number_format($row2['productPrice'], 2)."</td><td>".$row2['quantity']."</td><td>".number_format($row2['subtotal'], 2)."</td></tr>");
	}
}
echo("</table>");



/** Close connection **/
?>

</body>
</html>

