<?php

require_once $root.'/include/db_credentials.php';

class Category {
    
        private static $db;
        public $categoryId;
        public $categoryName;
        public $categoryDesc;
    
        public function __construct($categoryId) {
            $db = new DB();
            if(Category::$db == null) {
                Category::$db = $db->conn;
            }
            $this->categoryId = $categoryId;
            $this->loadCategory();
        }
    
        public function __destruct() {
            if(Category::$db != null) {
                Category::$db = null;
            }
        }
    
        public function loadCategory() {
            $sql = "SELECT * FROM category WHERE categoryId = ?";
            $params = array($this->categoryId);
            $db = new DB();
            $pstmt = sqlsrv_query($db->conn, $sql, $params);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC);
            if ($row != null) {
                $this->categoryId = $row['categoryId'];
                $this->categoryName = $row['categoryName'];
                $this->categoryDesc = $row['categoryDesc'];
            }
        }
    
        public static function GetCategory($categoryId) {
            $sql = "SELECT * FROM category WHERE categoryId = ?";
            $params = array($categoryId);
            $db = new DB();
            $pstmt = sqlsrv_query($db->conn, $sql, $params);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC);
            if ($row != null) {
                return new Category($row['categoryId']);
            }
        }

        public static function GetCategoryByName($categoryName) {
            $sql = "SELECT * FROM category WHERE categoryName =?";
            $params = array($categoryName);
            $db = new DB();
            $pstmt = sqlsrv_query($db->conn, $sql, $params);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $category = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC);
            return new Category($category['categoryId']);
        }
    
        public static function GetCategories() {
            $sql = "SELECT * FROM category";
            $db = new DB();
            $pstmt = sqlsrv_query($db->conn, $sql);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $categories = array();
            while($row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC)) {
                $categories[] = $row;
            }
            return $categories;
        }
    
        public static function AddCategory($categoryName, $categoryDesc) {
            $sql = "INSERT INTO category (categoryName, categoryDesc) VALUES (?, ?)";
            $params = array($categoryName, $categoryDesc);
            $db = new DB();
            $pstmt = sqlsrv_prepare($db->conn, $sql, $params);
            $pstmt = sqlsrv_execute($pstmt);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }
    
        public static function UpdateCategory($categoryId, $categoryName, $categoryDesc) {
            $sql = "UPDATE category SET categoryName = ?, categoryDesc = ? WHERE categoryId = ?";
            $params = array($categoryName, $categoryDesc, $categoryId);
            $db = new DB();
            $pstmt = sqlsrv_prepare($db->conn, $sql, $params);
            $pstmt = sqlsrv_execute($pstmt);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }

        public static function DeleteCategory($categoryId) {
            $sql = "DELETE FROM category WHERE categoryId = ?";
            $params = array($categoryId);
            $db = new DB();
            $pstmt = sqlsrv_prepare($db->conn, $sql, $params);
            $pstmt = sqlsrv_execute($pstmt);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }

        public function getCategoryId() {
            return $this->categoryId;
        }
}