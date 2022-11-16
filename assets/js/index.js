// Create A singular ajax request function
const ajaxRequest = (url, data, method, callback) => {
    data = $.param(data);
    $.ajax({
        url: url,
        data: data,
        method: method,
        success: function (response) {
            callback(response);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

const showSnackbar = (text) => {
    // Get the snackbar div
    let x = document.getElementById("snackbar");
    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = text;
    // After 3 seconds, remove the show class from DIV
    setTimeout(() => {
        x.className = x.className.replace("show", "");
    }, 3000);
}

const cartAjax = (data, action) => {
    console.log(data);
    // data = JSON.parse(data);
    // console.log(data);
    ajaxRequest('/include/api/cart.php?action='+action, data, 'POST', (response) => {
        console.log(response);
        response = JSON.parse(response);
        console.log(response);
        showSnackbar(response['message']);
        if (response.status) {
            window.location.reload();
            // window.location.href=window.location
        }
    })
}

const deletePM = (e) => {
    e.preventDefault();
    let data = {"pmId": e.target.getAttribute('data-pm-id')}
    let ajaxUrl = '/include/api/account.php?action=';
    ajaxRequest(ajaxUrl+"deletepm", data, 'POST', (res) => {
        console.log(res);
        res = JSON.parse(res);
        showSnackbar(res.message);
        if (res.status) {
            window.location.href=window.location.href
        }
    })
}