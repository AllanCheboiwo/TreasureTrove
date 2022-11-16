<?php
require '../db_credentials.php';
$res = [
    'status' => false,
    'message' => 'No Action'
];
// switch action from $_REQUEST['action'] and use $_POST with the Product, Review classes to run the action
// action can be deleteProduct, deleteReview, addProduct, addReview, updateProduct, updateReview

if(isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    switch($action) {
        case 'deleteProduct':
            if (isset($_REQUEST['pid'])) {
                $res = Product::DeleteProduct($_REQUEST['pid']);
            } else {
                $res['message'] = 'Missing product id';
            }
            break;
        case 'deleteReview':
            if (isset($_SESSION['customer'])) {
                if (isset($_REQUEST['rid'])) {
                    $res = Review::DeleteReview($_REQUEST['rid']);
                } else {
                    $res['message'] = 'Missing review id';
                }
            } else {
                $res['message'] = 'You must be logged in to delete a review';
            }
            break;
        case 'addProduct':
            if (isset($_REQUEST['pname'], $_REQUEST['price'], $_REQUEST['description'], $_REQUEST['category'])) {
                $data = [
                    'productName' => $_REQUEST['pname'],
                    'productImage' => $_REQUEST['pimage'],
                    'productImageUrl' => $_REQUEST['pimageurl'],
                    'desc' => $_REQUEST['description'],
                    'categoryId' => $_REQUEST['category']
                ];
                $res = Product::AddProduct($data);
            } else {
                $res['message'] = 'Missing one of: product name, price, description, or category';
            }
            break;
        case 'addReview':
            if (isset($_SESSION['customer'])) {
                if (isset($_REQUEST['pid'], $_REQUEST['rating'], $_REQUEST['comment'])) {
                    $data = [
                        'productId' => $_REQUEST['pid'],
                        'reviewRating' => $_REQUEST['rating'],
                        'reviewDate' => date('Y-m-d'),
                        'reviewComment' => $_REQUEST['comment'],
                        'customerId' => $_SESSION['customer']['customerId']
                    ];
                    $res = Review::AddReview($data);
                } else {
                    $res['message'] = 'Missing one of: product id, rating, or comment';
                }
            } else {
                $res['message'] = 'You must be logged in to add a review';
            }
            break;
        case 'updateProduct':
            if (isset($_REQUEST['pid'])) {
                $data = [
                    'productId' => $_REQUEST['pid'],
                    'productName' => $_REQUEST['pname'],
                    'productImage' => $_REQUEST['pimage'],
                    'productImageUrl' => $_REQUEST['pimageurl'],
                    'productDesc' => $_REQUEST['description'],
                    'categoryId' => $_REQUEST['category']
                ];
                $allRes = array();
                if (isset($_REQUEST['pname'])) {
                    $res = Product::UpdateProduct($data['productId'], "productName", $data['productName']);
                    array_push($allRes, $res);
                }
                if (isset($_REQUEST['pimage'])) {
                    $res = Product::UpdateProduct($data['productId'], "productImage", $data['productImage']);
                    array_push($allRes, $res);
                }
                if (isset($_REQUEST['pimageurl'])) {
                    $res = Product::UpdateProduct($data['productId'], "productImageUrl", $data['productImageUrl']);
                    array_push($allRes, $res);
                }
                if (isset($_REQUEST['description'])) {
                    $res = Product::UpdateProduct($data['productId'], "productDesc", $data['productDesc']);
                    array_push($allRes, $res);
                }
                if (isset($_REQUEST['category'])) {
                    $res = Product::UpdateProduct($data['productId'], "categoryId", $data['categoryId']);
                    array_push($allRes, $res);
                }
                foreach($allRes as $r) {
                    if (!$r['status']) {
                        $res = $r;
                        break;
                    }
                }
                $res['message'] = 'Product updated successfully';
            } else {
                $res['message'] = 'Missing one of: product id, product name, price, description, or category';
            }
            break;
        case 'updateReview':
            if (isset($_SESSION['customer'])) {
                if (isset($_REQUEST['rid'], $_REQUEST['rating'], $_REQUEST['comment'])) {
                    $data = [
                        'reviewId' => $_REQUEST['rid'],
                        'reviewRating' => $_REQUEST['rating'],
                        'reviewComment' => $_REQUEST['comment']
                    ];
                    $res = Review::UpdateReview($data);
                } else {
                    $res['message'] = 'Missing one of: review id, rating, or comment';
                }
            } else {
                $res['message'] = 'You must be logged in to update a review';
            }
            break;
        default:
            $res['message'] = 'Invalid action';
            break;
    }
}
// echo json_encode($_REQUEST);
echo json_encode($res);
