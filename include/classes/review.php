<?php

include_once $root.'/include/db_credentials.php';

class Review {
    private $db;
    private $reviewId;
    public $reviewRating;
    private $reviewDate;
    private $productId;
    private $customerId;
    public $reviewComment;

    public function __construct($db) {
        $this->db = $db;
    }

    public function __destruct() {
        sqlsrv_close($this->db);
        $this->db = null;
    }

    public function loadReview($reviewId) {
        $sql = "SELECT * FROM review WHERE reviewId = ?";
        $params = array($reviewId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->reviewId = $row['reviewId'];
            $this->reviewRating = $row['reviewRating'];
            $this->reviewDate = $row['reviewDate'];
            $this->productId = $row['productId'];
            $this->customerId = $row['customerId'];
            $this->reviewComment = $row['reviewComment'];
        }
    }

    public function getReview($reviewId) {
        $this->loadReview($reviewId);
        $review = array(
            'reviewId' => $this->reviewId,
            'reviewRating' => $this->reviewRating,
            'reviewDate' => $this->reviewDate,
            'productId' => $this->productId,
            'customerId' => $this->customerId,
            'reviewComment' => $this->reviewComment
        );
        return $review;
    }

    public function getReviews() {
        $sql = "SELECT * FROM review";
        $stmt = sqlsrv_query($this->db, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $reviews = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $review = array(
                'reviewId' => $row['reviewId'],
                'reviewRating' => $row['reviewRating'],
                'reviewDate' => $row['reviewDate'],
                'productId' => $row['productId'],
                'customerId' => $row['customerId'],
                'reviewComment' => $row['reviewComment']
            );
            array_push($reviews, $review);
        }
        return $reviews;
    }
    
    public function getReviewsByProductId($productId) {
        $sql = "SELECT * FROM review WHERE productId = ?";
        $params = array($productId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $reviews = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $review = array(
                'reviewId' => $row['reviewId'],
                'reviewRating' => $row['reviewRating'],
                'reviewDate' => $row['reviewDate'],
                'productId' => $row['productId'],
                'customerId' => $row['customerId'],
                'reviewComment' => $row['reviewComment']
            );
            array_push($reviews, $review);
        }
        return $reviews;
    }

    public function getReviewsByCustomerId($customerId) {
        $sql = "SELECT * FROM review WHERE customerId = ?";
        $params = array($customerId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $reviews = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $review = array(
                'reviewId' => $row['reviewId'],
                'reviewRating' => $row['reviewRating'],
                'reviewDate' => $row['reviewDate'],
                'productId' => $row['productId'],
                'customerId' => $row['customerId'],
                'reviewComment' => $row['reviewComment']
            );
            array_push($reviews, $review);
        }
        return $reviews;
    }

    public function addReview($reviewRating, $reviewDate, $productId, $customerId, $reviewComment) {
        $sql = "INSERT INTO review (reviewRating, reviewDate,
        productId, customerId, reviewComment) VALUES (?, ?, ?, ?, ?)";
        $params = array($reviewRating, $reviewDate, $productId, $customerId, $reviewComment);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function updateReview($reviewId, $reviewRating, $reviewDate, $productId, $customerId, $reviewComment) {
        $sql = "UPDATE review SET reviewRating = ?, reviewDate = ?, productId = ?,
        customerId = ?, reviewComment = ? WHERE reviewId = ?";
        $params = array($reviewRating, $reviewDate, $productId, $customerId, $reviewComment, $reviewId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteReview($reviewId) {
        $sql = "DELETE FROM review WHERE reviewId = ?";
        $params = array($reviewId);
        $stmt = sqlsrv_query($this->db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }


}