<?php

include_once $root.'/include/db_credentials.php';

class Shipment {
    private $db;
    private $shipmentId;
    private $shipmentDate;
    private $shipmentDesc;
    private $warehouseId;

    public function __construct($db) {
        $this->db = $db;
    }

    public function __destruct() {
        sqlsrv_close($this->db);
        $this->db = null;
    }

    public function loadShipment($shipmentId) {
        $sql = "SELECT * FROM shipment WHERE shipmentId = ?";
        $params = array($shipmentId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
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

    public function getShipment($shipmentId) {
        $this->loadShipment($shipmentId);
        $shipment = array(
            'shipmentDate' => $this->shipmentDate,
            'shipmentStatus' => $this->shipmentStatus,
            'warehouseId' => $this->warehouseId
        );
        return $shipment;
    }

    public function getShipments() {
        $sql = "SELECT * FROM shipment";
        $stmt = sqlsrv_query($this->db, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $shipments = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $shipment = array(
                'shipmentId' => $row['shipmentId'],
                'shipmentDate' => $row['shipmentDate'],
                'shipmentDate' => $row['shipmentDesc'],
                'shipmentDate' => $row['warehouseId']
            );
            array_push($shipments, $shipment);
        }
        return $shipments;
    }

    public function addShipment($shipmentDate, $shipmentDesc, $warehouseId) {
        $sql = "INSERT INTO shipment (shipmentDate, shipmentDesc, warehouseId) VALUES (?, ?, ?)";
        $params = array($shipmentDate, $shipmentDesc, $warehouseId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function updateShipment($shipmentId, $shipmentDate, $shipmentDesc, $warehouseId) {
        $sql = "UPDATE shipment SET shipmentDate = ?, shipmentDesc = ?, warehouseId = ? WHERE shipmentId = ?";
        $params = array($shipmentDate, $shipmentDesc, $warehouseId, $shipmentId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteShipment($shipmentId) {
        $sql = "DELETE FROM shipment WHERE shipmentId = ?";
        $params = array($shipmentId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function getShipmentId() {
        return $this->shipmentId;
    }
}