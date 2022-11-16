<?php

include_once $root.'/include/db_credentials.php';

class Warehouse {
    private $db;
    private $warehouseId;
    private $warehouseName;

    public function __construct($db) {
        $this->db = $db;
    }

    public function __destruct() {
        sqlsrv_close($this->db);
        $this->db = null;
    }

    public function loadWarehouse($warehouseId) {
        $sql = "SELECT * FROM warehouse WHERE warehouseId = ?";
        $params = array($warehouseId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->warehouseId = $row['warehouseId'];
            $this->warehouseName = $row['warehouseName'];
        }
    }

    public function getWarehouse($warehouseId) {
        $this->loadWarehouse($warehouseId);
        $warehouse = array(
            'warehouseName' => $this->warehouseName
        );
        return $warehouse;
    }

    public function getWarehouses() {
        $sql = "SELECT * FROM warehouse";
        $stmt = sqlsrv_query($this->db, $sql);
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

    public function addWarehouse($warehouseName) {
        $sql = "INSERT INTO warehouse (warehouseName) VALUES (?)";
        $params = array($warehouseName);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function updateWarehouse($warehouseId, $warehouseName) {
        $sql = "UPDATE warehouse SET warehouseName = ? WHERE warehouseId = ?";
        $params = array($warehouseName, $warehouseId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteWarehouse($warehouseId) {
        $sql = "DELETE FROM warehouse WHERE warehouseId = ?";
        $params = array($warehouseId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }
}