<?php

require_once $root.'/include/db_credentials.php';

class AuthOld extends DB {
    private static $db;
    public static $isLoggedIn = false;
    public static $currentCustomer;

    public function __construct() {
        $db = new DB();
        if(AuthOld::$db == null) {
            AuthOld::$db = $db->conn;
        }
    }

    public function __destruct() {
        sqlsrv_close(AuthOld::$db);
        AuthOld::$db = null;
    }

    public static function updateCurrentUser($userId, $item, $value) {
        if (AuthOld::FindCustomer($userId)) {
            $sql = "UPDATE customer SET $item = ? WHERE userId = ?";
            $params = array($value, $userId);
            $stmt = sqlsrv_query(AuthOld::$db, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            AuthOld::$currentCustomer[$item] = $value;
        }
    }

    public static function getCurrentUser() {
        if (AuthOld::$isLoggedIn) {
            return AuthOld::$currentCustomer;
        }
    }

    public static function Register($userId, $email, $password, $firstName, $lastName, $phone) {
        $res = [
            'success' => false,
            'message' => 'An error occured while registering.'
        ];
        if (AuthOld::$isLoggedIn == false) {
            if (AuthOld::FindCustomer($email) == false) {
                if (AuthOld::ValidateField('username', $userId) &&
                AuthOld::ValidateField('firstName', $firstName) &&
                AuthOld::ValidateField('lastName', $lastName) &&
                AuthOld::ValidateField('email', $email) &&
                AuthOld::ValidateField('password', $password) &&
                AuthOld::ValidateField('phone', $phone)) {
                    $db = new DB();
                    $sql = "INSERT INTO customer (firstName, lastName, email, password, phoneNum) VALUES (?, ?, ?, ?, ?)";
                    $params = array($firstName, $lastName, $email, password_hash($password, PASSWORD_BCRYPT), $phone);
                    $stmt = sqlsrv_query($db->conn, $sql, $params);
                    if ($stmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                    $res['success'] = true;
                    $res['message'] = 'Registration successful.';
                } else {
                    $res['message'] = 'Invalid inputs.';
                }
            } else {
                $res['message'] = 'Email already in use.';
            }
        } else {
            $res['message'] = 'You are already logged in.';
        }
        return $res;
    }

    public static function FindCustomer($userId) {
        $db = new DB();
        $sql = "SELECT * FROM customer WHERE userId = ?";
        $params = array($userId);
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }

    public static function FindCustomerByFirstAndLastName($firstName, $lastName) {
        $sql = "SELECT * FROM customer WHERE firstName = ? AND lastName = ?";
        $params = array($firstName, $lastName);
        $stmt = sqlsrv_query(AuthOld::$db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }

    public static function FindCustomerByEmail($email) {
        $sql = "SELECT * FROM customer WHERE email = ?";
        $params = array(strtolower($email));
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Delete customer if the customer exists and the password_verify is successful
     * @param string $customerId
     * @param string $password
     * @return array
     */
    public static function DeleteCustomer($customerId, $password) {
        $res = [
            'success' => false,
            'message' => 'An error occured while deleting.'
        ];
        if (AuthOld::FindCustomer($customerId)) {
            if (password_verify($password, AuthOld::$currentCustomer['password'])) {
                $sql = "DELETE FROM customer WHERE customerId = ?";
                $params = array($customerId);
                $stmt = sqlsrv_query(AuthOld::$db, $sql, $params);
                if ($stmt === false) {
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
        return $res;
    }

    // filters all inputs and verify the password then sets the $_SESSION['customer'] to the customer, update the cart session's items' customer with the customerId, update the loggedIn session and update the AuthOld loggedIn and customer
    /**
     * filterInputs for both the email and password fields
     * Checks if the customer exists and the password_verify is successful
     * Update session variables on success and AuthOld static properties
     * @param string $email
     * @param string $password
     * @param boolean $remember
     * If true, set the cookie for 30 days
     * If false, set the cookie for 1 day
     * If null, do not set the cookie
     * If the cookie is set, set the session to the cookie
     * If the cookie is not set, set the cookie to the session
     * @return array
     */
    public static function Login($email, $password, $remember = null) {
        $res = [
           'status' => false,
           'message' => 'An error occured while logging in.'
        ];
        $email = AuthOld::FilterInput("email", $email);
        $password = AuthOld::FilterInput("password", $password, true);
        if ($email['valid'] && $password['valid']) {
            $customer = AuthOld::FindCustomerByEmail($email['value']);
            if ($customer) {
                if (password_verify($password['value'], $customer['password'])) {
                    AuthOld::$currentCustomer = $customer;
                    AuthOld::$isLoggedIn = true;
                    $_SESSION['customer'] = $customer;
                    $_SESSION['loggedIn'] = true;
                    $res['status'] = true;
                    $res['message'] = 'Login successful.';
                    if ($remember === true) {
                        setcookie('customer', $customer['customerId'], time() + (86400 * 30), "/");
                    } else if ($remember === false) {
                        setcookie('customer', $customer['customerId'], time() + (86400), "/");
                    }
                    if (isset($_COOKIE['customer'])) {
                        $_SESSION['customer'] = $_COOKIE['customer'];
                    } else {
                        setcookie('customer', $_SESSION['customer'], time() + (86400 * 30), "/");
                    }
                } else {
                    $res['message'] = 'Incorrect password.';
                }
            } else {
                $res['message'] = 'Account does not exist.';
            }
        } else {
            $res['message'] = 'Invalid inputs.';
        }
        return $res;
    } 

    public static function Logout() {
        if ($_SESSION['loggedIn'] || AuthOld::$isLoggedIn) {
            $_SESSION=array();
            // setcookie(, '', time()-3600);
            session_unset();
            session_destroy();
            AuthOld::$isLoggedIn = false;
            AuthOld::$currentCustomer = null;
            header("Location: /index.php");
            echo "<script>alert('Logged out');</script>";
        }
    }

    public static function ValidateField($field, $value) {
        $val = false;
        $field = strtolower($field);
        if (($field == "firstname" || $field == "lastname") && preg_match("/^[A-Za-z\\-]{1,}$/i", $value)) {
            $val = true;
        } elseif($field == "username" && filter_var($value, FILTER_SANITIZE_STRING)) {
            $val = true;
        } elseif ($field == "email" && filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $val = true;
        } elseif ($field == "password" && strlen($value) > 0) {
            $val = true;
        } elseif (($field == "phone" || $field == "phonenum") && preg_match("/^\d{10}$/", $value)) {
            $val = true;
        } else {
            $val = false;
        }
        return $val;
    }

    /**
     * Properly filters, sanitizes and validates the input.
     * If email, sanitizes and validates email.
     * If password, sanitizes and validates password.
     * If phone, sanitizes and validates phone.
     * If string, sanitizes and validates string
     * If number, sanitizes and validates number, must be of any number type (float, int, decimal, etc.)
     * If postalCode or postCode, must be of format A1A 1A1, where A is a letter and 1 is a number. Sanitizes and validates postalCode.
     * @param string $field
     * @param string $value
     * @param string $confirm
     * @return array
     */
    public static function FilterInput($field, $value, $confirm = false) {
        $val = array();
        $field = strtolower($field);
        if ($field == "email") {
            $val['value'] = filter_var($value, FILTER_SANITIZE_EMAIL);
            $val['valid'] = filter_var($val['value'], FILTER_VALIDATE_EMAIL);
        } elseif ($field == "password") {
            if ($confirm) {
                $val['value'] = filter_var($value, FILTER_SANITIZE_STRING);
                $val['valid'] = strlen($val['value']) > 0;
            } else {
                $val['value'] = password_hash($value, PASSWORD_BCRYPT);
                $val['valid'] = true;
            }
        } elseif ($field == "phone" || $field == "phonenum") {
            $val['value'] = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            $val['valid'] = preg_match("/^\d{10}$/", $val['value']);
        } elseif ($field == "firstname" || $field == "lastname") {
            $val['value'] = filter_var($value, FILTER_SANITIZE_STRING);
            $val['valid'] = preg_match("/^[A-Za-z\\-]{1,}$/i", $val['value']);
        } elseif ($field == "string") {
            $val['value'] = filter_var($value, FILTER_SANITIZE_STRING);
            $val['valid'] = filter_var($val['value'], FILTER_SANITIZE_STRING);
        } elseif ($field == "postalcode" || $field == "postcode") {
            $val['value'] = filter_var($value, FILTER_SANITIZE_STRING);
            $val['valid'] = preg_match("/^[A-Za-z]\\d[A-Za-z] \\d[A-Za-z]\\d$/", $val['value']);
        } else {
            $val['value'] = filter_var($value, FILTER_SANITIZE_STRING);
            $val['valid'] = true;
        }
        return $val;
    }

    public static function Refresh() {
        if (AuthOld::$isLoggedIn) {
            AuthOld::$currentCustomer = AuthOld::FindCustomer(AuthOld::$currentCustomer['userId']);
        }
    }
}