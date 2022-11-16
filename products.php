<?php
    require 'include/main.php';
    $page_name = "All Products | Treasure Trove";
    $category = null;
    $sort = "";
    $filter = "";
    $minPrice = 0;
    $maxPrice = 1000;
    $prods;
    $cat = "all";
    if (isset($_REQUEST['category'])) {
        // && ($_REQUEST['category'] != "all" || $_REQUEST['category'] != "")
        $cat = str_replace('-', '/', $_REQUEST['category']);
    }
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
    }
    // print_r($cat);
    if ($cat === "all" || $cat === "") {
        $products = Product::SortAndFilterProducts($sort, $filter, $minPrice, $maxPrice);
    } else {
        $category = Category::GetCategoryByName($cat);
        $products = Product::GetProductsByCategoryName($cat);
        $products = Product::SortAndFilterProductByCategoryName($category->categoryName, $sort, $filter, $minPrice, $maxPrice);
    }
    require "include/header.php";
?>
<section class="container" style="padding-top: 20px">
    <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 16px 0;">
        <div class="card-body" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
            <h4 class="card-title align-content-center align-items-center" style="color: #1cc4ab; margin-bottom: 0">Products Listing</h4>
            <h6 class="x" style="margin-bottom: 0">
            </h6>
        </div>
    </div>
</section>
<section class="container">
    <!-- Start: Sidebar -->
    <div class="container" style="padding: 8px 0px;">
        <div class="row g-0">
            <div class="col-md-12 col-lg-3 col-xxl-2 offset-xxl-0 order-first" style="padding: 8px;">
                <div>
                    <h6 style="padding: 8px;border-radius: 4px;background: #055160;color: #ffffff;width: calc(100% - 90px);display: inline-block;box-shadow: 0px 0px 6px #055160;">Filter &amp; Sort</h6>
                    <button class="btn btn-primary justify-content-center align-items-center d-inline-flex" type="button" style="display: inline-block;margin-bottom: 6px;background: #055160;border-style: none;border-radius: 4px;box-shadow: 0px 0px 6px #055160;margin-left: 8px;padding: 8px 12px; max-height:35px" id="updateSortFilter">Update</button>
                </div>
                <ul class="list-group" style="box-shadow: 0px 0px 6px #055160;">
                    <li class="list-group-item" style="background: #055160;color: #ffffff;"><span class="fw-bold">Category</span>
                        <hr style="margin: 8px 0px;" />
                        <select class="form-select-sm d-flex justify-content-xxl-center" name="category" style="border-style: none;position: relative;box-shadow: 0px 0px 6px #ffffff;color: #364652; width: 90%">
                            <option value="all" <?php echo ($category == null || $category == '') ? "selected" : "" ?>>All</option>
                            <?php
                                $categories = Category::GetCategories();
                                foreach ($categories as $cat) {
                                    echo '<option value="'.$cat['categoryName'].'" '.(($category->categoryName == $cat['categoryName']) ? "selected" : "").'>'.$cat['categoryName'].'</option>';
                                }
                            ?>
                        </select>
                    </li>
                    <li class="list-group-item" style="background: #055160;color: #ffffff;"><span class="fw-bold">Sort</span>
                        <hr style="margin: 8px 0px;" />
                        <select class="form-select-sm d-flex justify-content-xxl-center" name="sort" style="border-style: none;position: relative;box-shadow: 0px 0px 6px #ffffff;color: #364652; width: 90%">
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
                                <input class="d-flex" type="number" name="minPrice" min="0" max="1000" value="<?php echo $minPrice ?>" step="50" style="border-radius: 4px;border-style: none;box-shadow: 0px 0px 6px #ffffff;text-align: right;padding: 2px;width: 80%;color: #364652;" placeholder="min" inputmode="numeric" />
                            </div>
                            <div style="margin: 4px;width: 100%;">
                                <h6 class="fw-semibold">Max</h6>
                                <input class="d-flex" type="number" name="maxPrice" min="0" max="1000" value="<?php echo $maxPrice ?>" step="50" style="border-radius: 4px;border-style: none;box-shadow: 0px 0px 6px #ffffff;text-align: right;padding: 2px;width: 80%;color: #364652;" placeholder="max" inputmode="numeric" />
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col" id="prodList">
                
                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4">
                    <?php
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
<script type="text/javascript" defer>
    $(document).ready(function() {
        let selectPrice = $('select[name="price"]');
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
            let category = $('select[name="category"]').val();
            let url;
            if (category != '' || category != 'all') {
                url = '/products/category/'+category+'/sort/'+sort+'/filter/'+price+'/'+minPrice+'-'+maxPrice;
            } else {
                
                url = '/products/sort/'+sort+'/filter/'+price+'/'+minPrice+'-'+maxPrice;
            }
            // window.location.href = url;
            let reqUrl = '/include/api/filter.php';
            // url = reqUrl+'?category='+category+'&sort='+sort+'&filter='+price'&minPrice='+minPrice+'&maxPrice='+maxPrice;
            // console.log(url);
            let data = {
                "sort": sort,
                "price": price,
                "minPrice": minPrice,
                "maxPrice": maxPrice,
                "category": category
            };
            ajaxRequest(reqUrl, data, 'POST', (resp) => {
                console.log(resp);
                let products = JSON.parse(resp);
                // console.log(resp);
                let col = $('#prodList');
                col.html('');
                let newCol = $('<div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4"></div>');
                let count = 0;
                // col.innerHtml=newCol;
                for (let i = 0; i < products.length; i++) {
                    let product = products[i];
                    // console.log(product)
                    let productId = product['productId'];
                    let prodCard = $("<div class='col col-md-3'></div>");
                    let card = $("<div class='card' style='margin: 4px'></div>");
                    let cardImg = $('<img class=\'card-img-top\' src=\'https://cdn.bootstrapstudio.io/placeholders/1400x800.png\' alt=\'Card image cap\'>');
                    let cardBody = $("<div class='card-body'></div>");
                    let cardTitle = $("<h5 class='card-title'></h5>");
                    let cardText = $("<h6 class='card-text'>$</p>");
                    let cardDesc = $("<p class='card-text'></p>");
                    let cardView = $("<a href='product.php?pid=' class='btn btn-primary'>View</a>");
                    let cardCart = $("<a class='btn btn-primary preview-toggle' style='margin: 0px; box-shadow: 0px 0px 6px #0d6efd; border: none;'>Cart It</a>");
                    cardTitle.text(product['productName']);
                    cardText.text(product['productPrice']);
                    cardDesc.text(product['productDesc']);
                    cardView.attr('href', 'product.php?pid='+product['productId']);
                    cardCart.attr('onclick', 'cartAjax({"pid":'+product['productId']+',"pname":"'+product['productName']+'","price":'+product['productPrice']+',"quantity":1}, "add")');
                    cardBody.append(cardTitle);
                    cardBody.append(cardText);
                    cardBody.append(cardDesc);
                    cardBody.append(cardView);
                    cardBody.append(cardCart);
                    card.append(cardImg);
                    card.append(cardBody);
                    prodCard.append(card);
                    newCol.append(prodCard);
                    count++;
                    // nCol is a sibling to newCol, each col contains 4 material card items
                    // after every 4 items, a new nCol is added to newCol which is then appended to col
                    if (count % 4 == 0) {
                        col.append(newCol);
                        newCol = $('<div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4"></div>');
                    }
                    // console.log(newCol)

                }
                // col.append('</div>');
            });
        }
        let updateSortFilterBtn = $('#updateSortFilter');
        updateSortFilterBtn.on('click', function() {
            // e.preventDefault();
            console.log('clicked');
            updateSortFilter();
        });
        // updateSortFilter();
    });
</script>
<?php
    require 'include/footer.php'
?>