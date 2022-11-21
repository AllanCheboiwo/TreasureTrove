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
        $this->productId = $productId;
        $this->warehouseId = $warehouseId;
        $this->LoadData();
    }

    public function __destruct() {
        sqlsrv_close($this->db);
        $this->db = null;
    }

    public function LoadData() {
        $sql = "SELECT * FROM productInventory WHERE productId = ? AND warehouseId = ?";
        $params = array($this->productId, $this->warehouseId);
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

    public function GetData() {
        $this->LoadData();
        $productInventory = array(
            'productId' => $this->productId,
            'warehouseId' => $this->warehouseId,
            'quantity' => $this->quantity,
            'price' => $this->price
        );
        return $productInventory;
    }

    public static function GetProductsInventory() {
        $sql = "SELECT * FROM productInventory";
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql);
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

    public static function GetProductsInventoryByID($productId, $warehouseId) {
        return new ProductInventory($productId, $warehouseId);
    }

    public static function UpdateProductInventory($productId, $warehouseId, $quantity, $price) {
        $sql = "UPDATE productInventory SET quantity = ?, price = ? WHERE productId = ? AND warehouseId = ?";
        $params = array($quantity, $price, $productId, $warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function AddProductInventory($productId, $warehouseId, $quantity, $price) {
        $sql = "INSERT INTO productInventory (productId, warehouseId, quantity, price) VALUES (?, ?, ?, ?)";
        $params = array($productId, $warehouseId, $quantity, $price);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function DeleteProductInventory($productId, $warehouseId) {
        $sql = "DELETE FROM productInventory WHERE productId = ? AND warehouseId = ?";
        $params = array($productId, $warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function DeleteProductInventories($productId) {
        $sql = "DELETE FROM productInventory WHERE productId = ?";
        $params = array($productId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public static function deleteProductInventoriesByWarehouse($warehouseId) {
        $sql = "DELETE FROM productInventory WHERE warehouseId = ?";
        $params = array($warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
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

    public static function IsOutOfStock($productId, $warehouseId) {
        $sql = "SELECT * FROM productInventory WHERE productId = ? AND warehouseId = ?";
        $params = array($productId, $warehouseId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            if ($row['quantity'] == 0) {
                return true;
            }
        }
        return false;
    }
}