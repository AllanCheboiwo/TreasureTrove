<?php
    require_once $root.'/include/db_credentials.php';

class Funcs extends DB {

    /**
     * Properly filters, sanitizes and validates the input.
     * If email, sanitizes and validates email.
     * If password, sanitizes and validates password. if confirm is not true, return hashed password
     * If phone, sanitizes and validates phone.
     * If string, sanitizes and validates string
     * If number, sanitizes and validates number, must be of any number type (float, int, decimal, etc.)
     * If postalCode or postCode, must be of format A1A 1A1, where A is a letter and 1 is a number. Sanitizes and validates postalCode.
     * @param string $field
     * @param string $value
     * @param string $confirm
     * @return array
     */
    public static function ValidateField($field, $value, $confirm) {
        $res = [
            'value' => $value,
            'valid' => false,
        ];
        switch($field) {
            case 'email':
                $res['value'] = filter_var($value, FILTER_SANITIZE_EMAIL);
                $res['value'] = filter_var($res['value'], FILTER_VALIDATE_EMAIL);
                $res['valid'] = !empty($res['value']);
                break;
            case 'pass':
            case 'password':
                if (strlen($value) > 0) {
                    if ($confirm) {
                        // $res['value'] = filter_var($value, FILTER_SANITIZE_STRING);
                        $res['value'] = filter_var($value, FILTER_VALIDATE_REGEXP, [
                            'options' => [
                                'regexp' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                            ],
                        ]);
                        $res['valid'] = !empty($res['value']);
                    } else {
                        $res['value'] = password_hash($value, PASSWORD_BCRYPT);
                        $res['valid'] = !empty($res['value']);
                    }
                } else {
                    $res['value'] = 'Password is required';
                }
                break;
            case 'phonenum':
            case 'phone':
                $res['value'] = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                $res['value'] = filter_var($res['value'], FILTER_VALIDATE_REGEXP, [
                    'options' => [
                        'regexp' => '/^(\+1)?[0-9]{10}$/',
                    ],
                ]);
                $res['valid'] = !empty($res['value']);
                break;
            case 'number':
                $res['value'] = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                $res['value'] = filter_var($res['value'], FILTER_VALIDATE_INT);
                $res['valid'] = !empty($res['value']);
                break;
            case 'postalCode':
            case 'postCode':
                $res['value'] = filter_var($value, FILTER_SANITIZE_STRING);
                $res['value'] = filter_var($res['value'], FILTER_VALIDATE_REGEXP, [
                    'options' => [
                        'regexp' => '/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/',
                    ],
                ]);
                $res['valid'] = !empty($res['value']);
                break;
            case 'firstname':
            case 'lastname':
            case 'name':
                $res['value'] = filter_var($value, FILTER_SANITIZE_STRING);
                $res['valid'] = preg_match("/^[A-Za-z\\-]{1,}$/i", $res['value']);
            break;
            case 'string':
            default:
                $res['value'] = filter_var($value, FILTER_SANITIZE_STRING);
                $res['value'] = filter_var($res['value'], FILTER_VALIDATE_REGEXP, [
                    'options' => [
                        'regexp' => '/^[a-zA-Z0-9\s]+$/', // only letters, numbers and spaces
                    ],
                ]);
                $res['valid'] = !empty($res['value']);
                break;
            }
            // echo json_encode($res);
        return $res;
    }
}