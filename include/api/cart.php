<?php
require '../db_credentials.php';
$res = [
    'status' => false,
    'message' => 'No Action'
];
// switch action from $_REQUEST['action'] and use $_POST with the AUTH class to run the action
// action can be add, remove
switch($_REQUEST['action']) {
    case 'add':
        if (isset($_REQUEST['pid'], $_REQUEST['pname'], $_REQUEST['quantity'], $_REQUEST['price'])) {
            $data = [
                'productId' => $_REQUEST['pid'],
                'productName' => $_REQUEST['pname'],
                'quantity' => $_REQUEST['quantity'],
                'price' => $_REQUEST['price']
            ];
            // $res = Cart::
            $res = Cart::AddToCart($data);
        } else {
            $res['message'] = 'Missing product id or quantity';
        }
        break;
    case 'update':
        if (isset($_REQUEST['pname'], $_REQUEST['pid'], $_REQUEST['quantity'], $_REQUEST['price'])) {
            $data = [
                // 'orderId' => $_REQUEST['oid'],
                'productId' => $_REQUEST['pid'],
                'productName' => $_REQUEST['pname'],
                'price' => $_REQUEST['price'],
                'quantity' => $_REQUEST['quantity']
            ];
            $res = Cart::UpdateCart($data);
        } else {
            $res['message'] = 'Missing product id or quantity';
        }
        break;
    case 'clear':
        unset($_SESSION['cart']);
        $res['status'] = true;
        $res['message'] = 'Cart Cleared';
        break;
    case 'checkout':
        $customerId = null;
        if (isset($_REQUEST['customerId'])) {
            $customerId = $_REQUEST['customerId'];
        } elseif(isset($_SESSION['customer'])) {
            $customerId = $_SESSION['customer']['customerId'];
        }

        if ($customerId !== null) {
            if (Customer::GetCustomerById($customerId)) {
                $data = [
                    'shiptoAddress' => $_REQUEST['shiptoAddress'],
                    'shiptoCity' => $_REQUEST['shiptoCity'],
                    'shiptoState' => $_REQUEST['shiptoState'],
                    'shiptoPostalCode' => $_REQUEST['shiptoPostalCode'],
                    'shiptoCountry' => $_REQUEST['shiptoCountry'],
                    'customerId' => $customerId
                ];
                $res = Cart::Checkout($data);
            } else {
                $res['message'] = 'Customer not found in our records';
            }
        } else {
            $res['message'] = 'Invalid Customer';
        }
        break;
    case 'remove':
        if (isset($_POST['pid'])) {
            $productId = $_POST['pid'];
            $res = Cart::DeleteFromCart($productId);
        } else {
            $res['message'] = 'Missing product id';
        }
        break;
    default:
        print_r($_SESSION['cart']);
        $res['message'] = 'Invalid Action';
        break;
}
Cart::MergeDuplicates();
echo json_encode($res);