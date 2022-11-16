<?php

require_once $root.'/include/db_credentials.php';

class Cart {
    private static $db;
    protected $items = array();
    public static $useDB = false;

    public function __construct() {
        $this->items = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
        if ($this->items === null) {
            $this->items = array();
        }
        if (self::$db == null) {
            self::$db = new DB();
        }
    }

    /**
     * Get total items in cart
     * @return int
     */
    public static function GetTotalItems() {
        $cartItems = $_SESSION['cart'];
        $sum = 0;
        if (!empty($cartItems)) {
            foreach($cartItems as $item) {
                $sum += 1;
            }
        }
        return $sum;
    }

    /**
     * Get total price of cart
     * @return float
     */
    public static function GetTotalPrice() {
        $cartItems = $_SESSION['cart'];
        $total = 0;
        if (!empty($cartItems)) {
            foreach($cartItems as $item) {
                $total += $item['quantity'] * $item['price'];
            }
        }
        return $total;
    }

    /**
     * Add product item to cart
     * If cart is empty, create a new ordersummary in the database
     * else get the orderId from the last ordersummary in the database and add the product to the cart
     * Store the cart item to the incart table in the database if the item does not exist in the incart table
     * else update the quantity of the item in the incart table
     * Then store the cart to the session
     * @param array $data - productId, quantity, price
     * @return array
     */
    public static function AddToCart($data = array()) {
        $res = [
            'status' => false,
            'message' => 'Error adding item to cart'
        ];
        $orderId = null;
        $productName = $data['productName'];
        $productId = $data['productId'];
        $quantity = $data['quantity'];
        $price = $data['price'];
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

        if ($cartItems === null || empty($cartItems)) {
            if (Cart::$useDB) {
                $sql = "INSERT INTO ordersummary (customerId) VALUES (?); SELECT SCOPE_IDENTITY();";
                $params = array($_SESSION['customer']['customerId']);
                $db = new DB();
                $pstmt = sqlsrv_query($db->conn, $sql, $params);
                if ($pstmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                sqlsrv_next_result($pstmt);
                if(sqlsrv_fetch($pstmt) === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                
                // $orderId = $pstmt
                $orderId = sqlsrv_get_field($pstmt, 0);
            }
            $incartItem = array( // use orderId if needed
                // 'orderId' => $orderId,
                'productId' => $productId,
                'productName' => $productName,
                'quantity' => $quantity,
                'price' => $price
            );
            array_push($cartItems, $incartItem);
        } else {
            $cartItems = $_SESSION['cart'];
            // get orderId from a cartItem in the cart
            if (Cart::$useDB) {
                $orderId = $cartItems[0]['orderId'];
            }
            // if Item is in cart already then update the quantity else, add new item
            $incartItem = null;
            foreach($cartItems as $key => $item) {
                // $item['orderId'] == $orderId &&
                if ($item['productId'] == $productId) {
                    $incartItem = $item;
                    $incartItem['quantity'] += $quantity;
                    $cartItems[$key] = $incartItem;
                }
            }
            if ($incartItem === null) {
                $incartItem = array(
                    // 'orderId' => $orderId,
                    'productId' => $productId,
                    'productName' => $productName,
                    'quantity' => $quantity,
                    'price' => $price
                );
                array_push($cartItems, $incartItem);
            }
        }
        if(Cart::$useDB) {
            if (Cart::ExistCart($productId) === false) {
                $sql = "INSERT INTO incart (productId, quantity, price) VALUES (?, ?, ?, ?)"; // use orderId if needed
                $params = array($productId, $quantity, $price);
                $db = new DB();
                $pstmt = sqlsrv_query($db->conn, $sql, $params);
                if ($pstmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
            } else {
                $sql = "UPDATE incart SET quantity = ? WHERE productId = ?"; // use orderId if needed
                $params = array($quantity,$productId);
                $db = new DB();
                $pstmt = sqlsrv_query($db->conn, $sql, $params);
                if ($pstmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
            }
        }
        $_SESSION['cart'] = $cartItems;
        $res['status'] = true;
        $res['message'] = 'Item added to cart';
        return $res;
    }
    

    public static function ExistCart($productId) {
        $sql = "SELECT * FROM incart WHERE productId = ?";
        $params = array($productId);
        $db = new DB();
        $pstmt = sqlsrv_query($db->conn, $sql, $params);
        if ($pstmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        return sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC);
    }

    /**
     * Remove items from the cart database based on orderId, productId
     * @param int $orderId
     * @param int $productId
     * @return array
     */
    public static function DeleteFromCart($productId) {
        $res = [
            'status' => false,
            'message' => 'Error deleting item from cart'
        ];
        $cartItems = $_SESSION['cart'];
        if ($cartItems !== null || empty($cartItems)) {
            // if cart item exists in DB, delete it
            if (Cart::$useDB) {
                $sql = "DELETE FROM incart WHERE productId = ?"; // use orderId if needed
                $params = array($productId);
                $db = new DB();
                $pstmt = sqlsrv_query($db->conn, $sql, $params);
                if ($pstmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
            }
            // remove cart item from session
            // echo json_encode($cartItems);
            foreach ($cartItems as $key => $cartItem) {
                // $cartItem['orderId'] == $orderId && // use orderId if needed
                if ($cartItem['productId'] == $productId) {
                    unset($cartItems[$key]);
                }
            }
            $_SESSION['cart'] = $cartItems;
            $res['status'] = true;
            $res['message'] = 'Item deleted from cart';
        } else {
            $res['message'] = 'Cart is empty';
        }
        return $res;
    }

    /**
     * Update cart item quantity
     * @param array $data - orderId, productId, quantity
     * @return array
     */
    public static function UpdateCart($data = array()) {
        $res = [
            'status' => false,
            'message' => 'Error updating cart'
        ];
        // $orderId = $data['orderId'];
        $productId = $data['productId'];
        // $productName = $data['productName'];
        $quantity = $data['quantity'];
        $cartItems = $_SESSION['cart'];
        if ($cartItems !== null || empty($cartItems)) {
            // if cart item exists in DB, update it
            if (Cart::$useDB) {
                $sql = "UPDATE incart SET quantity = ? WHERE productId = ?"; // use orderId if needed
                $params = array($quantity,$productId);
                $db = new DB();
                $pstmt = sqlsrv_query($db->conn, $sql, $params);
                if ($pstmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
            }
            // update cart item in session
            foreach ($cartItems as $key => $cartItem) {
                // && $cartItem['orderId'] == $orderId
                if ($cartItem['productId'] == $productId) {
                    $cartItems[$key]['quantity'] = $quantity;
                }
            }
            $_SESSION['cart'] = $cartItems;
            $res['status'] = true;
            $res['message'] = 'Cart updated';
        } else {
            $res['message'] = 'Cart is empty';
        }
        Cart::MergeDuplicates();
        return $res;
    }

    /**
     * Merge duplicates by adding the quantities
     * @return array
     */
    public static function MergeDuplicates() {
        $res = [
            'status' => false,
            'message' => 'Error merging duplicates'
        ];
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
        $newCartItems = array();
        if (!empty($cartItems)) {
            foreach ($cartItems as $cartItem) {
                $productId = $cartItem['productId'];
                // $orderId = $cartItem['orderId'];
                $productName = $cartItem['productName'];
                $quantity = $cartItem['quantity'];
                $price = $cartItem['price'];
                if (Cart::$useDB) {
                    if (Cart::ExistCart($productId) === false) {
                        $sql = "INSERT INTO incart (productId, quantity, price) VALUES (?, ?, ?, ?)"; // use orderId if needed
                        $params = array($productId, $quantity, $price);
                        $db = new DB();
                        $pstmt = sqlsrv_query($db->conn, $sql, $params);
                        if ($pstmt === false) {
                            die(print_r(sqlsrv_errors(), true));
                        }
                    } else {
                        $sql = "UPDATE incart SET quantity = ? WHERE productId = ?"; // use orderId if needed
                        $params = array($quantity, $productId);
                        $db = new DB();
                        $pstmt = sqlsrv_query($db->conn, $sql, $params);
                        if ($pstmt === false) {
                            die(print_r(sqlsrv_errors(), true));
                        }
                    }
                }
                $incartItem = array(
                    // 'orderId' => $orderId,
                    'productId' => $productId,
                    'productName' => $productName,
                    'quantity' => $quantity,
                    'price' => $price
                );
                array_push($newCartItems, $incartItem);
            }
            $_SESSION['cart'] = $newCartItems;
            $res['status'] = true;
            $res['message'] = 'Duplicates merged';
        } else {
            $res['message'] = 'Cart is empty';
        }
        return $res;
    }

    public static function GetCartItems() {
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        return $cartItems;
    }

    public static function GetCartTotal() {
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        $total = 0;
        if ($_SESSION['cart']) {
            foreach ($cartItems as $cartItem) {
                $total += $cartItem['price'] * $cartItem['quantity'];
            }
        }
        return $total;
    }

    public static function GetCartCount() {
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        $count = 0;
        if ($_SESSION['cart']) {
            foreach ($cartItems as $cartItem) {
                $count += $cartItem['quantity'];
            }
        }
        return $count;
    }

    /**
     * Get incart items from database based on orderId and productId
     * Update ordersummary table: totalAmount, orderDate, shiptoAddress, shiptoCity, shiptoState, shiptoPostalCode, shiptoCountry
     * Insert each cartItem into orderproduct table with orderId, productId, quantity, price from incart table
     * Delete cart items from incart table
     * @param array $data - orderId, shiptoAddress, shiptoCity, shiptoState, shiptoPostalCode, shiptoCountry
     * @return array
     */
    public static function Checkout($data = array()) {
        $res = [
            'status' => false,
            'message' => 'Error checking out'
        ];
        $orderId = null;
        // $productId = $data['productId'];
        $shiptoAddress = $data['shiptoAddress'];
        $shiptoCity = $data['shiptoCity'];
        $shiptoState = $data['shiptoState'];
        $shiptoPostalCode = $data['shiptoPostalCode'];
        $shiptoCountry = $data['shiptoCountry'];
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        if (!empty($cartItems)) {
            // get total amount
            $totalAmount = Cart::GetCartTotal();
            // update ordersummary table
            //$sql = "UPDATE ordersummary SET totalAmount = ?, orderDate = ?, shiptoAddress = ?, shiptoCity = ?, shiptoState = ?, shiptoPostalCode = ?, shiptoCountry = ? WHERE orderId = ?";
            // use insert instead of update
            $sql = "INSERT INTO ordersummary (totalAmount, orderDate, shiptoAddress, shiptoCity, shiptoState, shiptoPostalCode, shiptoCountry, customerId) VALUES (?, ?, ?, ?, ?, ?, ?, ?); SELECT SCOPE_IDENTITY() AS orderId";
            $params = array($totalAmount, date('Y-m-d H:i:s'), $shiptoAddress, $shiptoCity, $shiptoState, $shiptoPostalCode, $shiptoCountry, $data['customerId']);
            $db = new DB();
            $pstmt = sqlsrv_query($db->conn, $sql, $params);
            if ($pstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            sqlsrv_next_result($pstmt);
            if(sqlsrv_fetch($pstmt) === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            
            // $orderId = $pstmt
            $orderId = sqlsrv_get_field($pstmt, 0);
            // insert each cartItem into orderproduct table
            foreach ($cartItems as $cartItem) {
                $sql = "INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (?, ?, ?, ?)";
                $params = array($orderId, $cartItem['productId'], $cartItem['quantity'], $cartItem['price']);
                $pstmt = sqlsrv_query($db->conn, $sql, $params);
                if ($pstmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                if (Cart::$useDB) {
                    // delete cart items from incart table
                    $sql = "DELETE FROM incart WHERE productId = ?"; // use orderId if needed
                    $params = array($cartItem['productId']);
                    $pstmt = sqlsrv_query($db->conn, $sql, $params);
                    if ($pstmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                }
            }
            // delete cart items from session
            unset($_SESSION['cart']);
            $res['status'] = true;
            $res['message'] = 'Checkout successful';
        } else {
            $res['message'] = 'Cart is empty';
        }
        return $res;
    }

    public function __destruct() {
        Cart::$db = null;
    }

    public static function GenerateOrder() {
        $orderProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        $orderSummaryString = "";
        if (!empty($orderProducts)) {
            // print_r($orderProducts);
            echo "<script>console.log($orderProducts)</script>";
            foreach ($orderProducts as $orderProduct) {
                $product = new Product($orderProduct['productId']);
                $category = new Category($product->categoryId);
                $orderSummaryString .= '<div class="container" style="padding: 0px;">
                                            <div class="row g-0">
                                                <div class="col-sm-4 col-md-4">
                                                    <img class="rounded img-fluid" src="'.$product->ProductImageLink().'" style="width: 100%;" />
                                                </div>
                                                <div class="col-sm-8 col-md-8" style="padding: 10px;">
                                                    <a href="/product.php?pid='.$product->productId.'" style="text-decoration: none"><h5>'.$product->productName.'</h5></a>
                                                    <a href="/category/'.str_replace('/', '-', $category->categoryName).'" style="text-decoration: none"><h6 class="text-muted mb-2">'.$category->categoryName.'</h6></a>
                                                    <h6 class="text-muted mb-2">Quantity: '.$orderProduct['quantity'].'</h6>
                                                    <h6 class="text-muted mb-2">Price: $'.number_format($orderProduct['price'] * $orderProduct['quantity'], 2).'</h6>
                                                    <p>'.$product->productDesc.'</p>
                                                    ';
                // if ($orderProduct['orderDate'] !== null) {
                //     $orderSummaryString .= '<button class="btn btn-primary" type="button" style="border-style: none;border-radius: 6px;box-shadow: 0px 0px 6px #169884;display: inline-flex;background: #1cc4ab;">
                //                                 <i class="material-icons" style="margin-right: 8px;">local_grocery_store</i>
                //                                 Buy it again
                //                             </button>
                //                             ';
                // }
                $orderSummaryString .='</div>
                                            </div>
                                        </div>';
            }
        } else {
            $orderSummaryString .= "<p>No Items</p>";
        }
        return $orderSummaryString;
    }
}