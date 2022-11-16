<?php
    include 'include/main.php';
    $page_name = "Orders | Treasure Trove";
    include 'include/header.php';
?>
<section class="container-md" style="padding: 20px 0px">
    <div class="row g-0 row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-2">
        <div class="col d-flex align-items-center">
            <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 16px 0;">
                <div class="card-body align-content-center" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                    <h5 class="card-title" style="color: #1cc4ab;margin:0px">Your Orders</h5>
                </div>
            </div>
        </div>
        <div class="col d-flex justify-content-center align-items-center align-content-center justify-content-sm-center justify-content-lg-end" style="padding: 16px 10px;">
            <form class="d-block">
                <div class="input-group"><input class="form-control d-table-row" type="text" style="border-radius: 20px;border-style: none;box-shadow: inset 0px 0px 3px #cccccc;background: #fafafa;" name="searchOrders" placeholder="Search all orders" /><button class="btn btn-primary" type="button" style="border-radius: 20px;margin-left: 8px;border-style: none;box-shadow: 0px 0px 6px #1cc4ab;background: #169884;">Search</button></div>
            </form>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 d-flex align-items-center align-content-center" style="padding: 8px;">
            <h5 class="d-inline-flex" style="margin: 8px;"># of orders placed in</h5>
            <div class="dropdown d-inline-flex"><button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button" style="background: #169884;box-shadow: 0px 0px 6px #1cc4ab;border-style: none;">Dropdown </button>
                <div class="dropdown-menu"><a class="dropdown-item" href="#">First Item</a><a class="dropdown-item" href="#">Second Item</a><a class="dropdown-item" href="#">Third Item</a></div>
            </div>
        </div>
    </div>
</section>
<section class="container" style="padding: 20px 0px">
    <?php
        $orders = OrderSummary::GetOrdersByCustomer($_SESSION['customer']['customerId']);
        foreach ($orders as $order) {
            $ord = new OrderSummary($order['orderId']);
            $orderDetails = OrderSummary::GetOrderDetails($order['orderId']);
            $orderItems = OrderSummary::GetItemsByOrderId($order['orderId']);
            $orderTotal = $orderDetails['totalAmount'];
            // sum to total of items in ordersummary
            if ($orderTotal == 0) {
                foreach ($orderItems as $item) {
                    $orderTotal += $item['price'] * $item['quantity'];
                }
            }
            $tempDate = $orderDetails;
            $orderplaced = $tempDate;
            // $orderPlaced = ($orderDetails['orderDate'] !== null) ? $orderDetails['orderDate'] : "Not Yet Placed";
            echo '<div class="card" style="margin: 40px;border-style: none;box-shadow: 0px 0px 6px #cccccc;">
                    <div class="container-fluid">
                        <div class="row" style="border-top-left-radius: 6px;border-top-right-radius: 6px;background: #364652;border-style: none;box-shadow: 0px 0px 6px #364652;color: #169884;">
                            <div class="col-6 col-sm-6 col-md-3 col-xl-2" style="padding: 10px;">
                                <h6>ORDER PLACED</h6>
                                <h5>'.$orderPlaced.'</h5>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3 col-xl-2" style="padding: 10px;">
                                <h6>TOTAL</h6>
                                <h5>CDN$ '.number_format($orderTotal, 2).'</h5>
                            </div>
                            <div class="col-sm-6 col-md-6 col-xl-5" style="padding: 10px;">
                                <h6>SHIP TO</h6>
                                <h6>'.$ord->FullAddress().'</h6>
                            </div>
                            <div class="col-sm-6 col-md-12 col-xl-3" style="padding: 10px; justify-content: end; align-items: start; display: flex;">
                                <h6>ORDER # '.$order['orderId'].'</h6>
                            </div>
                        </div>
                        <div class="row g-0" style="margin: 0px -12px;">
                            <div class="col-12" style="background: #169884;color: #faf3dd;box-shadow: 0px 0px 6px #1cc4ab;">
                                <h5 style="padding: 10px;">Delivered Nov. 9, 2022</h5>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-9 col-xxl-9">
                            ';
                            echo OrderSummary::DisplayOrderSummary($order['orderId']);
                    // foreach ($orderItems as $item) {
                    //     $orderTotal += $item->price;
                    //     $product = Product::GetProduct($item->productId);
                    //     echo '<div class="row g-0" style="padding: 10px 0px;">
                    //             <div class="col-3 col-sm-3 col-md-3 col-lg-2 col-xl-2 col-xxl-2" style="padding: 0px 10px;">
                    //                 <img class="img-fluid d-block" src="'.$product->ProductImageLink().'" style="width: 100%;height: 100%;object-fit: cover;object-position: center;" />
                    //             </div>
                    //             <div class="col-9 col-sm-9 col-md-9 col-lg-10 col-xl-10 col-xxl-10" style="padding: 0px 10px;">
                    //                 <h5>'.$product->productName.'</h5>
                    //                 <h6>CDN$ '.$item->price.'</h6>
                    //             </div>
                    //         </div>';
                    // }
                            echo '
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 col-xxl-3 d-flex justify-content-center align-items-center" style="padding: 10px;">
                                <div class="btn-group-vertical d-flex" role="group"><a class="btn btn-primary" role="button" style="border-radius: 6px;border-style: none;box-shadow: 0px 0px 6px #169884;background: #1cc4ab;">Write Product Reviews</a><a class="btn btn-primary" role="button" style="border-radius: 6px;border-style: none;box-shadow: 0px 0px 6px #faf3dd;background: #faf3dd;color: #364652;margin-top: 8px;">
                                ';
                                if ($orderDetails['orderDate'] != null) {
                                    echo 'Track Package';
                                } else {
                                    echo 'Place Order';
                                }
                            echo '
                                </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 10px 8px;">
                        <button class="btn btn-primary" type="button" style="border-style: none;border-radius: 6px;box-shadow: 0px 0px 6px #169884;background: #1cc4ab;" onclick="window.location.href=\'/order.php?oid='.$order['orderId'].'\'">
                    ';
                    if ($orderDetails['orderDate'] != null) {
                        echo 'View Order Details';
                    } else {
                        echo 'Checkout';
                    }
                    echo '
                        </button>
                    </div>
                </div>';
        }
    ?>
</section>
<?php
    include 'include/footer.php';
?>