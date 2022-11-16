<?php
    require '../db_credentials.php';

    if (isset($_REQUEST['term'])) {
        $term = $_REQUEST['term'];
        // santize and clean term to prevent sql injection
        $term = filter_var($term, FILTER_SANITIZE_STRING);
        $sql = "SELECT * FROM product WHERE productName LIKE '%$term%' OR productDesc LIKE '%$term%'";
        $db = new DB();
        $result = sqlsrv_query($db->conn, $sql);
        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $products = array();
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $products[] = [
                'value' => $row['productName'],
                'id' => $row['productId']
            ];
        }
        echo json_encode($products);
    }
?>