<?php

include_once $root.'/include/db_credentials.php';

class Warehouse {
    private static $db;
    private $warehouseId;
    private $warehouseName;

    public function __construct($warehouseId) {
        $this->warehouseId = $warehouseId;
        $this->loadWarehouse($warehouseId);
        if (self::$db == null) {
            self::$db = new DB();
        }
    }

    public function __destruct() {
        self::$db = null;
    }

    public function loadWarehouse() {
        $sql = "SELECT * FROM warehouse WHERE warehouseId = ?";
        $params = array($this->warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->warehouseId = $row['warehouseId'];
            $this->warehouseName = $row['warehouseName'];
        }
    }

    public function getWarehouse() {
        $this->loadWarehouse();
        $warehouse = array(
            'warehouseId' => $this->warehouseId,
            'warehouseName' => $this->warehouseName
        );
        return $warehouse;
    }

    public static function GetWarehouses() {
        $sql = "SELECT * FROM warehouse";
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $warehouses = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $warehouse = array(
                'warehouseId' => $row['warehouseId'],
                'warehouseName' => $row['warehouseName']
            );
            array_push($warehouses, $warehouse);
        }
        return $warehouses;
    }

    public static function AddWarehouse($warehouseName) {
        $sql = "INSERT INTO warehouse (warehouseName) VALUES (?)";
        $params = array($warehouseName);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function UpdateWarehouse($warehouseId, $warehouseName) {
        $sql = "UPDATE warehouse SET warehouseName = ? WHERE warehouseId = ?";
        $params = array($warehouseName, $warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function DeleteWarehouse($warehouseId) {
        $sql = "DELETE FROM warehouse WHERE warehouseId = ?";
        $params = array($warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function GetProductsInventories($warehouseId) {
        $sql = "SELECT * FROM productInventory WHERE warehouseId = ?";
        $params = array($warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $productInventory = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $product = array(
                'productId' => $row['productId'],
                'warehouseId' => $row['warehouseId'],
                'quantity' => $row['quantity'],
                'price' => $row['price']
            );
            array_push($productInventory, $product);
        }
        return $productInventory;
    }
}