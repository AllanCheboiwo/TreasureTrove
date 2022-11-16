<?php
    if (isset($_REQUEST['search'])) {
        header('Location: /product.php?pid='.$_REQUEST['search']);
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimal-ui">
<!-- <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, minimal-ui" /> -->
    <title><?php echo $page_name; ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css">
    <link rel="stylesheet" href="https://cdn.reflowhq.com/v2/toolkit.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.4.8/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/css/Navbar-Right-Links-icons.css?h=befd8a398792e305b7ffd4a176b5b585">
    <link rel="stylesheet" href="/assets/css/Simple-Slider-Simple-Slider.css?h=da830b6503e0457b5735b0129f20b163">
    <link rel="stylesheet" href="/assets/css/styles.css?h=250c50b43b53824e1f084ffe5c8c0342">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="/assets/js/index.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            const updateQuantity = (qtyData) => {
                cartAjax(qtyData, "update");
            }
        });
    </script>
</head>

<body style="height: 100%;">
    <header style="position: sticky;top: 0;left: 0;z-index: 2;background: #1cc4ab;">
        <!-- Start: Navbar Right Links -->
        <nav class="navbar navbar-dark navbar-expand-md sticky-top py-3">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="/index.php">
                    <span class="bs-icon-sm bs-icon-rounded bs-icon-primary d-flex justify-content-center align-items-center me-2 bs-icon" style="background: #169884;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-bezier">
                            <path fill-rule="evenodd" d="M0 10.5A1.5 1.5 0 0 1 1.5 9h1A1.5 1.5 0 0 1 4 10.5v1A1.5 1.5 0 0 1 2.5 13h-1A1.5 1.5 0 0 1 0 11.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm10.5.5A1.5 1.5 0 0 1 13.5 9h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zM6 4.5A1.5 1.5 0 0 1 7.5 3h1A1.5 1.5 0 0 1 10 4.5v1A1.5 1.5 0 0 1 8.5 7h-1A1.5 1.5 0 0 1 6 5.5v-1zM7.5 4a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z"></path>
                            <path d="M6 4.5H1.866a1 1 0 1 0 0 1h2.668A6.517 6.517 0 0 0 1.814 9H2.5c.123 0 .244.015.358.043a5.517 5.517 0 0 1 3.185-3.185A1.503 1.503 0 0 1 6 5.5v-1zm3.957 1.358A1.5 1.5 0 0 0 10 5.5v-1h4.134a1 1 0 1 1 0 1h-2.668a6.517 6.517 0 0 1 2.72 3.5H13.5c-.123 0-.243.015-.358.043a5.517 5.517 0 0 0-3.185-3.185z"></path>
                        </svg>
                    </span>
                    <span style="color: rgb(255,255,255);">Treasure Trove</span>
                </a>
                <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-2">
                    <span class="visually-hidden">Toggle navigation</span>
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse d-xxl-flex align-items-xxl-center collapse" id="navcol-2">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/products" style="">Products</a></li>
                        <li class="nav-item">
                            <a id="cartNum" class="nav-link" href="/cart" style="">
                                Cart
                                <?php
                                    if (isset($_SESSION['cart'])) {
                                        $count = count($_SESSION['cart']);
                                        echo "<span id=\"cart_count\" class=\"text-warning\">$count</span>";
                                    } else {
                                        echo "<span id=\"cart_count\" class=\"text-warning\">0</span>";
                                    }
                                ?>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/orders" style="">Orders</a></li>
                    </ul>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button" style="padding: 6px 12px;background: #169884;border-style: none; box-shadow: 0px 0px 6px #169884"><?php echo $_SESSION['loggedIn'] ? $_SESSION['userId'] : 'Guest'?>&nbsp;</button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <?php
                                if ($_SESSION['loggedIn']) {
                                    echo '
                                        <a class="dropdown-item" href="/account">Account</a>
                                        <a class="dropdown-item" href="/orders">Orders</a>
                                        <a class="dropdown-item" href="/settings">Settings</a>
                                        <a class="dropdown-item" href="?logout">Logout</a>
                                    ';
                                } else {
                                    echo '
                                        <a class="dropdown-item" href="/login">Login</a>
                                        <a class="dropdown-item" href="/register">Register</a>
                                    ';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav><!-- End: Navbar Right Links -->
    </header>
    <main style="position: relative;z-index: 1;background: #faf3dd;min-height:100vh">
    <section class="d-flex justify-content-center align-items-center" style="background: #169884;padding: 6px;height: 50px;">
        <div class="dropdown" style="display: inline-block;width: 120px;"><button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button" style="width: 115px;padding: 6px;background: #1cc4ab;border-style: none;">Categories&nbsp;</button>
            <div class="dropdown-menu dropdown-menu-start">
                <?
                    $category = Category::getCategories();
                    if ($category != null) {
                        foreach ($category as $cat) {
                            $catName = str_replace('/', '-', $cat['categoryName']);
                            echo '<a class="dropdown-item" href="/category/' . $catName . '">' . $cat['categoryName'] . '</a>';
                        }
                    } else {
                        echo '<a class="dropdown-item" href="#">No categories</a>';
                    }
                ?>
            </div>
        </div>
        <form class="d-flex justify-content-center align-items-center align-items-xl-center" style="width: calc(100% - 120px);display: inline-block;height: 45px;" autocomplete="off" target="_self">
            <div class="input-group autocomplete" style="display: inline-flex;
    background: transparent;
    border: none;
    padding: 0px;">
                <input class="form-control" type="search" name="search" placeholder="Search Products" style="border-width: 0px;border-style: none;box-shadow: 0px 0px 2px var(--bs-link-hover-color); border-radius: 4px 0px 0px 4px">
                <button class="btn btn-primary d-flex align-items-center align-content-center" type="submit" style="background: #1cc4ab;border-top-right-radius: 4px;border-bottom-right-radius: 4px;border-width: 0px; outline: none">
                    <i class="material-icons d-xl-flex align-items-xl-center" style="border-style: none;">search</i>
                </button>
            </div>
            </div>
        </form>
    </section>
    <script type="text/javascript">
        $(document).ready(function() {
            // get json result [value, id] from /include/api/search?term=productName and convert result to a link where href="/product?pid=id"
            // create a dropdown with the results from the search on the div's ::after
            // when a result is clicked, redirect to the product page
            // when the search bar is clicked, show the dropdown
            // when the search bar is not clicked, hide the dropdown
        
            $('input[name="search"]').on('keyup', function() {
                var term = $(this).val();
                if (term.length > 0) {
                    $.ajax({
                        url: '/include/api/search.php?term=' + term,
                        type: 'GET',
                        success: function(data) {
                            var autocompleteItems = $("<div></div>").addClass("autocomplete-items");
                            var result = JSON.parse(data);
                            var html = '';
                            console.log(result)
                            for (var i = 0; i < result.length; i++) {
                                html += '<div><a class="dropdown-item" href="/product.php?pid=' + result[i].id + '">' + result[i].value + '</a></div>';
                            }
                            autocompleteItems.html(html);
                            // append autocompleteitems to the autocomplete div if the autocomplete div does not have a child called autocomplete-items else replace the child with the new autocomplete-items
                            if ($('.autocomplete').children('.autocomplete-items').length == 0) {
                                $('.autocomplete').append(autocompleteItems);
                            } else {
                                $('.autocomplete').children('.autocomplete-items').replaceWith(autocompleteItems);
                            }
                            $('.autocomplete').show();
                        }
                    });
                } else {
                    $('.autocomplete-items').hide();
                }
            });
            let cartItems = $('.cart-item');
            // console.log(cartItems);
            // for loop
            cartItems.each((item) => {
                // console.log(cartItems[item])
                cartItems[item].addEventListener('click', (e) => {
                    // console.log(this)
                    e.preventDefault();
                    cartItemClicked(e, cartItems[item]);
                })
            })
            const cartItemClicked = (e, el) => {
                var productId = $(el).attr('data-product-id');
                // var orderId = $(el).attr('data-order-id');
                data = {
                    "pid": productId
                };
                // console.log(data);
                ajaxRequest('/include/api/cart.php?action=remove', data, 'POST',function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    showSnackbar(response['message']);
                    if(response.status) {
                        // remove parent card
                        // el.parent().parent().parent().remove();
                        $("#cartNum").html("Cart <span id=\"cart_count\" class=\"text-warning\">0</span><?php echo count($_SESSION['cart']);?></span>");
                        window.location.reload();
                    }
                });
            }
        });
    </script>