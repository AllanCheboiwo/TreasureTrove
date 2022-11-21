<?php
require 'include/main.php';
$page_name = "Shipping";
$order = null;
if (isset($_REQUEST['oid'])) {
    $orderId = $_REQUEST['oid'];
    if (OrderSummary::GetOrder($orderId)) {
        $order = new OrderSummary($orderId);
    } else {
        header("Location: ".$_HTTP['REFERER']);
    }
}
require 'include/header.php';
?>
<div class="container" style="padding: 10px">
    <a href="/orders" class="btn btn-primary">Back to Orders</a>
</div>
<?php
require 'include/footer.php';
?>