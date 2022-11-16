<?php
    require '../db_credentials.php';
    $res = [
        'status' => false,
        'message' => 'Invalid'
    ];
    // switch $_REQUEST['action'] and use $_REQUEST['data'] to pass in data
    // echo "<script>console.log('".json_encode($_REQUEST)."');</script>";
    if (isset($_REQUEST['action'])) {
        switch ($_REQUEST['action']) {
            case 'deleteAccount':
                if (isset($_POST['password'])) {
                    $data = [
                        'userId' => $_SESSION['userId'],
                        'password' => $_POST['password']
                    ];
                    $res = AUTH::DeleteCustomer($data);
                } else {
                    $res['message'] = 'Missing password';
                }
                break;
            case 'editEmail':
                if ($_POST['email'] === $_POST['email2']) {
                    $res = $customer->UpdateEmail($_POST['email'], $_POST['password']);
                } else {
                    $res['message'] = 'Emails do not match';
                }
                break;
            case 'editPassword':
                if ($_POST['password'] === $_POST['password2']) {
                    $res = $customer->UpdatePassword($_POST['passwordOld'], $_POST['password']);
                } else {
                    $res['message'] = 'New Password and Verification Password do not match.';
                }
                break;
            case 'editPersonal':
                $res = $customer->UpdatePersonalInfo($_POST['firstName'], $_POST['lastName'], $_POST['phoneNum']);
                break;
            case 'editShipping':
                if (isset($_POST['address']) && isset($_POST['UAFN']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['country']) && isset($_POST['postalCode'])) {
                    $res = $customer->UpdateAddress($_POST['address'], $_POST['UAFN'], $_POST['city'], $_POST['state'], $_POST['country'], $_POST['postalCode']);
                } else {
                    $res['message'] = 'Missing address information';
                }
                break;
            case 'editBilling':
                if (isset($_POST['billaddress']) && isset($_POST['billUAFN']) && isset($_POST['billcity']) && isset($_POST['billstate']) && isset($_POST['billcountry']) && isset($_POST['billpostalCode'])) {
                    $res = $customer->UpdateAddress($_POST['billaddress'], $_POST['billUAFN'], $_POST['billcity'], $_POST['billstate'], $_POST['billcountry'], $_POST['billpostalCode']);
                } else {
                    $res['message'] = 'Missing address information';
                }
                break;
            case 'addpm':
                if (isset($_REQUEST['newcardNumber']) && isset($_REQUEST['newcardType']) && isset($_REQUEST['newcardExpMonth']) && isset($_REQUEST['newcardExpYear'])) {
                    $expMonth = intval($_POST['newcardExpMonth']) < 10 ? "0".$_POST['newcardExpMonth'] : $_POST['newcardExpMonth'];
                    $expDate = strval("$expMonth/".$_POST['newcardExpYear']);
                    $expDate = DateTime::createFromFormat('m/Y', $expDate);
                    $expDate = date_format($expDate, 'Y-m-01');
                    $data = [
                        'customerId' => $_SESSION['customer']['customerId'],
                        'paymentNumber' => $_POST['newcardNumber'],
                        'paymentType' => $_POST['newcardType'],
                        'paymentExpiryDate' => $expDate
                    ];
                    if (PaymentMethod::IsDuplicate($data)) {
                        $res['message'] = 'Duplicate Payment Method';
                    } else {
                        $res = PaymentMethod::AddPM($_SESSION['customer']['customerId'], $_REQUEST['newcardNumber'], $_REQUEST['newcardType'], $expDate);
                    }
                } else {
                    $res['message'] = "Missing Card Details";
                }
                break;
            case 'deletepm':
                if (isset($_REQUEST['pmId'])) {
                    $res = PaymentMethod::DeletePM($_REQUEST['pmId']);
                } else {
                    $res['message'] = "Missing ID";
                }
                break;
            default:
                $res['message'] = 'Invalid action';
                break;
        }
    } else {
        $res['message'] = "Missing Action";
    }
    // echo json_encode($_REQUEST);
    echo json_encode($res);
?>