<?php

include_once $root.'/include/db_credentials.php';

class OrderProduct {
    private static $db;
    public $orderId;
    public $productId;
    public $quantity;
    public $price;

    public function __construct($orderId, $productId) {
        $this->db = new DB();
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->LoadInfo();
    }

    public function __destruct() {
        if($this->db != null) {
            $this->db = null;
        }
    }

    public function LoadInfo() {
        $sql = "SELECT * FROM orderProduct WHERE orderId = ? AND productId = ?";
        $params = array($this->orderId, $this->productId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->orderId = $row['orderId'];
            $this->productId = $row['productId'];
            $this->quantity = $row['quantity'];
            $this->price = $row['price'];
        }
    }

    public function getOrderProductData() {
        $this->LoadInfo();
        return array(
            'orderId' => $this->orderId,
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price
        );
    }

    public static function GetProduct($orderId, $productId) {
        return new OrderProduct($orderId, $productId);
    }

    public function getOrderProducts() {
        $sql = "SELECT * FROM orderProduct";
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $orderProducts = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $orderProduct = array(
                'orderId' => $row['orderId'],
                'productId' => $row['productId'],
                'quantity' => $row['quantity'],
                'price' => $row['price']
            );
            array_push($orderProducts, $orderProduct);
        }
        return $orderProducts;
    }

    public function addOrderProduct($orderId, $productId, $quantity, $price) {
        $sql = "INSERT INTO orderProduct (orderId, productId, quantity, price) VALUES (?, ?, ?, ?)";
        $params = array($orderId, $productId, $quantity, $price);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function updateOrderProduct($orderId, $productId, $quantity, $price) {
        $sql = "UPDATE orderProduct SET quantity = ?, price = ? WHERE orderId = ? AND productId = ?";
        $params = array($quantity, $price, $orderId, $productId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteOrderProduct($orderId, $productId) {
        $sql = "DELETE FROM orderProduct WHERE orderId = ? AND productId = ?";
        $params = array($orderId, $productId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteOrderProducts($orderId) {
        $sql = "DELETE FROM orderProduct WHERE orderId = ?";
        $params = array($orderId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteOrderProductsByProduct($productId) {
        $sql = "DELETE FROM orderProduct WHERE productId = ?";
        $params = array($productId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function GetMostOrderedProducts($limit) {
        // List all orderproducts
        // count the productId as orderedNum, order by productId
        $items = array();
        $sql = "SELECT productId, SUM(quantity) as orderedTimes from orderproduct GROUP BY productId ORDER BY SUM(quantity) DESC";
        $db = new DB();
        $pstmt = sqlsrv_query($db->conn, $sql);
        if ($pstmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $count = 0;
        while ($row = sqlsrv_fetch_array($pstmt)) {
            array_push($items, $row);
            $count++;
            if ($count >= $limit) {
                break;
            }
        }
        return $items;
    }
}