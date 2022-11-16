<?php

include_once $root.'/include/db_credentials.php';

class Review {
    private static $db;
    public $reviewId;
    public $reviewRating;
    public $reviewDate;
    public $productId;
    public $customerId;
    public $reviewComment;

    public function __construct($reviewId) {
        self::$db = new DB();
        $this->reviewId = $reviewId;
        $this->LoadInfo();
    }

    public function __destruct() {
        self::$db = null;
        $this->reviewId = null;
        $this->reviewRating = null;
        $this->reviewDate = null;
        $this->productId = null;
        $this->customerId = null;
        $this->reviewComment = null;
    }

    public function LoadInfo() {
        $sql = "SELECT * FROM review WHERE reviewId = ?";
        $params = array($this->reviewId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->reviewId = $row['reviewId'];
            $this->reviewRating = $row['reviewRating'];
            $this->reviewDate = $row['reviewDate']->format('M d, Y');
            $this->productId = $row['productId'];
            $this->customerId = $row['customerId'];
            $this->reviewComment = $row['reviewComment'];
        }
    }

    public static function GetReview($reviewId) {
        $sql = "SELECT * FROM review WHERE reviewId = ?";
        $params = array($reviewId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            return new Review($row['reviewId']);
        } else {
            return null;
        }
    }

    public static function GetReviews() {
        $sql = "SELECT * FROM review";
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql);
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
    
    public static function GetReviewsByProductId($productId) {
        $sql = "SELECT * FROM review WHERE productId = ?";
        $params = array($productId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
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

    public function GetReviewsByCustomerId($customerId) {
        $sql = "SELECT * FROM review WHERE customerId = ?";
        $params = array($customerId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
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

    /**
     * $reviewRating, $reviewDate, $productId, $customerId, $reviewComment
     */
    public static function AddReview($data = array()) {
        $res = [
            'status' => false,
            'message' => 'Error adding review'
        ];
        if (isset($data['reviewRating']) && isset($data['reviewDate']) && isset($data['productId']) && isset($data['customerId']) && isset($data['reviewComment'])) {
            $sql = "INSERT INTO review (reviewRating, reviewDate, productId, customerId, reviewComment) VALUES (?, ?, ?, ?, ?)";
            $params = array($data['reviewRating'], $data['reviewDate'], $data['productId'], $data['customerId'], $data['reviewComment']);
            $db = new DB();
            $pstmt = sqlsrv_prepare($db->conn, $sql, $params);
            $pstmt = sqlsrv_execute($pstmt);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $res['status'] = true;
            $res['message'] = 'Review added successfully';
        } else {
            $res['message'] = 'Missing data';
        }
        return $res;
    }

    public static function UpdateReview($data = array()) {
        $res = [
            'status' => false,
            'message' => 'Error updating review'
        ];
        if (isset($data['reviewRating']) && isset($data['reviewComment']) && isset($data['reviewId'])) {
            $sql = "UPDATE review SET reviewRating = ?, reviewComment = ? WHERE reviewId = ?";
            $params = array($data['reviewRating'], $data['reviewComment'], $data['reviewId']);
            $db = new DB();
            $pstmt = sqlsrv_prepare($db->conn, $sql, $params);
            $pstmt = sqlsrv_execute($pstmt);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $res['status'] = true;
            $res['message'] = 'Review updated successfully';
        } else {
            $res['message'] = 'Missing data';
        }
        return $res;
    }

    public static function DeleteReview($reviewId) {
        $res = [
            'status' => false,
            'message' => 'Error deleting review'
        ];
        if (isset($reviewId)) {
            $sql = "DELETE FROM review WHERE reviewId = ?";
            $params = array($reviewId);
            $db = new DB();
            $pstmt = sqlsrv_prepare($db->conn, $sql, $params);
            $pstmt = sqlsrv_execute($pstmt);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $res['status'] = true;
            $res['message'] = 'Review deleted successfully';
        } else {
            $res['message'] = 'Missing data';
        }
        return $res;
    }

    public static function DisplayReview($reviewId) {
        $review = Review::GetReview($reviewId);
        $revCustomer = Customer::GetCustomerById($review->customerId);
        $revString = '';
        if ($review !== null) {
            $revString .= '<div id="review-'.$review->reviewId.'" class="col ttreview" style="margin: 10px 0;" data-review-id="'.$review->reviewId.'" data-review-by="'.$review->customerId.'" data-reviewed="'.$review->productId.'" data-rating="'.$review->reviewRating.'">
            <div class="card" style="border-style: none;border-radius: 8px;box-shadow: 0px 0px 3px #cccccc;">
                <div class="card-body">
                    <h5 class="card-title" style="color: #364652;">'.Customer::GetFullName($review->customerId).'
                    <span class="float-end">';
                    for ($i = 0; $i < 5; $i++) {
                        $ic = $i+1;
                        $revString .= '<i class="material-icons '.(($ic <= $review->reviewRating) ? "rated" : "").'">star</i>';
                    }
            $revString .= '</span>
                            </h5>
                            <h6 class="text-muted card-subtitle mb-2">Reviewed on '.$review->reviewDate.'</h6>
                            <textarea class="form-control" name="reviewComment" placeholder="'.$review->reviewComment.'" autocomplete="off" spellcheck="true" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #cccccc;resize: none" readonly>'.$review->reviewComment.'</textarea>';
                            // <p class="card-text">'.$review->reviewComment.'</p>';
            if (isset($_SESSION['customer']) && $_SESSION['customer']['customerId'] == $review->customerId) {
                $revString .= '<button class="btn btn-primary btn-sm edit-review" data-review-id="'.$review->reviewId.'" style="margin-top:8px; border: none; box-shadow: 0 0 3px #cccccc">Edit</button>
                    <button class="btn btn-danger btn-sm delete-review" data-review-id="'.$review->reviewId.'" style="margin-top:8px; border: none; box-shadow: 0 0 3px #cccccc">Delete</button>';
            }
            $revString .= '</div>
                    </div>
                </div>';
        }
        return $revString;
    }

    public function StarRating() {
        $rating = '';
        for ($i = 0; $i < 5; $i++) {
            $rating .= ' <i class="material-icons '. (($i+1 < $this->reviewRating) ? "rated" : "") .'">star</i>';
        }
        return $rating;
    }
}