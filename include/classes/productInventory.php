<?php

include_once $root.'/include/db_credentials.php';

class ProductInventory {
    private $db;
    private $productId;
    private $warehouseId;
    private $quantity;
    private $price;

    public function __construct($productId, $warehouseId) {
        $db = new DB();
        if($this->db == null) {
            $this->db = $db->conn;
        }
        $this->loadProductInventory($productId, $warehouseId);
    }

    public function __destruct() {
        sqlsrv_close($this->db);
        $this->db = null;
    }

    public function loadProductInventory($productId, $warehouseId) {
        $sql = "SELECT * FROM productInventory WHERE productId = ? AND warehouseId = ?";
        $params = array($productId, $warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->productId = $row['productId'];
            $this->warehouseId = $row['warehouseId'];
            $this->quantity = $row['quantity'];
            $this->price = $row['price'];
        }
    }

    public function getProductInventory($productId, $warehouseId) {
        $this->loadProductInventory($productId, $warehouseId);
        $productInventory = array(
            'productId' => $this->productId,
            'warehouseId' => $this->warehouseId,
            'quantity' => $this->quantity,
            'price' => $this->price
        );
        return $productInventory;
    }

    public function getProductInventories() {
        $sql = "SELECT * FROM productInventory";
        $stmt = sqlsrv_query($this->db, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $productInventories = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $productInventory = array(
                'productId' => $row['productId'],
                'warehouseId' => $row['warehouseId'],
                'quantity' => $row['quantity'],
                'price' => $row['price']
            );
            array_push($productInventories, $productInventory);
        }
        return $productInventories;
    }

    public function updateProductInventory($productId, $warehouseId, $quantity, $price) {
        $sql = "UPDATE productInventory SET quantity = ?, price = ? WHERE productId = ? AND warehouseId = ?";
        $params = array($quantity, $price, $productId, $warehouseId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function insertProductInventory($productId, $warehouseId, $quantity, $price) {
        $sql = "INSERT INTO productInventory (productId, warehouseId, quantity, price) VALUES (?, ?, ?, ?)";
        $params = array($productId, $warehouseId, $quantity, $price);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteProductInventory($productId, $warehouseId) {
        $sql = "DELETE FROM productInventory WHERE productId = ? AND warehouseId = ?";
        $params = array($productId, $warehouseId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteProductInventories($productId) {
        $sql = "DELETE FROM productInventory WHERE productId = ?";
        $params = array($productId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteProductInventoriesByWarehouse($warehouseId) {
        $sql = "DELETE FROM productInventory WHERE warehouseId = ?";
        $params = array($warehouseId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getWarehouseId() {
        return $this->warehouseId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getPrice() {
        return $this->price;
    }
}