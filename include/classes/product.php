<?php

require_once $root.'/include/db_credentials.php';

class Product {
    private static $db;
    public $productId;
    public $productName;
    public $productPrice;
    public $productImageURL;
    public $productImage;
    public $productDesc;
    public $categoryId;

    public function __construct($productId) {
        $db = new DB();
        if(self::$db == null) {
            self::$db = $db->conn;
        }
        $this->productId = $productId;
        $this->loadProduct();
    }

    public function __destruct() {
        if(self::$db != null) {
            self::$db = null;
        }
    }

    public function loadProduct() {
        $sql = "SELECT * FROM product WHERE productId = ?";
        $params = array($this->productId);
        $db = null;
        if (self::$db == null) {
            $db = new DB();
            $db = $db->conn;
        } else {
            $db = self::$db;
        }
        $stmt = sqlsrv_query($db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $this->productId = $row['productId'];
            $this->productName = $row['productName'];
            $this->productPrice = $row['productPrice'];
            $this->productImageURL = $row['productImageURL'];
            $this->productImage = $row['productImage'];
            $this->productDesc = $row['productDesc'];
            $this->categoryId = $row['categoryId'];
        }
    }

    public static function GetProduct($productId) {
        $sql = "SELECT * FROM product WHERE productId = ?";
        $params = array($productId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $product = new Product($product['productId']);
        return $product;
    }

    public static function GetProducts() {
        $products = array();
        $sql = "SELECT * FROM product";
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        do {
            while($row = sqlsrv_fetch_array($stmt)) {
                $product = array(
                    'productId' => $row['productId'],
                    'productName' => $row['productName'],
                    'productPrice' => $row['productPrice'],
                    'productImageURL' => $row['productImageURL'],
                    'productImage' => $row['productImage'],
                    'productDesc' => $row['productDesc'],
                    'categoryId' => $row['categoryId']
                );
                array_push($products, $product);
            }
        } while (sqlsrv_next_result($stmt));
        return $products;
    }

    public static function GetProductsBySearch($term) {
        $sql = "SELECT * FROM product WHERE productName LIKE ?";
        $params = array('%'.$term.'%');
        $db = null;
        if (!self::$db) {
            $db = new DB();
            $db = $db->conn;
        } else {
            $db = self::$db;
        }
        $stmt = sqlsrv_query($db, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $products = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $products[] = $row;
        }
        return $products;
    }

    public static function GetProductsByCategoryId($categoryId) {
        $sql = "SELECT * FROM product WHERE categoryId = ?";
        $params = array($categoryId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $products = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $product = array(
                'productId' => $row['productId'],
                'productName' => $row['productName'],
                'productPrice' => $row['productPrice'],
                'productImageURL' => $row['productImageURL'],
                'productImage' => $row['productImage'],
                'productDesc' => $row['productDesc'],
                'categoryId' => $row['categoryId']
            );
            array_push($products, $product);
        }
        return $products;
    }

    public static function GetProductsByCategory($categoryId) {
        $sql = "SELECT * FROM product WHERE categoryId = (SELECT categoryId FROM category WHERE categoryId = ?)";
        $params = array($categoryId);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $products = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $product = array(
                'productId' => $row['productId'],
                'productName' => $row['productName'],
                'productPrice' => $row['productPrice'],
                'productImageURL' => $row['productImageURL'],
                'productImage' => $row['productImage'],
                'productDesc' => $row['productDesc'],
                'categoryId' => $row['categoryId']
            );
            array_push($products, $product);
        }
        // echo "<script>console.log(".json_encode($products).");</script>";
        return $products;
    }

    public static function GetProductsByCategoryName($categoryName) {
        $sql = "SELECT * FROM product WHERE categoryId = (SELECT categoryId FROM category WHERE categoryName = ?)";
        $params = array($categoryName);
        $db = new DB();
        $stmt = sqlsrv_query($db->conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $products = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $product = array(
                'productId' => $row['productId'],
                'productName' => $row['productName'],
                'productPrice' => $row['productPrice'],
                'productImageURL' => $row['productImageURL'],
                'productImage' => $row['productImage'],
                'productDesc' => $row['productDesc'],
                'categoryId' => $row['categoryId']
            );
            array_push($products, $product);
        }
        return $products;
    }

    public static function getProductCard($productId) {
        $row = Product::GetProduct($productId);
        if ($row != null) {
            return '
            <div class="ref-product">
                <div class="ref-media">
                    '.$row->GetProductImage().'
                </div>
                <div class="ref-product-data">
                    <div class="ref-product-info">
                        <h5 class="ref-name">'.$row->productName.'</h5>
                        <p class="ref-excerpt">'.$row->productDesc.'</p>
                    </div><strong class="ref-price ref-on-sale"><span class="ref-original-price">$'.$row->productPrice.'</span></strong>
                </div>
                <div class="ref-addons">
                '.$row->DisplayAddToCartButton().'
                </div>
            </div>
            ';
        }
    }

    public static function CardMaterial($productId) {
        $row = Product::GetProduct($productId);
        if ($row != null) {
            if ($row->productImageURL == null) {
                $row->productImageURL = 'https://cdn.bootstrapstudio.io/placeholders/1400x800.png';
            }
            return '<div class="col col-md-6">
                        <div class="card" style="margin: 4px; box-shadow: 0px 0px 3px #cccccc; border: none;">
                            <img class="card-img-top" style="background:url('.$row->ProductImageLink(). ') center center / cover no-repeat; object-fit: cover; height: 200px;backdrop-filter: blur(10px);box-shadow: 0px 0px 3px #cccccc;border:none;">
                            <div class="card-body">
                                <h5 class="card-title">' . $row->productName. '</h5>
                                <h6 class="card-text">$'. $row->productPrice. '</p>
                                <p class="card-text">' . $row->productDesc. '</p>
                                <a href="/product.php?pid=' . $row->productId. '" class="btn btn-primary btn-sm" style="margin: 0px; box-shadow: 0px 0px 6px #0d6efd; border: none;">
                                    View
                                </a>'.$row->DisplayAddToCartButton(8).'
                            </div>
                        </div>
                    </div>';
        }
    }

    public static function ShowProductQuantityControl($productId) {
        $prod = Product::GetProduct($productId);
        $orderId = $_SESSION['cart'][0]['orderId'];
        if ($prod != null) {
                return '<div class="col-sm-6 col-lg-12 d-flex" style="position: relative; padding: 12px;">
                            <section class="justify-content-center align-items-center">
                                <div class="input-group d-flex" style="width: 100%;">
                                    <button class="btn btn-primary d-xxl-flex justify-content-xxl-center align-items-xxl-center" type="button" onclick="cartAjax({\'pname\': \''.$prod->productName.'\', \'pid\': '.$prod->productId.', \'price\': \''.$prod->productPrice.'\', \'quantity\': (this.nextSibling.value - 1)}, \'update\')" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">
                                        <i class="material-icons">remove</i>
                                    </button>
                                    <input class="form-control" type="number" name="quantity" placeholder="Qty" autocomplete="off" min="1" max="100" step="1" style="text-align: center; outline: none !important" onkeyup="cartAjax({\'pname\': \''.$prod->productName.'\', \'pid\': '.$prod->productId.', \'price\': \''.$prod->productPrice.'\', \'quantity\': this.value}, \'update\')" value="1" disabled/>
                                    <button class="btn btn-primary d-xxl-flex justify-content-xxl-center align-items-xxl-center" type="button" onclick="cartAjax({\'pname\': \''.$prod->productName.'\', \'pid\': '.$prod->productId.', \'price\': \''.$prod->productPrice.'\', \'quantity\': (this.previousSibling.value - 1)}, \'update\')" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">
                                        <i class="material-icons">add</i>
                                    </button>
                                </div>
                            </section>
                        </div>
            ';
        }
    }

    // public function addProduct($productName, $productPrice,
    // $productImageURL, $productImage, $productDesc, $categoryId) {
    //     $sql = "INSERT INTO product (productName, productPrice,
    //     productImageURL, productImage, productDesc, categoryId) VALUES (?, ?, ?, ?, ?, ?)";
    //     $params = array($productName, $productPrice, $productImageURL, $productImage, $productDesc, $categoryId);
    //     $db = new DB();
    //     $stmt = sqlsrv_query($db->conn, $sql, $params);
    //     if ($stmt === false) {
    //         die(print_r(sqlsrv_errors(), true));
    //     }
    // }

    public static function UpdateProduct($productId, $updateItem, $updateValue) {
        $res = [
            'status' => false,
            'message' => 'Something went wrong'
        ];
        if (isset($productId)) {
            $sql = "UPDATE product SET $updateItem = ? WHERE productId = ?";
            $params = array($updateValue, $productId);
            $db = new DB();
            $stmt = sqlsrv_query($db->conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            } else {
                $res['status'] = true;
                $res['message'] = 'Product updated successfully';
            }
        } else {
            $res['message'] = 'Product not found';
        }
        return $res;
    }

    public static function DeleteProduct($productId) {
        $res = [
            'status' => false,
            'message' => 'Product not found'
        ];
        if (isset($productId)) {
            $sql = "DELETE FROM product WHERE productId = ?";
            $params = array($productId);
            $db = new DB();
            $stmt = sqlsrv_query($db->conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $res['status'] = true;
            $res['message'] = 'Product deleted successfully';
        } else {
            $res['message'] = 'Product ID not found';
        }
        return $res;
    }

    public function getProductId() {
        return $this->productId;
    }

    /**
     * Returns list of products based on category in a certain order by a certain price filter
     * 
     * @param string $categoryName
     * @param string $orderSort - alphabetical, reverse, chronological (by product id)
     * @param string $priceFilter - lowToHigh, highToLow, custom (price range min and max)
     * @param int $priceMin (optional if priceFilter is not custom)
     * @param int $priceMax (optional if priceFilter is not custom)
     * @return array
     */
    public static function SortAndFilterProductByCategoryName($categoryName, $ordersort, $pricefilter, $min, $max) {
        $products = Product::GetProductsByCategoryName($categoryName);
        if ($ordersort == 'alphabetical') {
            usort($products, function($a, $b) {
                return strcmp($a['productName'], $b['productName']);
            });
        } elseif ($ordersort == 'reverse') {
            usort($products, function($a, $b) {
                return strcmp($b['productName'], $a['productName']);
            });
        } elseif ($ordersort == 'chronological') {
            usort($products, function($a, $b) {
                return $a['productId'] - $b['productId'];
            });
        }

        if ($pricefilter == 'lowToHigh') {
            usort($products, function($a, $b) {
                return $a['productPrice'] - $b['productPrice'];
            });
        } elseif ($pricefilter == 'highToLow') {
            usort($products, function($a, $b) {
                return $b['productPrice'] - $a['productPrice'];
            });
        } elseif ($pricefilter == 'custom') {
            $products = array_filter($products, function($product) use ($min, $max) {
                return $product['productPrice'] >= $min && $product['productPrice'] <= $max;
            });
        }

        return $products;
    }

    public static function SortAndFilterProducts($ordersort, $pricefilter, $min, $max) {
        // print arguments
        $prods = Self::GetProducts();
        if ($ordersort == 'alphabetical') {
            usort($prods, function($a, $b) {
                return strcmp($a['productName'], $b['productName']);
            });
        } elseif ($ordersort == 'reverse') {
            usort($prods, function($a, $b) {
                return strcmp($b['productName'], $a['productName']);
            });
        } elseif ($ordersort == 'chronological') {
            usort($prods, function($a, $b) {
                return $a['productId'] - $b['productId'];
            });
        }

        if ($pricefilter == 'lowToHigh') {
            usort($prods, function($a, $b) {
                return $a['productPrice'] - $b['productPrice'];
            });
        } elseif ($pricefilter == 'highToLow') {
            usort($prods, function($a, $b) {
                return $b['productPrice'] - $a['productPrice'];
            });
        } elseif ($pricefilter == 'custom') {
            $prods = array_filter($prods, function($product) use ($min, $max) {
                return $product['productPrice'] >= $min && $product['productPrice'] <= $max;
            });
        }

        return $prods;
    }


    /**
     * Returns list of products based on category in a certain order by a certain price filter
     * 
     * @param string $categoryIde
     * @param string $orderSort - alphabetical, reverse, chronological (by product id)
     * @param string $priceFilter - lowToHigh, highToLow, custom (price range min and max)
     * @param int $priceMin (optional if priceFilter is not custom)
     * @param int $priceMax (optional if priceFilter is not custom)
     * @return array
     */
    public static function SortAndFilterProductByCategoryId($categoryId, $ordersort, $pricefilter, $min, $max) {
        $products = Product::GetProductsByCategoryId($categoryId);
        if ($ordersort == 'alphabetical') {
            usort($products, function($a, $b) {
                return strcmp($a['productName'], $b['productName']);
            });
        } else if ($ordersort == 'reverse') {
            usort($products, function($a, $b) {
                return strcmp($b['productName'], $a['productName']);
            });
        } else if ($ordersort == 'chronological') {
            usort($products, function($a, $b) {
                return $a['productId'] - $b['productId'];
            });
        }

        if ($pricefilter == 'lowToHigh') {
            usort($products, function($a, $b) {
                return $a['productPrice'] - $b['productPrice'];
            });
        } else if ($pricefilter == 'highToLow') {
            usort($products, function($a, $b) {
                return $b['productPrice'] - $a['productPrice'];
            });
        } else if ($pricefilter == 'custom') {
            $products = array_filter($products, function($product) use ($min, $max) {
                return $product['productPrice'] >= $min && $product['productPrice'] <= $max;
            });
        }
        return $products;
    }

    /**
     * Returns list of random products
     * @param int $numProducts
     * @return array
     */
    public static function getRandomProducts($numProducts) {
        $products = Product::GetProducts();
        shuffle($products);
        return array_slice($products, 0, $numProducts);
    }

    /**
     * Returns list of random products per category
     * @param int $numProducts
     * @return array
     */
    public static function getRandomProductsPerCategory($numProducts) {
        $categories = Category::getCategories();
        $products = array();
        foreach ($categories as $category) {
            $products = array_merge($products, Product::getRandomProductsByCategoryId($category['categoryId'], $numProducts));
        }
        return $products;
    }

    /**
     * Returns list of random products by category id
     * @param int $categoryId
     * @param int $numProducts
     * @return array
     */
    public static function getRandomProductsByCategoryId($categoryId, $numProducts) {
        $products = Product::GetProductsByCategoryId($categoryId);
        shuffle($products);
        return array_slice($products, 0, $numProducts);
    }

    public function DisplayAddToCartButton($marginLeft = 0) {
        $data = [
            "pid" => $this->productId,
            "pname" => urlencode($this->productName),
            "price" => $this->productPrice,
            "quantity" => 1
        ];
        return "<a onclick='cartAjax(".json_encode($data).", \"add\")' class='btn btn-primary btn-sm preview-toggle' style='margin: 0px; box-shadow: 0px 0px 6px #0d6efd; border: none;margin-left:".$marginLeft."px'>Add To Cart
        </a>";
    }

    public function GetProductImage($rounded = null) {
        $image = null;
        if ($rounded) {
            $rounded = "rounded";
        } else {
            $rounded = "";
        }
        $image = $this->ProductImageLink();
        return '<img class="img-fluid '.$rounded.'" src="'.$image.'" alt="'.$this->productName.'">';
    }

    public function ProductImageLink() {
        $image = null;
        if ($this->productImage == null) {
            if ($this->productImageURL == null) {
                $image = "https://cdn.bootstrapstudio.io/placeholders/1400x800.png";
            } else {
                $image = $this->productImageURL;
            }
        } else {
            $image = $this->productImage;
        }
        return $image;
    }

    public static function ImageLink($productId) {
        $product = Product::GetProduct($productId);
        return $product->ProductImageLink();
    }

    public static function AddProduct($data = array()) {
        $res = [
            "status" => false,
            "message" => "Product not added"
        ];
        if (isset($data['productName']) && isset($data['productDesc']) && isset($data['productImage']) && isset($data['productImageURL']) && isset($data['productCategoryId'])) {
            $productName = $data['productName'];
            $productPrice = $data['productPrice'];
            $productDesc = $data['productDesc'];
            $productImage = $data['productImage'];
            $productImageURL = $data['productImageURL'];
            $productCategoryId = $data['productCategoryId'];
            $sql = "INSERT INTO products (productName, productPrice, productDesc, productImage, productImageURL, productCategoryId) VALUES (?, ?, ?, ?, ?, ?)";
            $db = new DB();
            $pstmt = sqlsrv_prepare($db->conn, $sql, array($productName, $productPrice, $productDesc, $productImage, $productImageURL, $productCategoryId));
            if (sqlsrv_execute($pstmt)) {
                $res['status'] = true;
                $res['message'] = "Product added";
            }
        } else {
            $res['message'] = "Missing data";
        }
        return $res;
    }

}