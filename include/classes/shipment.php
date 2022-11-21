<?php

include_once $root.'/include/db_credentials.php';

class Shipment {
    private $db;
    private $shipmentId;
    public $shipmentDate;
    public $shipmentDesc;
    private $warehouseId;

    public function __construct($shipmentId) {
        $this->shipmentId = $shipmentId;
        $this->LoadData();
        if ($this->db == null) {
            $this->db = new DB();
        }
    }

    public function __destruct() {
        $this->db = null;
    }

    public function LoadData() {
        $sql = "SELECT * FROM shipment WHERE shipmentId = ?";
        $params = array($this->shipmentId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->shipmentId = $row['shipmentId'];
            $this->shipmentDate = $row['shipmentDate'];
            $this->shipmentDesc = $row['shipmentDesc'];
            $this->warehouseId = $row['warehouseId'];
        }
    }

    public function GetShipment() {
        $this->LoadData();
        $shipment = array(
            'shipmentDate' => $this->shipmentDate,
            'shipmentDesc' => $this->shipmentDesc,
            'warehouseId' => $this->warehouseId
        );
        return $shipment;
    }

    public static function GetShipments() {
        $sql = "SELECT * FROM shipment";
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $shipments = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $shipment = array(
                'shipmentId' => $row['shipmentId'],
                'shipmentDate' => $row['shipmentDate'],
                'shipmentDesc' => $row['shipmentDesc'],
                'warehouseId' => $row['warehouseId']
            );
            array_push($shipments, $shipment);
        }
        return $shipments;
    }

    public static function GetShipmentById($shipmentId) {
        $sql = "SELECT * FROM shipment WHERE shipmentId = ?";
        $params = array($shipmentId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $shipment = array(
                'shipmentId' => $row['shipmentId'],
                'shipmentDate' => $row['shipmentDate'],
                'shipmentDesc' => $row['shipmentDesc'],
                'warehouseId' => $row['warehouseId']
            );
            return $shipment;
        }
        return null;
    }

    public static function AddShipment($shipmentDate, $shipmentDesc, $warehouseId) {
        $sql = "INSERT INTO shipment (shipmentDate, shipmentDesc, warehouseId) VALUES (?, ?, ?)";
        $params = array($shipmentDate, $shipmentDesc, $warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function UpdateShipment($shipmentId, $shipmentDate, $shipmentDesc, $warehouseId) {
        $sql = "UPDATE shipment SET shipmentDate = ?, shipmentDesc = ?, warehouseId = ? WHERE shipmentId = ?";
        $params = array($shipmentDate, $shipmentDesc, $warehouseId, $shipmentId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function DeleteShipment($shipmentId) {
        $sql = "DELETE FROM shipment WHERE shipmentId = ?";
        $params = array($shipmentId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function getShipmentId() {
        return $this->shipmentId;
    }

    /**
     * Start Transaction
     * Retrieve all items in an ordersummary
     * create a new shipment
     * for each item verify sufficient quantity avaiable in warehouse1
     * if any item does not have sufficient quantity, cancel transaction and rollback. Otherwise, update productInventory for each intem
     * Commit transaction or rollback active transaction when needed
     * @param int $orderId
     * @return mixed array
     */
    public static function CreateShipment($orderId) {
        $db = new DB();
        sqlsrv_begin_transaction($db->conn);
        $sql = "SELECT * FROM orderSummary WHERE orderId = ?";
        $params = array($orderId);
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $orderSummary = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $item = array(
                'productId' => $row['productId'],
                'quantity' => $row['quantity']
            );
            array_push($orderSummary, $item);
        }
        $sql = "INSERT INTO shipment (shipmentDate, shipmentDesc, warehouseId) VALUES (?, ?, ?)";
        $params = array(date('Y-m-d'), 'Shipment for order '.$orderId, 1);
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $sql = "SELECT MAX(shipmentId) AS shipmentId FROM shipment";
        $stmt = sqlsrv_query($db->conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $shipmentId = $row['shipmentId'];
        foreach ($orderSummary as $item) {
            $sql = "SELECT * FROM productInventory WHERE productId = ? AND warehouseId = ?";
            $params = array($item['productId'], 1);
            $stmt = sqlsrv_query($db->conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            if ($row['quantity'] < $item['quantity']) {
                sqlsrv_rollback($db->conn);
                return false;
            }
            $sql = "UPDATE productInventory SET quantity = ? WHERE productId = ? AND warehouseId = ?";
            $params = array($row['quantity'] - $item['quantity'], $item['productId'], 1);
            $stmt = sqlsrv_query($db->conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }
        sqlsrv_commit($db->conn);
        return $shipmentId;
    }
}