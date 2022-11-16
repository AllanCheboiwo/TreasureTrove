<?php
require 'include/main.php';
$page_name = "Cart | Treasure Trove"; // Set page name
require "include/header.php";
?>
<section class="container-md" style="padding: 30px 40px;">
    <div data-reflow-type="shopping-cart">
        <div class="reflow-shopping-cart" style="display: block;">
            <div class="ref-loading-overlay"></div>
            <div class="ref-message" style="display: none;"></div>
            <div class="ref-cart" style="display: block;">
                <!-- <div class="ref-heading">Shopping Cart</div> -->
                <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 16px 0;">
                    <div class="card-body align-content-center" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                        <h5 class="card-title" style="color: #1cc4ab;margin:0px">Shopping Cart</h5>
                    </div>
                </div>
                <div class="ref-th">
                    <div class="ref-product-col">Product</div>
                    <div class="ref-price-col">Price</div>
                    <div class="ref-quantity-col">Quantity</div>
                    <div class="d-flex justify-content-center align-items-center">Total</div>
                </div>
                <div class="ref-cart-table">
                    <?php
                        $cart = null;
                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            $cart = $_SESSION['cart'];
                            $total = 0;
                            foreach ($cart as $id => $prod) {
                                $product = Product::GetProduct($prod['productId']);
                                $category = new Category($product->categoryId);
                                $price = $prod['price'];
                                $subtotal = $prod['quantity'] * $price;
                                // echo material card for product in cart
                                echo '
                                <div class="card" id="'.$prod['productId'].'" style="margin: 8px;border-style: none;box-shadow: 0px 0px 6px #fafafa;">
                                    <div class="row g-0">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="container" style="padding: 0px;">
                                                <div class="row g-0">
                                                    <div class="col-sm-4 col-md-4" style="padding: 10px">
                                                        '.$product->GetProductImage(true).'
                                                    </div>
                                                    <div class="col-sm-8 col-md-8" style="padding: 10px;">
                                                        <a href="/product.php?pid='.$product->productId.'" style="text-decoration: none"><h4>'.$product->productName.'</h4></a>
                                                        <a href="/category/'.str_replace('/', '-', $category->categoryName).'" style="text-decoration: none"><h6 class="text-muted mb-2">'.$category->categoryName.'</h6></a>
                                                        <p>'.$product->productDesc.'</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-2 d-flex justify-content-center align-items-center" style="padding: 10px;"><input class="form-control-plaintext d-flex" type="text" value="$'.number_format($price, 2).'" readonly style="text-align: center;" /></div>
                                        <div class="col-sm-4 col-md-2 d-grid justify-content-center align-items-center align-content-center" style="padding: 10px;">
                                            <div class="input-group d-flex" style="width: 100%;">
                                                <button class="btn btn-primary d-xxl-flex justify-content-xxl-center align-items-xxl-center" type="button" onclick="cartAjax({\'pname\': \''.$prod['productName'].'\', \'pid\': '.$prod['productId'].', \'price\': \''.$prod['price'].'\', \'quantity\': '.($prod['quantity'] - 1).'}, \'update\')" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">
                                                    <i class="material-icons">remove</i>
                                                </button>
                                                <input class="form-control" type="number" name="quantity" placeholder="Qty" autocomplete="off" min="1" max="100" step="1" style="text-align: center; outline: none !important" onkeyup="cartAjax({\'pname\': \''.$prod['productName'].'\', \'pid\': '.$prod['productId'].', \'price\': \''.$prod['price'].'\', \'quantity\': this.value}, \'update\')" value="'.$prod['quantity'].'" disabled/>
                                                <button class="btn btn-primary d-xxl-flex justify-content-xxl-center align-items-xxl-center" type="button" onclick="cartAjax({\'pname\': \''.$prod['productName'].'\', \'pid\': '.$prod['productId'].', \'price\': \''.$prod['price'].'\', \'quantity\': '.($prod['quantity'] + 1).'}, \'update\')" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">
                                                    <i class="material-icons">add</i>
                                                </button>
                                            </div>
                                            <a class="justify-content-center cart-item" id="cart-item-'.$prod['productId'] .'" data-product-id="'.$prod['productId'].'" style="margin: 0px auto;text-decoration: none; cursor: pointer">
                                                <span style="color: rgb(234, 64, 64);">REMOVE</span>
                                            </a>
                                        </div>
                                        <div class="col-sm-4 col-md-2 d-flex justify-content-center align-items-center" style="padding: 10px;"><input class="form-control-plaintext d-flex" type="text" value="$'.number_format($subtotal, 2).'" readonly style="text-align: center;font-weight: bold;" /></div>
                                    </div>
                                </div>
                                ';
                            }
                            foreach($_SESSION['cart'] as $productId => $cartproduct) {
                                $total += $cartproduct['price'] * $cartproduct['quantity'];
                            }
                            $total = number_format($total, 2);
                            // echo total
                        } else {
                            echo '<div class="d-flex justify-content-center align-content-center align-items-center" style="padding: 16px"><h4 class="text-muted card-subtitle mb-2">Your cart is empty.</h4></div>';
                        }
                    ?>
                <div class="ref-footer" style="margin-top:0">
                    <div class="ref-links"></div>
                    <div class="ref-totals">
                        <div class="ref-subtotal">Subtotal: <?php echo $total; ?></div>
                        <div class="ref-row ref-checkout-buttons">
                            <button class="btn btn-primary" style="background: #169884; border-style: none; box-shadow: 0px 0px 6px #169884; outline: none; margin-right: 8px" onclick="window.location.href='/'">Continue Shopping</button>
                            <button class="btn btn-primary" style="background: #169884; border-style: none; box-shadow: 0px 0px 6px #169884; outline: none;" onclick="window.location.href='/checkout'">Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<script type="text/javascript">
</script>
<?php
    include 'include/footer.php';
?>