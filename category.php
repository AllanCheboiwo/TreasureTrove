<?php
    require 'include/main.php';
    $category= null;
    if (isset($_GET['category'])){
        $cat = str_replace('-', '/', $_REQUEST['category']);
        $category = Category::GetCategoryByName($cat);
    } else {
        header('Location: listcat.php');
    }
    $page_name = $category->categoryName." | Treasure Trove";
    $sort = "alphabetical";
    $filter = "lowToHigh";
    $minPrice = 0;
    $maxPrice = 1000000;
    $products;
    if (isset($_REQUEST['sort'])) {
        $sort = $_REQUEST['sort'];
        if (isset($_REQUEST['filter'])) {
            $filter = $_REQUEST['filter'];
            if ($filter == "custom") {
                if (isset($_REQUEST['minPrice']) && isset($_REQUEST['maxPrice'])) {
                    $minPrice = $_REQUEST['minPrice'];
                    $maxPrice = $_REQUEST['maxPrice'];
                    
                }
            }
        }
        // $url = "/category/".$_REQUEST['category']."/sort/".$sort."/filter/".$filter."/".$minPrice."-".$maxPrice;
        // echo "<script>console.log('".$url."');</script>";
    }
    $products = Product::SortAndFilterProductByCategoryName(str_replace('-', '/', $_REQUEST['category']), $sort, $filter, $minPrice, $maxPrice);
    
    require "include/header.php";
?>
<section class="container" style="padding-top: 20px">
    <!-- <h3 style="padding: 8px;background: #ffffff;border-style: none;border-radius: 8px;box-shadow: 0px 0px 3px #cccccc;color: #364652;margin: 0px;">
        < ?php
            // echo str_replace('-', '/', $_REQUEST['category']);
        ?>
    </h3> -->
    
    <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 16px 0;">
        <div class="card-body" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
            <h4 class="card-title align-content-center align-items-center" style="color: #1cc4ab; margin-bottom: 0"><?php echo str_replace('-', '/', $_REQUEST['category']);?></h4>
            <h6 class="text-muted card-subtitle mb-2" style="margin-bottom: 0">
                <?php echo $category->categoryDesc; ?>
            </h6>
        </div>
    </div>
</section>
<section class="container">
    <!-- Start: Sidebar -->
    <div class="container" style="padding: 8px 0px;">
        <div class="row g-0">
            <div class="col-md-12 col-lg-3 col-xxl-2 offset-xxl-0 order-first" style="padding: 8px;">
                <script type="text/javascript">
                    $(document).ready(function() {
                        var selectPrice = $('select[name="price"]');
                        // var filterPrices = $('#filterPrices');
                        let val = selectPrice.val();
                        console.log(val);
                        if (val == 'custom') {
                            $('div #filterPrices').removeAttr('style');
                            $('div #filterPrices').attr('style', 'display: flex !important');
                        } else {
                            $('div #filterPrices').attr('style', 'display: none !important');
                        }
                        selectPrice.change(function() {
                            val = $(this).val();
                            console.log(val);
                            if (val == 'custom') {
                                // filterPrices.show();
                                // $('#filterPrices').css('visibility', 'visible !important');
                                $('div #filterPrices').removeAttr('style');
                                $('div #filterPrices').attr('style', 'display: flex !important');
                            } else {
                                // $('#filterPrices').hide();
                                $('div #filterPrices').attr('style', 'display: none !important');
                            }
                        });

                        function updateSortFilter() {
                            let sort = $('select[name="sort"]').val();
                            let price = $('select[name="price"]').val();
                            let minPrice = $('input[name="minPrice"]').val() || 0;
                            let maxPrice = $('input[name="maxPrice"]').val() || 1000;
                            let category = '<?php echo $_REQUEST['category']; ?>';
                            // let url = 'category.php?category='+category+'&sort='+sort+'&price='+price+'&minPrice='+minPrice+'&maxPrice='+maxPrice;
                            let url = '/category/'+category+'/sort/'+sort+'/filter/'+price+'/'+minPrice+'-'+maxPrice;
                            window.location.href = url;
                        }
                        let updateSortFilterBtn = $('#updateSortFilter');
                        updateSortFilterBtn.click(function() {
                            // e.preventDefault();
                            console.log('clicked');
                            updateSortFilter();
                        });
                    });
                </script>
                <div>
                    <h6 style="padding: 8px;border-radius: 4px;background: #055160;color: #ffffff;width: calc(100% - 90px);display: inline-block;box-shadow: 0px 0px 6px #055160;">Filter &amp; Sort</h6>
                    <button class="btn btn-primary justify-content-center align-items-center d-inline-flex" type="button" style="display: inline-block;margin-bottom: 6px;background: #055160;border-style: none;border-radius: 4px;box-shadow: 0px 0px 6px #055160;margin-left: 8px;padding: 8px 12px; max-height:35px" id="updateSortFilter">Update</button>
                </div>
                <ul class="list-group" style="box-shadow: 0px 0px 6px #055160;">
                    <li class="list-group-item" style="background: #055160;color: #ffffff;"><span class="fw-bold">Sort</span>
                        <hr style="margin: 8px 0px;" /><select class="form-select-sm d-flex justify-content-xxl-center" name="sort" style="border-style: none;position: relative;box-shadow: 0px 0px 6px #ffffff;color: #364652; width: 90%">
                            <option value="alphabetical" <?php echo ($sort == 'alphabetical') ? "selected" : "" ?>>Alphabetically</option>
                            <option value="reverse" <?php echo ($sort == 'reverse') ? "selected" : "" ?>>Reverse</option>
                            <option value="chronological" <?php echo ($sort == 'chronological') ? "selected" : "" ?>>Chronologically</option>
                        </select>
                    </li>
                    <li class="list-group-item" style="background: #055160;color: #ffffff;padding-bottom: 16px;"><span class="fw-bold">Filter</span>
                        <hr style="margin: 8px 0px;" />
                        <select class="form-select-sm d-flex justify-content-xxl-center" name="price" style="border-style: none;position: relative;box-shadow: 0px 0px 6px #ffffff;color: #364652;padding: 4px; width: 90%">
                            <option value="lowToHigh" <?php echo ($filter == 'lowToHigh') ? "selected" : "" ?>>Lowest to Highest</option>
                            <option value="highToLow" <?php echo ($filter == 'highToLow') ? "selected" : "" ?>>Highest to Lowest</option>
                            <option value="custom" <?php echo ($filter == 'custom') ? "selected" : "" ?>>Custom</option>
                        </select>
                        <div class="d-flex justify-content-evenly align-items-start align-content-around justify-content-xxl-center" id="filterPrices">
                            <div style="margin: 4px;width: 100%;">
                                <h6 class="fw-semibold">Min</h6>
                                <input class="d-flex" type="number" name="minPrice" min="0" max="1000" value="<?php echo $minPrice ?>" step="50" style="border-radius: 4px;border-style: none;box-shadow: 0px 0px 6px #ffffff;text-align: right;padding: 2px;width: 80%;color: #364652;" placeholder="minPrice" inputmode="numeric" />
                            </div>
                            <div style="margin: 4px;width: 100%;">
                                <h6 class="fw-semibold">Max</h6>
                                <input class="d-flex" type="number" name="maxPrice" min="0" max="1000" value="<?php echo $maxPrice ?>" step="50" style="border-radius: 4px;border-style: none;box-shadow: 0px 0px 6px #ffffff;text-align: right;padding: 2px;width: 80%;color: #364652;" placeholder="maxPrice" inputmode="numeric" />
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div class="col">
                <!-- <div data-reflow-type="product-list" data-reflow-order="custom_asc" data-reflow-layout="cards" data-reflow-shoppingcart-url="cart.html" style="padding: 30px 50px;">
                    <div class="reflow-product-list ref-cards">
                        <div class="ref-products">
                        </div>
                    </div>
                </div> -->
                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4">
                    <?php
                        // foreach ($products as $product) {
                        //     echo Product::CardMaterial($product['productId']);
                        // }
                        // after every 4 products, create a new row for the next 4
                        $count = 0;
                        foreach ($products as $product) {
                            echo Product::CardMaterial($product['productId']);
                            $count++;
                            if ($count % 4 == 0) {
                                echo "</div><div class='row row-cols-1 row-cols-sm-2 row-cols-xl-4'>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div><!-- End: Sidebar -->
</section>
<?php
    require 'include/footer.php'
?>