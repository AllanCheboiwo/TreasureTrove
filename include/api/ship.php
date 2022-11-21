<?php
require '../db_credentials.php';
$res = [
    'status' => false,
    'message' => 'Unknown error'
];
$action;
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    switch ($action) {
        case 'create':
            if (isset($_REQUEST['oid'])) {
                $orderId = $_REQUEST['oid'];
                $shipment = Shipment::CreateShipment($orderId);
                if (!$shipment) {
                    $res['message'] = 'Failed to create shipment';
                } else {
                    $res['status'] = true;
                    $res['message'] = 'Shipment created';
                    $res['shipment'] = Shipment::GetShipmentById($shipment);
                }
            } else {
                $res['message'] = 'Missing order ID';
            }
            break;
        default:
            $res['message'] = 'Invalid action';
            break;
    }
}

echo json_encode($res);