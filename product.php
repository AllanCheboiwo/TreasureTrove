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
                        <button id="addReview" class="btn btn-primary" type="button" style="background: #1cc4ab;border-style: none;box-shadow: 0px 0px 6px #169884;">Add a review</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <section style="padding: 10px;">
                <h3 style="padding: 8px;background: #1cc4ab;border-style: none;border-radius: 8px;box-shadow: 0px 0px 3px #169884;color: #364652;margin: 0px;">Reviews (<?php echo count(Review::GetReviewsByProductId($product->productId)); ?>)</h3>
            </section>
            <div class="row g-0 row-cols-1" style="padding: 10px;">
                <div class="col" id="newReview" style="margin: 10px 0;">
                    <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;">
                        <div class="card-body">
                            <h4 class="card-title">Write your review</h4>
                            <h6 class="text-muted d-flex align-items-center align-content-center card-subtitle mb-2">
                                Rating: <span id="revStars" class="d-flex justify-content-center align-items-center align-content-center">
                                    <i class="material-icons">star</i>
                                    <i class="material-icons">star</i>
                                    <i class="material-icons">star</i>
                                    <i class="material-icons">star</i>
                                    <i class="material-icons">star</i>
                                </span>
                            </h6>
                            <form>
                                <textarea class="form-control" name="reviewComment" placeholder="Tell us what you thought about this product..." rows="4" autocomplete="off" spellcheck="true" style="background: #eaeaea;border-style: none;box-shadow: inset 0px 0px 3px #cccccc;"></textarea>
                                <button class="btn btn-primary" id="postReview" type="button" style="margin-top: 8px;padding: 4px 8px;background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">Post Review</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                    $reviews = Review::GetReviewsByProductId($product->productId);
                    if(empty($reviews)){
                        echo '<h5 class="text-muted card-subtitle mb-2">No reviews yet</h5>';
                    } else {
                        foreach($reviews as $review){
                            echo Review::DisplayReview($review['reviewId']);
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        let reviewStars = $('#revStars');
        let reviewStarsIcons = reviewStars.find('i');
        reviewStarsIcons.on('click', function(){
            let starIndex = $(this).index();
            reviewStarsIcons.each(function(index){
                if(index <= starIndex){
                    $(this).addClass('rated');
                } else {
                    $(this).removeClass('rated');
                }
            });
        });
        $('#newReview').hide();
        $('#postReview').on('click', function(){
            let reviewComment = $(this).parent().find('textarea[name="reviewComment"]').val();
            let reviewRating = reviewStars.find('i.rated').length;
            let productId = <?php echo $product->productId; ?>;
            if(reviewComment.length > 0){
                $.ajax({
                    url: '/include/api/product.php',
                    type: 'POST',
                    data: {
                        action: 'addReview',
                        comment: reviewComment,
                        rating: reviewRating,
                        pid: productId
                    },
                    success: function(response){
                        console.log(response);
                        response = JSON.parse(response);
                        showSnackbar(response.message);
                        if(response.status){
                            window.location.reload();
                            // reviewStars.each(function(index){
                            //     $(this).removeClass('rated');
                            // });
                            reviewStarsIcons.each(function(index){
                                $(this).removeClass('rated');
                            });
                            $('#newReview').slideToggleUp();
                        }
                    }
                });
            } else {
                alert('Please enter a review comment');
            }
        });
        $('#addReview').on('click', function(){
            $('#newReview').slideToggle();
        });
        let deleteReviewButtons = $('.delete-review');
        deleteReviewButtons.on('click', function(){
            let reviewId = $(this).data('review-id');
            $.ajax({
                url: '/include/api/product.php',
                type: 'POST',
                data: {
                    action: 'deleteReview',
                    rid: reviewId
                },
                success: function(response){
                    console.log(response);
                    response = JSON.parse(response);
                    showSnackbar(response.message);
                    if(response.status){
                        // window.location.reload();
                        $('#review-' + reviewId).slideToggleUp();
                    }
                }
            });
        });
        let editReviewButtons = $('.edit-review');
        editReviewButtons.on('click', function(){
            let reviewId = $(this).data('review-id');
            let reviewComment = $('#review-' + reviewId).find('.card-text').text();
            let mode = "edit";
            let reviewRating = $('#review-' + reviewId).find('.card-subtitle').data('rating');
            $('#review-' + reviewId).find('.card-text').html('<textarea class="form-control" name="reviewComment" placeholder="Tell us what you thought about this product..." rows="4" autocomplete="off" spellcheck="true" style="background: #eaeaea;border-style: none;box-shadow: inset 0px 0px 3px #cccccc;">' + reviewComment + '</textarea>');
            $('#review-' + reviewId).find('.card-subtitle').html('Rating: <span id="revStars" class="d-flex align-items-center align-content-center"></span>');
            // toggle readonly on textarea if we're edding
            if(mode == "edit"){
                $('#review-' + reviewId).find('textarea[name="reviewComment"]').attr('readonly', false);
            } else {
                $('#review-' + reviewId).find('textarea[name="reviewComment"]').attr('readonly', true);
            }
            let reviewStars = $('#review-' + reviewId).find('#revStars');
            for(let i = 0; i < 5; i++){
                if(i < reviewRating){
                    reviewStars.append('<i class="material-icons rated">star</i>');
                } else {
                    reviewStars.append('<i class="material-icons">star</i>');
                }
            }
            let reviewStarsIcons = reviewStars.find('i');
            reviewStarsIcons.on('click', function(){
                let starIndex = $(this).index();
                reviewStarsIcons.each(function(index){
                    if(index <= starIndex){
                        $(this).addClass('rated');
                    } else {
                        $(this).removeClass('rated');
                    }
                });
            });
            // add updateReview button if not exists
            if($('#review-' + reviewId).find('#updateReview').length == 0){
                $('#review-' + reviewId).find('.card-body').append('<button class="btn btn-primary btn-sm" id="updateReview" type="button" style="margin-top: 8px;padding: 4px 8px;background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;margin-left:8px">Update</button>');
                $('#review-' + reviewId).find('#updateReview').on('click', function(){
                    let reviewComment = $(this).parent().find('textarea[name="reviewComment"]').val();
                    let reviewRating = reviewStars.find('i.rated').length;
                    let productId = <?php echo $product->productId; ?>;
                    if(reviewComment.length > 0){
                        $.ajax({
                            url: '/include/api/product.php',
                            type: 'POST',
                            data: {
                                action: 'updateReview',
                                comment: reviewComment,
                                rating: reviewRating,
                                rid: reviewId
                            },
                            success: function(response){
                                console.log(response);
                                response = JSON.parse(response);
                                showSnackbar(response.message);
                                if(response.status){
                                    window.location.reload();
                                    // reviewStars.each(function(index){
                                    //     $(this).removeClass('rated');
                                    // });
                                    reviewStarsIcons.each(function(index){
                                        $(this).removeClass('rated');
                                    });
                                    $('#newReview').slideToggleUp();
                                }
                            }
                        });
                    } else {
                        alert('Please enter a review comment');
                    }
                    return false;
                });
            }
            
            // dont add cancel if a canel already exists
            if($('#review-' + reviewId).find('#cancelReview').length == 0){
                $('#review-' + reviewId).find('.card-body').append('<button class="btn btn-primary btn-sm" id="cancelReview" type="button" style="margin-top: 8px; margin-left: 8px; padding: 4px 8px;background: #1cc4ab;border-style: none;box-shadow: 0px 0px 3px #169884;">Cancel</button>');
                $('#review-' + reviewId).find('#cancelReview').on('click', () => {
                    window.location.reload();
                });
            }
        });
    });
</script>
<?php
    require "include/footer.php";
?>