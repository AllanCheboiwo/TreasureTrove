<?php
    require 'include/main.php';
    $product = null;
    if (isset($_GET['pid'])){
        $product = Product::GetProduct($_REQUEST['pid']);
    } else {
        $referrer = $_SERVER['HTTP_REFERER'];
        // if referrer is not set or is equal to current page, redirect to index.php
        if (empty($referrer) || $referrer == $_SERVER['REQUEST_URI']) {
            header('Location: /index.php');
        } else {
            header('Location: ' . $referrer);
        }
    }
    $page_name = $product->productName." | Treasure Trove";
    $productImage = $product->productImageURL;
    if ($productImage == null || $productImage == "") {
        $productImage = 'https://cdn.bootstrapstudio.io/placeholders/1400x800.png';
    }
    require "include/header.php";
?>
<section class="container" style="padding-top: 20px">
    <div class="row g-0 row-cols-1">
        <div class="col">
            <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 16px 0;">
                <div class="card-body" style="background: #364652;border-top-left-radius: 8px;border-top-right-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                    <h4 class="card-title" style="color: #1cc4ab;"><?php echo $product->productName; ?></h4>
                    <a style="text-decoration: none" href="/category/<?php
                            $category = Category::GetCategory($product->categoryId);
                            echo $category->categoryName;?>"><h6 class="text-muted card-subtitle mb-2">
                        <?php
                            $category = Category::GetCategory($product->categoryId);
                            echo $category->categoryName;
                        ?>
                    </h6></a>
                </div>
                <div class="row g-0">
                    <div class="col-md-4">
                        <div id="carousel-1" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-inner">
                                <div class="carousel-item active"><img class="w-100 d-block" src="<?php echo $product->ProductImageLink(); ?>" alt="Slide Image" /></div>
                                <!-- <div class="carousel-item"><img class="w-100 d-block" src="https://cdn.bootstrapstudio.io/placeholders/1400x800.png" alt="Slide Image" /></div>
                                <div class="carousel-item"><img class="w-100 d-block" src="https://cdn.bootstrapstudio.io/placeholders/1400x800.png" alt="Slide Image" /></div> -->
                                <?php
                                    // echo $product->GetProductImage();
                                ?>
                            </div>
                            <div>
                                <a class="carousel-control-prev" href="#carousel-1" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel-1" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                            <ol class="carousel-indicators">
                                <li class="active" data-bs-target="#carousel-1" data-bs-slide-to="0"></li>
                                <!-- <li data-bs-target="#carousel-1" data-bs-slide-to="1"></li>
                                <li data-bs-target="#carousel-1" data-bs-slide-to="2"></li> -->
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-8" style="padding: 10px;">
                        <div class="row row-cols-1">
                            <div class="col">
                                <h5>CAD$ <?php echo $product->productPrice; ?></h5>
                            </div>
                            <div class="col">
                                <p><?php echo $product->productDesc;?></p>
                            </div>
                            <!-- <div class="col-sm-6 col-lg-12 d-flex" style="padding: 12px;position: relative;">
                                <section class="justify-content-center align-items-center">
                                    <div class="input-group d-flex" style="width: 100%;">
                                        <button class="btn btn-primary d-xxl-flex justify-content-xxl-center align-items-xxl-center" type="button" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">
                                            <i class="material-icons">remove</i>
                                        </button>
                                        <input class="form-control" type="number" name="quantity" placeholder="Qty" autocomplete="off" min="1" max="100" step="1" style="text-align: center;" readonly value="1" disabled />
                                        <button class="btn btn-primary d-xxl-flex justify-content-xxl-center align-items-xxl-center" type="button" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">
                                            <i class="material-icons">add</i>
                                        </button>
                                    </div>
                                </section>
                            </div> -->
                            <?php
                                //echo Product::ShowProductQuantityControl($product->productId);
                            ?>
                            <div class="col-sm-6 col-lg-12 d-flex justify-content-start align-items-center align-content-center" style="position: relative;">
                                <!-- <button class="btn btn-primary" type="button">Add To Cart</button> -->
                                <?php
                                    echo $product->DisplayAddToCartButton();
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col" style="padding: 10px;">
                        <button class="btn btn-primary" type="button" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 6px #169884;">Add a review</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <section style="padding: 10px;">
                <h3 style="padding: 8px;background: #1cc4ab;border-style: none;border-radius: 8px;box-shadow: 0px 0px 3px #169884;color: #364652;margin: 0px;">Reviews</h3>
            </section>
            <div class="row g-0 row-cols-1" style="padding: 10px;">
                <div class="col">
                    <div class="card" style="border-style: none;border-radius: 8px;box-shadow: 0px 0px 6px #cccccc;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: #364652;">Customer Name</h4>
                            <h6 class="text-muted card-subtitle mb-2">Reviewed on ReviewDate</h6>
                            <p class="card-text">Nullam id dolor id nibh ultricies vehicula ut id elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p><a class="card-link" href="#">Link</a><a class="card-link" href="#">Link</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Begin Reviews -->
<section class="container">
</section>
<!-- End Reviews -->
<?php
    require "include/footer.php";
?>