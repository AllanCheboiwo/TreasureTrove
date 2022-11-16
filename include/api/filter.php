<?php
    require '../db_credentials.php';

    $category = null;
    $sort = "";
    $filter = "";
    $minPrice = 0;
    $maxPrice = 1000;
    $products;
    $cat;
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
    if ($cat === "all") {
        $products = Product::SortAndFilterProducts($sort, $filter, $minPrice, $maxPrice);
    } else {
        $category = Category::GetCategoryByName($cat);
        $products = Product::GetProductsByCategoryName($cat);
        $products = Product::SortAndFilterProductByCategoryName($category->categoryName, $sort, $filter, $minPrice, $maxPrice);
    }
    echo json_encode($products);