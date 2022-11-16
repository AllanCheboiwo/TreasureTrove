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
            $this->orderDate = $row['orderDate']->format('d m, Y');
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
        return new OrderSummary($orderId);
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
        $orderProducts = OrderSummary::GetItemsByOrderId($orderId);
        $orderSummaryString = "";
        if (!empty($orderProducts)) {
            foreach ($orderProducts as $orderProduct) {
                $product = new Product($orderProduct['productId']);
                $category = new Category($product->categoryId);
                $orderSummaryString .= '<div class="container" style="padding: 0px; margin:8px">
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
                if ($orderProduct['orderDate'] !== null) {
                    $orderSummaryString .= '<button class="btn btn-primary" type="button" style="border-style: none;border-radius: 6px;box-shadow: 0px 0px 6px #169884;display: inline-flex;background: #1cc4ab;">
                                                <i class="material-icons" style="margin-right: 8px;">local_grocery_store</i>
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
}