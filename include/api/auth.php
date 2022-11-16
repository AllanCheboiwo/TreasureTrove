<?php
    require '../db_credentials.php';
    $res = [
        'status' => false,
        'message' => 'No Action'
    ];
    // switch action from $_REQUEST['action'] and use $_POST with the AUTH class to run the action
    // acion can be login, register, forgot password, etc.
    switch($_REQUEST['action']) {
        case 'login':
            if (isset($_POST['user']) && isset($_POST['password'])) {
                $data = [
                    'user' => $_POST['user'],
                    'password' => $_POST['password']
                ];
                $res = AUTH::Login($data);
            } else {
                $res['message'] = 'Missing email or password';
            }
            break;
        case'register':
            // if isset userId, email, firstname, lastname, password, phone; then register
            if (isset($_POST['userId'], $_POST['email'], $_POST['firstname'], $_POST['lastname'], $_POST['password'], $_POST['phonenum'])) {
                $data = [
                    'userId' => $_POST['userId'],
                    'email' => $_POST['email'],
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'password' => $_POST['password'],
                    'phonenum' => $_POST['phonenum']
                ];
                $res = AUTH::Register($data);
            } else {
                $res['message'] = 'Missing required fields';
            }
            break;
        case 'forgotPassword':
            if (isset($_POST['user'])) {
                $data = [
                    'user' => $_POST['user']
                ];
                $res = AUTH::ResetPassword($sdata);
            } else {
                $res['message'] = 'Missing username/email';
            }
            break;
        default:
            $res['message'] = 'Invalid Action';
            break;
    }
    // echo json_encode($_REQUEST);
    echo json_encode($res);
?>