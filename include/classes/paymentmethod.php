<?
include_once $root.'/include/db_credentials.php';

class PaymentMethod {

    private static $db;
    private $paymentMethodId;
    private $customerId;
    private $paymentNumber;
    private $paymentType;
    private $cardName;
    private $paymentExpiryDate;

    public function __construct($pmId) {
        PaymentMethod::$db = new DB();
        $this->paymentMethodId = $pmId;
        $this->LoadInfo();
    }

    private function LoadInfo() {
        $sql = "SELECT * FROM paymentMethod WHERE paymentMethodId = ?";
        $params = array($this->paymentMethodId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $this->paymentMethodId = $row['paymentMethodId'];
        $this->customerId = $row['customerId'];
        $this->paymentNumber = $row['paymentNumber'];
        $this->paymentType = $row['paymentType'];
        $this->cardName = $row['cardName'];
        $this->paymentExpiryDate = $row['paymentExpiryDate'];
    }

    public static function AddPM($customerId, $paymentNumber, $paymentType, $paymentExpiryDate) {
        $res = [
            'status' => false,
            'message' => 'Invalid Items (add)'
        ];
        $sql = "INSERT INTO paymentMethod (customerId, paymentNumber, paymentType, paymentExpiryDate) VALUES (?, ?, ?, ?)";
        $params = array($customerId, $paymentNumber, $paymentType, $paymentExpiryDate);
        $db= new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            $res['status'] = true;
            $res['message'] = 'Added PaymentMethod';
        }
        return $res;
    }

    public function UpdatePM($paymentNumber, $paymentType, $paymentExpiryDate) {
        $res = [
            'status' => false,
            'message' => 'Invalid Items (update)'
        ];
        $sql = "UPDATE paymentMethod SET paymentNumber = ?, paymentType = ?, paymentExpiryDate = ? WHERE paymentMethodId = ?";
        $params = array($paymentNumber, $paymentType, $paymentExpiryDate, $this->paymentMethodId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            $res['status'] = true;
            $res['message'] = 'Updated PaymentMethod';
        }
        return $res;
    }

    public function UpdateOnlyPM($updateItem, $updateValue) {
        $res = [
            'status' => false,
            'message' => 'Invalid Items (updateitem)'
        ];
        $sql = "UPDATE paymentMethod SET " . $updateItem . " = ? WHERE paymentMethodId = ?";
        $params = array($updateValue, $this->paymentMethodId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            $res['status'] = true;
            $res['message'] = 'Updated PaymentMethod';
        }
        return $res;
    }

    public static function DeletePM($pmId) {
        $res = [
            'status' => false,
            'message' => 'Invalid Delete'
        ];
        $sql = "DELETE FROM paymentMethod WHERE paymentMethodId = ?";
        $db = new DB();
        $params = array($pmId);
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            $res['status'] = true;
            $res['message'] = 'Deleted PaymentMethod';
        }
        return $res;
    }

    public function GetItems() {
        $this->LoadInfo();
        return array(
            'paymentMethodId' => $this->paymentMethodId,
            'customerId' => $this->customerId,
            'paymentNumber' => $this->paymentNumber,
            'paymentType' => $this->paymentType,
            'paymentExpiryDate' => $this->paymentExpiryDate
        );
    }

    public static function GetPaymentMethodsByCustomer($customerId) {
        $sql = "SELECT * FROM paymentMethod WHERE customerId =?";
        $params = array($customerId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $result = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $result[] = array(
                'paymentMethodId' => $row['paymentMethodId'],
                'customerId' => $row['customerId'],
                'paymentNumber' => $row['paymentNumber'],
                'paymentType' => $row['paymentType'],
                'paymentExpiryDate' => $row['paymentExpiryDate'],
            );
        }
        sqlsrv_free_stmt($stmt);
        return $result;
    }

}