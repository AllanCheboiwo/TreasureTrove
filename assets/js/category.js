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
        let minPrice = $('input[name="minPrice"]').val();
        let maxPrice = $('input[name="maxPrice"]').val();
        // let category = '<?php echo $_REQUEST['category']; ?>';
        // let url = 'category.php?category='+category+'&sort='+sort+'&price='+price+'&minPrice='+minPrice+'&maxPrice='+maxPrice;
        let url = '/'+sort+'/'+price+'/'+minPrice+'-'+maxPrice;
        window.location.href = url;
    }

    $('button #updateSortFilter').click(function() {
        e.preventDefault();
        console.log('clicked');
        updateSortFilter();
    });
});