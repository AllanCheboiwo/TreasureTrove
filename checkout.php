<?php
    require 'include/main.php';
    $page_name = "Checkout | Treasure Trove";
    $orderItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    if (empty($orderItems)) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    $customer;
    if (isset($_SESSION['customer'])) {
        $customer = new Customer($_SESSION['userId']);
    } else {
        header('Location: /login');
    }
    $methods = $customer->GetPaymentMethods();
    $total = Cart::GetTotalPrice();
    $itemCount = Cart::GetTotalItems();
    $customerId = (isset($_SESSION['customer'])) ? $_SESSION['customer']['customerId'] : null;
    require 'include/header.php';
?>
<section class="container-md">
    <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 16px 0;">
        <div class="card-body" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
            <h5 class="card-title d-flex align-content-center align-items-center" style="color: #1cc4ab;">Checkout</h5>
            <h6 class="text-muted card-subtitle mb-2" style="margin-bottom: 0">
                <?php echo $itemCount; ?> Items
            </h6>
        </div>
    </div>
</section>
<div class="container" style="padding-bottom: 20px;">
    <div class="row">
        <div class="col-md-8">
            <div class="row g-0 row-cols-1">
                <div class="col" style="margin-top: 8px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 3px #cccccc;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: #364652;">Shipping Address</h4>
                            <h6 class="text-muted card-subtitle mb-2">Items will be shipped to this location</h6>
                            <form method="post" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <input class="form-control" type="text" name="shiptoAddress" placeholder="Street Address" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" value="<?php echo $customer->address; ?>"/>
                                <input class="form-control" type="text" name="shipToUAFN" placeholder="Apt/Unit/Flat Number" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" value="<?php echo $customer->UAFN; ?>"/>
                                <div class="row g-0">
                                    <div class="col">
                                        <input class="form-control" type="text" name="shipToCity" placeholder="City" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" value="<?php echo $customer->city; ?>"/>
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="text" name="shipToState" placeholder="State" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" value="<?php echo $customer->state; ?>"/>
                                    </div>
                                </div>
                                <div class="row g-0">
                                    <div class="col">
                                        <input class="form-control" type="text" name="shipToCountry" placeholder="Country" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" value="<?php echo $customer->country; ?>"/>
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="text" name="shipToPostalCode" placeholder="Postal Code" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" value="<?php echo $customer->postalCode; ?>"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 8px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 3px #cccccc;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: #364652;">Payment Method</h4>
                            <h6 class="text-muted card-subtitle mb-2">Use or Edit payment method</h6>
                            <form id="checkoutForm" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <input class="form-control" type="text" name="cardName" placeholder="Card Name" value="<?php echo $customer->FullName(); ?>" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;"/>
                                <div class="row g-0">
                                    <div class="col-4 col-sm-2 col-md-3 col-lg-2 col-xxl-2 d-inline-flex justify-content-center align-items-center align-content-center">
                                        <select class="form-select" name="cardType" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">
                                            <option value="visa" <?php echo ($methods[0]["paymentType"] === "visa" ? "selected" : "") ?>>Visa</option>
                                            <option value="mastercard" <?php echo ($methods[0]["paymentType"] === "mastercard" ? "selected" : "") ?>>Master Card</option>
                                            <option value="express" <?php echo ($methods[0]["paymentType"] === "express" ? "selected" : "") ?>>Express</option>
                                            <option value="other" <?php echo ($methods[0]["paymentType"] === "other" ? "selected" : "") ?>>Other</option>
                                        </select>
                                    </div>
                                    <div class="col"><input class="form-control" type="text" name="cardNumber" placeholder="Card Number" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;"inputmode="numeric" value="<?php echo $methods[0]["paymentNumber"]; ?>" maxlength=16 /></div>
                                </div>
                                <div class="row g-0">
                                    <div class="col-sm-5 col-lg-4 col-xl-3 col-xxl-3 d-inline-flex justify-content-center align-items-center align-content-center">
                                        <select class="form-select" name="cardType" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">
                                            <?php
                                                $ncount = 0;
                                                // count to 12
                                                for ($i = 0; $i < 12; $i++) {
                                                    $num = $i + 1;
                                                    echo '<option value="'.$num.'" '.(explode("-", $method["paymentExpiryDate"])[1] === $num ? "selected" : "").'>'.$num.'</option>';
                                                }
                                            ?>
                                        </select>
                                        <select class="form-select" name="cardType" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">
                                            <?php
                                                $currentYear = date("Y");
                                                $count = 0;
                                                for ($i = 0; $i < 20; $i++) {
                                                    $year = (intval($currentYear)+$i);
                                                    echo '<option value="'.$year.'" '.(explode("-", $method["paymentExpiryDate"])[0] === $year ? "selected" : "").'>'.$year.'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col"><input class="form-control" type="text" name="cardCVV" maxlength=3 placeholder="Card CVV" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;"/></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 8px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 3px #cccccc;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: #364652;">Review</h4>
                            <h6 class="text-muted card-subtitle mb-2"><?php echo $itemCount; ?> items</h6>
                            <?php
                                echo Cart::GenerateOrder();
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 8px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 3px #cccccc;">
                        <div class="card-body d-none d-md-flex align-items-center align-content-center">
                            <button class="btn btn-primary float-start" id="placeOrder" type="button" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">Place Order</button>
                            <h5 class="d-inline-flex card-title" style="margin: 0px;margin-left: 8px;color: #364652;">Order Total: $ <?php echo number_format($total, 2); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="margin: 12px 0;border-style: none;box-shadow: 0px 0px 3px #cccccc;">
                <div class="card-body"><button class="btn btn-primary" id="placeOrderSummary" type="button" style="width: 100%;box-shadow: 0px 0px 3px #169884;border-style: none;background: #1cc4ab;">Place Order</button>
                    <h4 class="card-title" style="margin-top: 8px;color: #364652;">Order Summary</h4>
                    <h6 class="text-muted card-subtitle mb-2"><span class="float-end">Summary</span>Description</h6>
                    <p class="card-text"><span class="float-end">$ <?php echo number_format($total, 2); ?></span>Items:</p>
                    <p class="fw-semibold card-text"><span class="float-end">$ <?php echo number_format($total, 2); ?></span>Order Total</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(() => {
        let data = {
            'customerId': <?php echo $customerId; ?>,
            'shiptoAddress': $("input[name=shipToAddress").val() + "<?php echo Customer::$addressDelimeter; ?>" + $("input[name=shipToUAFN]").val(),
            'shiptoCity': $("input[name=shipToCity]").val(),
            'shiptoState': $("input[name=shipToState]").val(),
            'shiptoPostalCode': $("input[name=shipToPostalCode]").val(),
            'shiptoCountry': $("input[name=shipToCountry]").val(),
        }
        let ajaxUrl = '/include/api/cart.php?action=';
        let placeOrderBtn = $("#placeOrder");
        let placeOrderSumBtn = $("#placeOrderSummary");
        placeOrderBtn.click((e) => {
            e.preventDefault();
            ajaxRequest(ajaxUrl+"checkout", data, 'POST', (res) => {
                console.log(res);
                res = JSON.parse(res);
                showSnackbar(res.message);
                if (res.status) {
                    window.location.href = '/orders';
                }
            });
        })
        placeOrderSumBtn.click((e) => {
            e.preventDefault();
            ajaxRequest(ajaxUrl+"checkout", data, 'POST', (res) => {
                console.log(res);
                res = JSON.parse(res);
                showSnackbar(res.message);
                if (res.status) {
                    window.location.href = '/orders';
                }
            });
        })
    })
</script>
<?php
    require 'include/footer.php';
?>