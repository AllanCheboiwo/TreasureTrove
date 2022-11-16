<?php

require_once $root.'/include/db_credentials.php';

class AUTH extends DB {
    private static $db;
    public static $userId;
    public static $customer;

    public function __construct() {
        if (AUTH::$db == null) {
            AUTH::$db = $this->conn;
        }
    }

    public static function Logout() {
        $res = [
            'status' => false,
            'message' => 'No Action'
        ];
        if ($_SESSION['loggedIn']) {
            AUTH::$userId = null;
            AUTH::$customer = null;
            $_SESSION['loggedIn'] = false;
            $_SESSION['customer'] = null;
            $_SESSION['userId'] = null;
            // session_destroy();
            $res['status'] = true;
            $res['message'] = 'Logged Out';
        } else {
            $res['message'] = 'Not Logged In';
        }
        return $res;
    }

    public static function Login($data = array()) {
        $res = [
           'status' => false,
           'message' => 'No Action'
        ];
        if (isset($data['user'], $data['password'])) {
            // if user contains @ symbol, then it is an email else it's a userId
            // Filter Input with the Funcs Class
            $user = $data['user'];
            $pass = $data['password'];
            if (strpos($user, '@') !== false) {
                $user = Funcs::ValidateField("email", $user, false);
            } else {
                $user = Funcs::ValidateField("string", $user, false);
            }
            $user = $user['value'];
            // $pass = Funcs::ValidateField("password", $pass, true);
            // $pass = $pass['value'];
            $sdata = [
                'user' => $user
            ];
            $customer = AUTH::FindCustomer($sdata);
            $spass = $customer['customer']['password'];
            // echo json_encode($customer);
            if ($customer['status']) {
                // verify password
                if (password_verify($pass, $spass)) {
                    // set session variables
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['userId'] = $customer['customer']['userid'];
                    $_SESSION['customer'] = $customer['customer'];
                    AUTH::$userId = $customer['customer']['userId'];
                    AUTH::$customer = $customer['customer'];
                    $res['status'] = true;
                    $res['message'] = 'Logged In';
                } else {
                    $res['message'] = 'Invalid Password';
                }
            } else {
                $res['message'] = 'Invalid User';
            }
        } else {
            $res['message'] = 'Missing userId/email and password field';
        }
        return $res;
    }

    /**
     * Register with userId, email, firstname, lastname, phonenum, password
     * @param array $data
     * @return array
     */
    public static function Register($data = array()) {
        $res = [
            'status' => false,
            'message' => 'No Action'
        ];
        if (isset($data['userId'], $data['email'], $data['firstname'], $data['lastname'], $data['phonenum'], $data['password'])) {
            $userId = Funcs::ValidateField("string", $data['userId'], false);
            $email = Funcs::ValidateField("email", $data['email'], false);
            $firstname = Funcs::ValidateField("string", $data['firstname'], false);
            $lastname = Funcs::ValidateField("string", $data['lastname'], false);
            $phonenum = Funcs::ValidateField("phone", $data['phonenum'], false);
            $password = Funcs::ValidateField("password", $data['password'], false);
            foreach(array($userId, $email, $firstname, $lastname, $phonenum, $password) as $field) {
                if (!$field['valid']) {
                    $res['message'] = $field['message'];
                    return $res;
                }
            }
            $sdata = [
                'user' => $userId['value']
            ];
            $customer = AUTH::FindCustomer($sdata);
            if ($customer['status']) {
                $res['message'] = 'User already already exists';
                return $res;
            } else {
                $db = new DB();
                $sql = "INSERT INTO customer (userId, email, firstname, lastname, phonenum, password) VALUES (?, ?, ?, ?, ?, ?)";
                $pstmt = sqlsrv_prepare($db->conn, $sql, array($userId['value'], $email['value'], $firstname['value'], $lastname['value'], $phonenum['value'], $password['value']));
                $pstmt = sqlsrv_execute($pstmt);
                if ($pstmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                $res['status'] = true;
                $res['message'] = 'Registered';
            }
        } else {
            $res['message'] = 'Missing one of: username, email, firstname, lastname, phonenum';
        }
        return $res;
    }

    /**
     * Return Customer Information
     * @param array $data user
     * @return array customer
     */
    public static function FindCustomer($data = array()) {
        $res = [
            'status' => false,
            'message' => 'No Action',
            'customer' => array()
        ];
        if (isset($data['user'])) {
            // $user = Funcs::ValidateField("string", $data['user'], false);
            // if (!$user['valid']) {
            //     $res['message'] = $user['value'];
            //     return $res;
            // }
            $sql = "SELECT * FROM customer WHERE userId = ? OR email = ?";
            $db = new DB();
            $pstmt = sqlsrv_query($db->conn, $sql, array($data['user'], $data['user']));
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC);
            if ($row) {
                $res['status'] = true;
                $res['message'] = 'Found Customer';
                $res['customer'] = $row;
            } else {
                $res['message'] = 'Customer Not Found';
            }
        } else {
            $res['message'] = 'Missing userId/email field';
        }
        // echo json_encode($res);
        return $res;
    }

    /**
     * If the user is logged in, return the userId
     * If not, if the user is found, change password with new password
     * If not, return error
     * @param array $data
     * @return array
     */
    public static function ResetPassword($data = array()) {
        $res = [
           'status' => false,
           'message' => 'No Action'
        ];
        if (isset($data['userId'])) {
            $userId = Funcs::ValidateField("string", $data['userId'], false);
            if (!$userId['valid']) {
                $res['message'] = $userId['value'];
                return $res;
            }
            $user = AUTH::FindCustomer(array('user' => $data['userId']));
            if ($user['status']) {
                if (isset($data['password'])) {
                    $password = Funcs::ValidateField("password", $data['password'], false);
                    if (!$password['valid']) {
                        $res['message'] = $password['value'];
                        return $res;
                    }
                    $sql = "UPDATE customer SET password = :password WHERE userId = :userId OR email = :userId";
                    $pstmt = sqlsrv_query(AUTH::$db, $sql, array(
                        ":password" => $password['value'],
                        ":userId" => $userId['value']
                    ));
                    if ($pstmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    } else {
                        $res['status'] = true;
                        $res['message'] = 'Password Changed';
                    }
                } else {
                    $res['message'] = 'Missing password field';
                }
            } else {
                $res['message'] = 'User Not Found';
            }
        } else {
            $res['message'] = 'Missing userId/email field';
        }
        return $res;
    }

    public static function UpdateSession($data = array()) {
        $res = [
            'status' => false,
            'message' => 'No Action'
        ];
        $user = AUTH::FindCustomer(array('user' => $data['userId']));
        if ($user['status']) {
            $_SESSION['userId'] = $user['customer']['userId'];
            $_SESSION['customer'] = $user['customer'];
            $res['status'] = true;
            $res['message'] = 'Session Updated';
        } else {
            $res['message'] = 'User Not Found';
        }
        return $res;
    }

    public static function DeleteCustomer($data = array()) {
        $res = [
            'success' => false,
            'message' => 'An error occured while deleting.'
        ];
        if (isset($data['userId'], $data['password'])) {
            $password = $data['password'];
            $userId = $data['userId'];
            if (AuthOld::FindCustomer($userId)) {
                $customer = new Customer($userId);
                if (password_verify($password, $customer->GetPassword())) {
                    $sql = "DELETE FROM customer WHERE userId = ?";
                    $params = array($userId);
                    $db = new DB();
                    $pstmt = sqlsrv_query($db->conn, $sql, $params);
                    if ($pstmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                    $res['success'] = true;
                    $res['message'] = 'Account deleted.';
                } else {
                    $res['message'] = 'Incorrect password.';
                }
            } else {
                $res['message'] = 'Account does not exist.';
            }
        } else {
            $res['message'] = 'Missing one of: customerId, password';
        }
        return $res;
    }
}