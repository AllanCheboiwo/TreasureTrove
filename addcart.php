<?php
require('include/main.php');

// Get the current list of products

// Add new product selected
// Get product information
if (isset($_GET['pid']) && isset($_GET['pname']) && isset($_GET['price'])){
    $id = $_GET['pid'];
    $name = $_GET['pname'];
    $price = $_GET['price'];
} else {
    header('Location: /index.php');
}

// Update quantity if add same item to order again
if (isset($_SESSION['cart'][$id])){
    $_SESSION['cart'][$id]['quantity'] = $_SESSION['cart'][$id]['quantity'] + 1;
    if (isset($_SESSION['customer']) && isset($_SESSION['loggedIn'])) {
        $_SESSION['cart'][$id]['customerId'] = $_SESSION['customer']['customerId'];
    }
} else {
    $_SESSION['cart'][$id] = array( "pid"=>$id, "pname"=>$name, "price"=>$price, "quantity"=>1, "customerId"=>isset($_SESSION['customer']) ? $_SESSION['customer']['customerId'] : null );
}

$_SESSION['cart'] = $cart;
header('Location: cart.php');

?>