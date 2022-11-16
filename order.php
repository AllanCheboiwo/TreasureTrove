<?php
    require 'include/main.php';
	$orderId;
	$order;
	if ((isset($_REQUEST['orderId']) || isset($_REQUEST['oid'])) && ($_REQUEST['orderId'] !== null || $_REQUEST['oid'] !== null)) {
		$orderId = $_REQUEST['orderId'] || $_REQUEST['oid'];
		$order = new OrderSummary($order);
	}
	$customer = new Customer($order->customerId);
	$page_name = "Order | Treasure Trove";
    require 'include/header.php';
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 16px 0;">
                <div class="card-body" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                    <h4 class="card-title" style="color: #1cc4ab;">Order Details</h4>
                    <h6 class="text-muted card-subtitle mb-2">Ordered on <?php echo $order->orderDate;?> | Order# <?php echo $orderId;?></h6>
                </div>
            </div>
        </div>
    </div>
    <section style="padding: 8px;border-style: none;">
        <div class="card" style="border-style: none;box-shadow: 0px 0px 6px #cccccc;">
            <div class="card-body">
                <section>
                    <h4>Shipping Address</h4>
                    <h6 class="text-muted mb-2"><?php echo $customer->firstName . " " . $customer->lastName ?></h6>
                    <p><?php echo $order->FullAddress(); ?></p>
                </section>
                <div class="row g-0 row-cols-1 row-cols-md-2">
                    <div class="col">
                        <section style="padding: 4px;">
                            <h5>Payment Methods</h5>
                            <h6 class="text-muted mb-2">
							<!-- PaymentMethodType ending in #### -->
								<?php
									$methods = $customer->GetPaymentMethods();
									if (!empty($methods)) {
										$chosenMethod = $methods[0];
										// get last 4 digits of paymentmethodnumber
										$last4Digits = substr($chosenMethod['paymentMethodNumber'], -4);
                                        // $last4Digits = substr($last4Digits, 0, 4);
                                        // $last4Digits = substr($last4Digits, -4);
										echo $method['paymentMethodType'] . ' ending in ' . $last4Digits;

									} else {
										echo "No Payment Method";
									}
								?>
							</h6>
                        </section>
                    </div>
                    <div class="col">
                        <section style="padding: 4px;">
                            <h5>Order Summary</h5>
                            <h6 class="text-muted mb-2">Description<span class="float-end">Amount</span></h6>
                            <p>Item(s) Subtotal:<span class="float-end">CDN$ <?php echo $order->totalAmount ?></span></p>
                            <p class="fw-bold">Grand Total:<span class="float-end">CDN$ <?php echo $order->totalAmount ?></span></p>
                        </section>
                    </div>
                </div>
                <div id="accordion-1" class="accordion" role="tablist" style="margin-top: 8px;">
                    <div class="accordion-item">
                        <h2 class="accordion-header" role="tab"><button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-1 .item-1" aria-expanded="true" aria-controls="accordion-1 .item-1" style="background: #364652;color: #1cc4ab;">Transaction</button></h2>
                        <div class="accordion-collapse collapse show item-1" role="tabpanel" data-bs-parent="#accordion-1">
                            <div class="accordion-body">
                                <p class="mb-0">Items shipped: Shipped Date - 
								<?php
									$methods = $customer->GetPaymentMethods();
									if (!empty($methods)) {
										$chosenMethod = $methods[0];
										// get last 4 digits of paymentmethodnumber
										$last4Digits = substr($chosenMethod['paymentMethodNumber'], -4);
                                        // $last4Digits = substr($last4Digits, 0, 4);
                                        // $last4Digits = substr($last4Digits, -4);
										echo $method['paymentMethodType'] . ' ending in ' . $last4Digits;

									} else {
										echo "No Payment Method";
									}
								?>: CDN$ <?php echo $order->totalAmount ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
	<section style="padding: 8px;border-style: none;">
		<div class="row g-0" style="border-radius: 6px;background: #ffffff;border-style: none;box-shadow: 0px 0px 6px #cccccc;color: #364652;">
			<div class="col-sm-12 col-md-8 col-lg-9 col-xxl-9">
				<?php
					echo OrderSummary::DisplayOrderSummary($orderId);
				?>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-3 col-xxl-3 d-flex justify-content-center align-items-center" style="padding: 10px;">
				<div class="btn-group-vertical d-flex" role="group">
					<a class="btn btn-primary" role="button" style="border-radius: 6px;border-style: none;box-shadow: 0px 0px 6px #169884;background: #1cc4ab;">
						Write Product Reviews
					</a>
					<a class="btn btn-primary" role="button" style="border-radius: 6px;border-style: none;box-shadow: 0px 0px 6px #faf3dd;background: #faf3dd;color: #364652;margin-top: 8px;">
					<?php
						if ($order->orderDate != null) {
							echo 'Track Package';
						} else {
							echo 'Place Order';
						}
					?>
					</a>
				</div>
			</div>
	</section>
</div>
<?php
	require 'include/footer.php';
?>
