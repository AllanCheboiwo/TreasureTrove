<?php
include_once $root.'/include/db_credentials.php';

class OrderSummary {
    private static $db;
    private $orderId;
    public $orderDate;
    public $totalAmount;
    private $shiptoAddress;
    private $shiptoCity;
    private $shiptoState;
    private $shiptoPostalCode;
    private $shiptoCountry;
    public $customerId;
    public $UAFN; // address2
    private static $addressDelimeter = ' ~~~ ';

    public function __construct($orderId) {
        OrderSummary::$db = new DB();
        if($orderId) {
            $this->orderId = $orderId;
            $this->LoadInfo();
        }
    }

    public function __destruct() {
        if($this->db != null) {
            $this->db = null;
        }
    }

    public function LoadInfo() {
        $sql = "SELECT * FROM orderSummary WHERE orderId = ?";
        $params = array($this->orderId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->orderId = $row['orderId'];
            $this->orderDate = $row['orderDate']->format('M d, Y');
            $this->totalAmount = $row['totalAmount'];
            $this->shiptoAddress = explode(self::$addressDelimeter, $row['shiptoAddress'])[0];
            $this->shiptoCity = $row['shiptoCity'];
            $this->shiptoState = $row['shiptoState'];
            $this->shiptoPostalCode = $row['shiptoPostalCode'];
            $this->shiptoCountry = $row['shiptoCountry'];
            $this->customerId = $row['customerId'];
            $this->UAFN = explode(self::$addressDelimeter, $row['shiptoAddress'])[1];
        }
    }

    public function getOrderSummary() {
        $this->LoadInfo();
        return array(
            'orderId' => $this->orderId,
            'orderDate' => $this->orderDate,
            'totalAmount' => $this->totalAmount,
            'shiptoAddress' => $this->shiptoAddress,
            'shiptoCity' => $this->shiptoCity,
            'shiptoState' => $this->shiptoState,
            'shiptoPostalCode' => $this->shiptoPostalCode,
            'shiptoCountry' => $this->shiptoCountry,
            'customerId' => $this->customerId,
            'UAFN' => $this->UAFN
        );
    }

    public static function GetOrder($orderId) {
        $sql = "SELECT * FROM orderSummary WHERE orderId = ?";
        $params = array($orderId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $order = new OrderSummary($row['orderId']);
            $order->orderDate = $row['orderDate']->format('M d, Y');
            $order->totalAmount = $row['totalAmount'];
            $order->shiptoAddress = explode(self::$addressDelimeter, $row['shiptoAddress'])[0];
            $order->shiptoCity = $row['shiptoCity'];
            $order->shiptoState = $row['shiptoState'];
            $order->shiptoPostalCode = $row['shiptoPostalCode'];
            $order->shiptoCountry = $row['shiptoCountry'];
            $order->customerId = $row['customerId'];
            $order->UAFN = explode(self::$addressDelimeter, $row['shiptoAddress'])[1];
            return $order;
        } else {
            return null;
        }
    }

    public function cancelOrderSummary($orderId) {
        $sql = "DELETE FROM ordersummary WHERE orderId = ?";
        $params = array($orderId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function updateOrderSummary($orderId, $item, $value) {
        $sql = "UPDATE ordersummary SET $item = ? WHERE orderId = ?";
        $params = array($value, $orderId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function toString() {
        $orderSummary = $this->getOrderSummary();
        $orderSummaryString = "";
        foreach ($orderSummary as $key => $value) {
            $orderSummaryString .= $key . ": " . $value . "<br/>";
        }
        return $orderSummaryString;
    }

    /**
     * Display order summary information in a material card
     * @param $orderId
     * @return string
     */
    public static function DisplayOrderSummary($orderId) {
        $order = new OrderSummary($orderId);
        $orderProducts = OrderSummary::GetItemsByOrderId($orderId);
        $orderSummaryString = "";
        if (!empty($orderProducts)) {
            foreach ($orderProducts as $orderProduct) {
                $product = new Product($orderProduct['productId']);
                $category = new Category($product->categoryId);
                $orderSummaryString .= '<div class="container" style="padding: 8px;">
                                            <div class="row g-0">
                                                <div class="col-sm-4 col-md-4">
                                                    <img class="rounded img-fluid" src="'.$product->ProductImageLink().'" style="background-color: #efefef;width: 100%;height: 200px;object-fit: contain;object-position: center;" />
                                                </div>
                                                <div class="col-sm-8 col-md-8" style="padding: 10px;">
                                                    <a href="/product.php?pid='.$product->productId.'" style="text-decoration: none"><h5>'.$product->productName.'</h5></a>
                                                    <a href="/category/'.str_replace('/', '-', $category->categoryName).'" style="text-decoration: none"><h6 class="text-muted mb-2">'.$category->categoryName.'</h6></a>
                                                    <h6 class="text-muted mb-2">Quantity: '.$orderProduct['quantity'].'</h6>
                                                    <h6 class="text-muted mb-2">Price: $'.number_format($orderProduct['price'] * $orderProduct['quantity'], 2).'</h6>
                                                    <p style="margin-bottom: 4px">'.$product->productDesc.'</p>
                                                    ';
                if ($order->orderDate !== null) {
                    $orderSummaryString .= '<button class="btn btn-primary btn-sm justify-content-center align-items-center align-content-center" type="button" style="border-style: none;border-radius: 6px;box-shadow: 0px 0px 6px #169884;display: inline-flex;background: #1cc4ab; font-size:12px">
                                                <i class="material-icons" style="font-size:12px">local_grocery_store</i>
                                                Buy it again
                                            </button>
                                            ';
                }
                $orderSummaryString .='</div>
                                            </div>
                                        </div>';
            }
        } else {
            $orderSummaryString .= "<p class='text-muted card-subtitle mb-2' style='margin:10px'>No Items</p>";
        }
        return $orderSummaryString;
    }

    public static function GetOrdersByCustomer($customerId) {
        $sql = "SELECT * FROM ordersummary WHERE customerId = ?";
        $params = array($customerId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $orders = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $orders[] = $row;
        }
        return $orders;
    }
    
    /**
     * Get all orderProducts by orderId in orderSummary by customerId
     * @param $customerId
     * @return array
     */
    public static function GetOrderProducts($customerId) {
        $sql = "SELECT * FROM ordersummary WHERE customerId =?";
        $params = array($customerId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $orderProducts = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $orderProducts[] = $row;
        }
        return $orderProducts;
    }

    /**
     * Get all orderProducts by orderId
     * @param $orderId
     * @return array
     */
    public static function GetOrderItems($orderId) {
        $sql = "SELECT * FROM orderProduct WHERE orderId =?";
        $params = array($orderId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $orderItems = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $orderItems[] = new OrderProduct($row['orderId'], $row['productId']);
        }
        return $orderItems;
    }

    public static function GetOrderDetails($orderId) {
        $orderSummary = new OrderSummary($orderId);
        return array(
            'orderId' => $orderSummary->orderId,
            'orderDate' => $orderSummary->orderDate,
            'totalAmount' => $orderSummary->totalAmount,
            'shiptoAddress' => $orderSummary->shiptoAddress,
            'shiptoCity' => $orderSummary->shiptoCity,
            'shiptoState' => $orderSummary->shiptoState,
            'shiptoPostalCode' => $orderSummary->shiptoPostalCode,
            'shiptoCountry' => $orderSummary->shiptoCountry,
            'customerId' => $orderSummary->customerId
        );
    }

    public static function GetItemsByOrderId($orderId) {
        $order = OrderSummary::GetOrder($orderId);
        // get orderproducts from DB
        $sql = "SELECT * FROM orderproduct WHERE orderId = ?";
        $params = array($orderId);
        $db = new DB();
        $pstmt = sqlsrv_query($db->conn, $sql, $params);
        if ($pstmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $orderCartItems = array();
        while ($row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC)) {
            $orderCartItem = array(
                'orderId' => $row['orderId'],
                'productId' => $row['productId'],
                'quantity' => $row['quantity'],
                'price' => $row['price']
            );
            array_push($orderCartItems, $orderCartItem);
        }
        return $orderCartItems;
    }

    public function GetAddress() {
        $address = $this->shiptoAddress;
        $address2 = $this->UAFN;
        // if ($address2 === null) {
        //     $address2 = explode($this->addressDelimeter, $address)[1];
        // }
        return array(
            'address' => $address,
            'UAFN' => $address2,
            'city' => $this->shiptoCity,
            'state' => $this->shiptoState,
            'postalCode' => $this->shiptoPostalCode,
            'country' => $this->shiptoCountry
        );
    }

    public function FullAddress() {
        $address = $this->GetAddress();
        // create address string
        return $address['address'] . ' ' . $address['UAFN'] . ', ' . $address['city'] . ', ' . $address['state'] . ' ' . $address['postalCode'] . ', ' . $address['country'];
    }

    /**
     * Search through orders, customers, products, and categories orders and customers are linked by customerId, products and orders are linked by orderId, products, and categories orders are linked by categoryId. Search in productName, productDesc, customer's first name, and customer's last name, customer's userId, categoryName, categoryDesc
     * @param mixed $term
     * @return array
     */
    public static function Search($term) {
        // first get all orders,
        // then get all customers,
        // then get all products,
        // then get all categories
        $sql = "SELECT * FROM ordersJOIN customer ON orders.customerId = customers.customerId JOIN orderproduct ON orders.orderId = orderproduct.orderId JOIN product ON product.productId = orderproduct.productId JOIN Category ON category.categoryId = product.categoryId WHERE orderId LIKe ? OR customerId LIKE ? OR firstName LIKE ? OR lastName LIKE ? OR productId LIKe ? OR productDesc LIKE ? OR productName LIKE ? OR productId LIke ? OR totalAmount LIKE ? OR shiptoaddress LIKE ? OR shiptocity LIKE ? OR shiptocountry LIKE ? OR shiptostate LIKE ? OR shiptopostalcode LIKE ? OR categoryId LIKE ? OR categoryName LIKE ? OR categoryDesc LIKE ?";
        $params = array($term, $term, $term, $term, $term, $term, $term, $term, $term, $term, $term, $term, $term, $term, $term, $term, $term);
        $db = new DB();
        $pstmt = sqlsrv_query($db->conn, $sql, $params);
        if ($pstmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $orders = array();
        // find if $term exists in the returned results, return the order if found
        while ($row = sqlsrv_fetch_array($pstmt, SQLSRV_FETCH_ASSOC)) {
            array_push($orders, $row);
        }
        // $foundOrders = array();
        // foreach ($orders as $order) {
        //     // // if the search term exists in any field of the current item, push to foundOrders
        //     // foreach($order as $field) {
        //     //     if (strpos($field, $term)!== false) {
        //     //         array_push($foundOrders, $order);
        //     //     }
        //     // }
        // }
        return $orders;
    }
}