<?php
    // ob_start();
	if (!isset($_SESSION)) {
		session_start();
	}

	class DB {
		public $dbuser = "SA";
		public $dbpass = "YourStrong@Passw0rd";
		public $database = "orders";
		public $server = "db";
		public $conn;

		public function __construct() {
			$this->conn = sqlsrv_connect($this->server, array(
				"Database" => $this->database,
				"UID" => $this->dbuser,
				"PWD" => $this->dbpass,
				"CharacterSet" => "UTF-8"
			));
		}

		public function __destruct() {
			if ($this->conn != null) {
				// sqlsrv_close($this->conn);
				$this->conn = null;
			}
		}
	
		public function query($sql, $params = array()) {
			$stmt = sqlsrv_query($this->conn, $sql, $params);
			if ($stmt === false) {
				die(print_r(sqlsrv_errors(), true));
			}
			return $stmt;
		}
		
		public function fetch($stmt) {
			return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		}

		public function fetchAll($stmt) {
			$rows = array();
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				array_push($rows, $row);
			}
			return $rows;
		}

		public function Connection() {
			return $this->conn;
		}
	}

	$db = new DB();
	$connectionInfo = array("Database"=>$db->database, "UID"=>$db->dbuser, "PWD"=>$db->dbpass, "CharacterSet" => "UTF-8");
	$conn = $db->conn;
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require($root.'/include/functions.php');
	require($root.'/include/classes/auth.php');
	require($root.'/include/classes/paymentmethod.php');
	require($root.'/include/classes/customer.php');
	require($root.'/include/classes/category.php');
	require($root.'/include/classes/product.php');
	require($root.'/include/classes/orderSummary.php');
	require($root.'/include/classes/orderProduct.php');
	require($root.'/include/classes/review.php');
	require($root.'/include/classes/shipment.php');
	require($root.'/include/classes/warehouse.php');
	require($root.'/include/classes/productInventory.php');
	require($root.'/include/classes/cart.php');
	if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
		$customer = new Customer($_SESSION['userId']);
		AUTH::$userId = $_SESSION['userId'];
		// set session variables if not set
		if (!isset($_SESSION['customer'])) {
			$_SESSION['customer'] = $customer;
		}
		if (!isset($_SESSION['userId'])) {
			$_SESSION['userId'] = $customer->userId;
		}
	}
	$cart = null;
	$id = null;
	$name = null;
	$price = null;
	if (isset($_SESSION['cart'])){
		$cart = $_SESSION['cart'];
	} else{ // No products currently in list.  Create a list.
		$cart = array();
	}