<?php
require 'include/main.php';
$page_name = 'Treasure Trove';
require ('include/header.php');
?>
<section>
    <!-- Start: Simple Slider -->
    <div class="simple-slider">
        <!-- Start: Slideshow -->
        <div class="swiper-container">
            <!-- Start: Slide Wrapper -->
            <div class="swiper-wrapper">
                <?php
                    $topProducts = OrderProduct::GetMostOrderedProducts(5);
                    foreach($topProducts as $product) {
                        $prod = Product::GetProduct($product['productId']);
                        echo '<div class="swiper-slide d-flex justify-content-center align-items-end" style="background: url(';
                        echo $prod->ProductImageLink();
                        echo ') center center / cover no-repeat;object-fit: cover;backdrop-filter: blur(10px);">';
                        echo '<span class="fw-bold card-subtitle" style="margin-bottom: 48px; background: #111111; border-radius: 6px; box-shadow: 0 0 3px #000000; padding: 16px; color: #1cc4ab"><a href="/product.php?pid='.$prod->productId.'" style="text-decoration: none">'.$prod->productName.'</a></span>';
                        echo '<span class="fw-bold card-subtitle" style="margin-left: 8px;margin-bottom: 48px; background: #111111; border-radius: 6px; box-shadow: 0 0 3px #000000; padding: 16px; color: #1cc4ab"> Has been ordered: '.$product['orderedTimes'].' times!</span></div>';
                    }
                ?>
                <!-- Start: Slide -->
                <!-- <div class="swiper-slide" style="background: url(&quot;https://cdn.bootstrapstudio.io/placeholders/1400x800.png&quot;) center center / cover no-repeat;"></div> -->
                <!-- End: Slide -->
                <!-- Start: Slide -->
                <!-- <div class="swiper-slide" style="background: url(&quot;https://cdn.bootstrapstudio.io/placeholders/1400x800.png&quot;) center center / cover no-repeat;"></div> -->
                <!-- End: Slide -->
            </div><!-- End: Slide Wrapper -->
            <!-- Start: Pagination -->
            <div class="swiper-pagination"></div><!-- End: Pagination -->
            <!-- Start: Previous -->
            <div class="swiper-button-prev"></div><!-- End: Previous -->
            <!-- Start: Next -->
            <div class="swiper-button-next"></div><!-- End: Next -->
        </div><!-- End: Slideshow -->
    </div><!-- End: Simple Slider -->
</section><!-- Start: Lightbox Gallery -->
<!-- <section class="photo-gallery py-4 py-xl-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8 col-xl-6 text-center mx-auto">
                <h2>Popular Categories</h2>
                <p class="w-lg-50">Some random text goes here.</p>
            </div>
        </div>!-- Start: Photos --
        <div class="row gx-2 gy-2 row-cols-1 row-cols-md-2 row-cols-xl-3 photos" data-bss-baguettebox="">
        </div>!-- End: Photos --
    </div>
</section>!-- End: Lightbox Gallery -->
<section class="container" style="padding-top:50px">
    
<?php
    $products = Product::getRandomProductsPerCategory(4);
    // sort products per categoryId and put in object array
    $productsPerCategory = [];
    foreach ($products as $product) {
        $productsPerCategory[$product['categoryId']][] = $product;
    }
    // loop through object array and display products
    foreach ($productsPerCategory as $category) {
        $category = Category::GetCategory($category[0]['categoryId']);
        echo '<div class ="row d-flex" style="padding: 10px 20px">';
        // echo '<div><h4 style="background: rgba(250, 250, 250, 0.7);
        //                     padding: 8px;
        //                     border-radius: 8px;
        //                     box-shadow: 0px 0px 3px #cccccc;
        //                     color: #333333;
        //                     width: auto !important;
        //                     display: inline-block;
        //                     font-weight: semibold;
        //                 ">' . $category->categoryName . '</h4></div>
        echo '
        <div class="card" style="/* box-shadow: 0px 0px 6px #cccccc*/;border-style: none;border-radius: 8px;margin: 16px 0;background:none">
            <div class="card-body align-content-center" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                <a href="/category/'.str_replace('/', '-', $category->categoryName).'" style="text-decoration: none"><h5 class="card-title" style="color: #1cc4ab;margin:0px">'.$category->categoryName.'</h5></a>
            </div>
        </div>
        </br>';
        foreach ($productsPerCategory[$category->getCategoryId()] as $product) {
            $pd = Product::GetProduct($product['productId']);
            if ($product['productImageURL'] === null || $product['productImageURL'] === '') {
                $product['productImageURL'] = 'https://cdn.bootstrapstudio.io/placeholders/1400x800.png';
            }
            echo '<div class="col-auto col-sm-6 col-md-6 col-lg-3">';
            echo '<div class="card" style="margin: 4px">';
            echo '<img class="card-img-top" src="'.$product['productImageURL'] . '" alt="Card image cap" style="object-fit: cover; height: 230px;backdrop-filter: blur(10px);">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $product['productName'] . '</h5>';
            echo '<h6 class="card-text">$'. $product['productPrice']. '</p>';
            echo '<p class="card-text">' . $product['productDesc'] . '</p>';
            echo '<a href="product.php?pid=' . $product['productId'] . '" class="btn btn-primary btn-sm" style="margin: 0px; box-shadow: 0px 0px 6px #0d6efd; border: none;">View</a>';
            // echo '<a class="btn btn-primary" style="margin-left:8px" href="#">Add to Cart</a>';
            echo $pd->DisplayAddToCartButton(8);
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
?>
</section>
<?php
    include 'include/footer.php';
?>