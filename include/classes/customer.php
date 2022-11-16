<?php

require_once $root.'/include/db_credentials.php';

class Customer extends AUTH {
    public $customerId;
    public $firstName;
    public $lastName;
    public $email;
    public $phonenum;
    public $address;
    public $city;
    public $state;
    public $postalCode;
    public $country;
    public $UAFN; // address2
    private $password;
    public static $addressDelimeter = ' ~~~ ';

    public function __construct($userId) {
        if($userId) {
            $this->userId = $userId;
            $this->LoadInfo();
        }
    }

    public function GetPassword() {
        return $this->password;
    }

    public function LoadInfo() {
        if ($this->userId) {
            $sql = "SELECT * FROM customer WHERE userId = ?";
            $db = new DB();
            $pstmt = sqlsrv_query($db->conn, $sql, array($this->userId));
            if ($pstmt) {
                $row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC);
                $this->customerId = $row['customerId'];
                $this->firstName = $row['firstName'];
                $this->lastName = $row['lastName'];
                $this->email = $row['email'];
                $this->phonenum = $row['phonenum'];
                $this->address = explode(" ~~~ ", $row['address'])[0];
                $this->city = $row['city'];
                $this->state = $row['state'];
                $this->postalCode = $row['postalCode'];
                $this->country = $row['country'];
                $this->UAFN = explode(" ~~~ ", $row['address'])[1];
                $this->password = $row['password'];
                return true;
            } else {
                return false;
            }
        }
    }

    public function GetAddress() {
        $address = $this->address;
        $addr = array(
            'address' => $address,
            'UAFN' => $this->UAFN,
            'city' => $this->city,
            'state' => $this->state,
            'postalCode' => $this->postalCode,
            'country' => $this->country,
            'customerId' => $this->customerId
        );
        return $addr;
    }

    public function FullAddress() {
        $address = $this->GetAddress();
        // create address string
        return $address['address'] . ' ' . $address['UAFN'] . ', ' . $address['city'] . ', ' . $address['state'] . ' ' . $address['postalCode'] . ', ' . $address['country'];
    }

    /**
    * @param string $firstname
    * @param string $lastname
    * @param string $phonenum
    * @return mixed
    */
    public function updatePersonalInfo($firstname, $lastname, $phonenum) {
        $res = [
            'status' => false,
            'message' => 'Invalid personal information'
        ];
        try {
            $firstname = Funcs::ValidateField('name', $firstname, false);
            $lastname = Funcs::ValidateField('name', $lastname, false);
            $phonenum = Funcs::ValidateField('phone', $phonenum, false);
            // echo json_encode($firstname);
            // echo json_encode($lastname);
            // echo json_encode($phonenum);
            if ($firstname['valid'] && $lastname['valid'] && $phonenum['valid']) {
                $sql = "UPDATE customer SET firstName = ?, lastName = ?, phoneNum = ? WHERE userId = ?";
                $params = array($firstname['value'], $lastname['value'], $phonenum['value'], $this->userId);
                $db = new DB();
                $pstmt = sqlsrv_query($db->conn, $sql, $params);
                if ($pstmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                sqlsrv_free_stmt($pstmt);
                $res['status'] = true;
                $res['message'] = 'Personal information updated';
                $sdata = [
                    'user' => $this->userId,
                ];
                AUTH::UpdateSession($sdata);
            } else {
                $res['message'] = 'Invalid personal information';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $res;
    }

    public function updatePassword($password, $newPassword) {
        $res = [
            'status' => false,
            'message' => 'Invalid password'
        ];
        try {
            $passwordOld = Funcs::ValidateField('string', $password, true);
            $passwordNew = Funcs::ValidateField('string', $newPassword, false);
            if($passwordOld['valid'] && $passwordNew['valid']) {
                if (password_verify($passwordOld['value'], $this->GetPassword())) {
                    $sql = "UPDATE customer SET password = ? WHERE userId = ?";
                    $params = array($passwordNew['value'], $this->userId);
                    $db = new DB();
                    $stmt = sqlsrv_query($db->conn, $sql, $params);
                    if ($stmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                    sqlsrv_free_stmt($stmt);
                    $res['status'] = true;
                    $res['message'] = 'Password updated';
                    $sdata = [
                        'user' => $this->userId,
                    ];
                    AUTH::UpdateSession($sdata);
                } else {
                    $res['message'] = 'Password does not match our records';
                }
            } else {
                $res['message'] = 'Invalid password';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $res;
    }
    public function updateEmail($email, $password) {
        $res = [
            'status' => false,
            'message' => 'Invalid email or password'
        ];
        try {
            $email = Funcs::ValidateField('email', $email, false);
            $pass = Funcs::ValidateField('password', $password, false);
            if ($email['valid'] && $pass['valid']) {
                if (password_verify($pass, $this->GetPassword())) {
                    $sql = "UPDATE customer SET email = ? WHERE userId = ?";
                    $params = array($email, $this->userId);
                    $db = new DB();
                    $stmt = sqlsrv_query($db->conn, $sql, $params);
                    if ($stmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                    sqlsrv_free_stmt($stmt);
                    $res = [
                        'status' => true,
                        'message' => 'Email updated'
                    ];
                    $sdata = [
                        'user' => $this->userId,
                    ];
                    AUTH::UpdateSession($sdata);
                } else {
                    $res['message'] = 'Old Password does not match';
                }
            } else {
                $res['message'] = 'Invalid email or password';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $res;
    }

    public function UpdateAddress($address, $UAFN, $city, $state, $country, $postalCode) {
        $res = [
            'status' => false,
            'message' => 'Invalid address'
        ];
        try {
            $address = $address.Customer::$addressDelimeter.$UAFN;
            $sql = "UPDATE customer SET address = ?, city = ?, state = ?, country = ?, postalCode = ? WHERE userId = ?";
            $params = array($address, $city, $state, $country, $postalCode, $this->userId);
            $db = new DB();
            $stmt = sqlsrv_query($db->conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            sqlsrv_free_stmt($stmt);
            $res = [
                'status' => true,
                'message' => 'Address updated'
            ];
            $sdata = [
                'user' => $this->userId,
            ];
            AUTH::UpdateSession($sdata);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $res;
    }

    public static function GetCustomerById($customerId) {
        $sql = "SELECT * FROM customer WHERE customerId =?";
        $params = array($customerId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return new Customer($result['userid']);
    }

    public static function GetCustomerByUserId($userId) {
        $sql = "SELECT * FROM customer WHERE userId =?";
        $params = array($userId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $result = sqlsrv_fetch_array($stmt);
        return new Customer($result['userid']);
    }

    public function GetPaymentMethods() {
        return PaymentMethod::GetPaymentMethodsByCustomer($this->customerId);
    }

    public function FullName() {
        return $this->firstName.' '. $this->lastName;
    }

    public static function GetFullName($user) {
        $sql = "SELECT firstName, lastName FROM customer WHERE customerId = ?";
        $params = array($user);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $result = sqlsrv_fetch_array($stmt);
        return $result['firstName'].' '.$result['lastName'];
    }
}