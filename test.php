<?php
require './include/main.php';

//Connect to the database
$db = new DB();
$conn = $db->conn;

//Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Create an array of products
$products = array();

//Add products to the array
$products[101] = array('name' => 'Apple iPhone X', 'price' => 999.99, 'desc' => 'The iPhone X features a new all-screen design. Face ID, which makes your face your password');
$products[102] = array('name' => 'Apple iPad', 'price' => 599.99, 'desc' => 'The iPad features a large, high-resolution Retina display, FaceTime HD camera, iSight camera, and fast wireless connectivity.');
$products[103] = array('name' => 'Apple MacBook Pro', 'price' => 2199.99, 'desc' => 'MacBook Pro elevates the notebook to a whole new level of performance and portability.');
$products[104] = array('name' => 'Apple TV', 'price' => 149.99, 'desc' => 'The new Apple TV is the ultimate way to experience television.');

//Add an item to the cart
if (isset($_POST['add'])) {
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    
    //If the cart doesn't exist, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    //Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        //If it is, increase the quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        //If it isn't, add it to the cart
        $_SESSION['cart'][$productId] = array('quantity' => $quantity, 'price' => $price);
    }
}

//If the cart exists
if (isset($_SESSION['cart'])) {
    //Loop through each product in the cart and print out the name, price and quantity
    foreach ($_SESSION['cart'] as $productId => $product) {
        $name = $products[$productId]['name'];
        $price = $product['price'];
        $quantity = $product['quantity'];
        
        echo "<p>$name ($quantity @ $price each) = $price</p>";
    }
    
    //Calculate the total cost of the products in the cart
    $total = 0;
    foreach ($_SESSION['cart'] as $product) {
        $total += $product['price'] * $product['quantity'];
    }
    
    echo "<p>Total: \$$total</p>";
}

//If the user clicks on the "Empty cart" button
if (isset($_POST['empty'])) {
    //Unset the cart
    unset($_SESSION['cart']);
}

//If the user clicks on the "Checkout" button
if (isset($_POST['checkout'])) {
    //Add the products in the cart to the database
    foreach ($_SESSION['cart'] as $productId => $product) {
        $quantity = $product['quantity'];
        $price = $product['price'];
        
        $sql = "INSERT INTO incart (orderId, productId, quantity, price) VALUES (1, $productId, $quantity, $price)";
        
        if (mysqli_query($conn, $sql)) {
            echo "<p>Product $productId added to cart!</p>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    
    //Unset the cart
    unset($_SESSION['cart']);
}

require './include/header.php';
?>
<table>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>
    
    <?php
    foreach ($products as $productId => $product) {
        $name = $product['name'];
        $price = $product['price'];
        $desc = $product['desc'];
        
        echo "<tr>";
        echo "<td>$name</td>";
        echo "<td>$price</td>";
        echo "<td>";
        echo "<form action='#' method='post'>";
        echo "<input type='hidden' name='productId' value='$productId'>";
        echo "<input type='hidden' name='price' value='$price'>";
        echo "<select name='quantity'>";
        for ($i = 1; $i <= 10; $i++) {
            echo "<option value='$i'>$i</option>";
        }
        echo "</select>";
        echo "<input type='submit' name='add' value='Add to cart'>";
        echo "<input type='submit' name='checkout' value='Checkout'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>

<form action="#" method="post">
    <input type="submit" name="empty" value="Empty cart">
</form>