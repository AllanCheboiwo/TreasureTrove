<?php
    require 'include/main.php';
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
        header("Location: /index.php");
    }
    require 'include/header.php';
?>
<!-- Start: Register Form Basic -->
<section class="position-relative py-4 py-xl-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8 col-xl-6 text-center mx-auto">
                    <h2>Register</h2>
                    <p class="w-lg-50">Become a member today!</p>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card mb-5" style="background: #fb565a;box-shadow: 0px 0px 6px #fb565a;border-style: none;">
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="bs-icon-xl bs-icon-circle bs-icon-primary bs-icon my-4" style="background: #1cc4ab;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-person">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"></path>
                                </svg></div>
                            <form class="text-center" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <div class="mb-3"><input class="form-control" type="text" name="userId" placeholder="Username" autocomplete="off" required="true"></div>
                                <div class="mb-3"><input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off" inputmode="email" required=""></div>
                                <div class="mb-3"><input class="form-control" type="text" name="firstname" placeholder="Firstname" required="true"></div>
                                <div class="mb-3"><input class="form-control" type="text" name="lastname" placeholder="Lastname" required="true"></div>
                                <div class="mb-3"><input class="form-control" type="tel" name="phonenum" placeholder="Phone" inputmode="tel" required="true"></div>
                                <div class="mb-3"><input class="form-control" type="password" name="password" placeholder="Password" required="true"><span style="display:flex;color:#ffffff;font-size:12px">At least one of each: uppercase letter, lowercase letter, digit, and special character</span></div>
                                <div class="mb-3"><button class="btn btn-primary d-block w-100" type="submit" id="registerForm" style="background: #1cc4ab;border-style: none;">Register</button></div>
                                <!-- <p class="fw-semibold" style="color: #364652;"><a href="/forgot">Forgot your password?</a></p> -->
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card" style="background: #fb565a;border-style: none;box-shadow: 0px 0px 6px #fb565a;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: #364652;">Already have an account?</h4>
                            <h6 class="text-white card-subtitle mb-2">Log into your account!</h6><a class="btn btn-primary" role="button" href="/login.php" style="background: #169884;border-style: none;">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End: Register Form Basic -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#registerForm').click(function (e) {
            e.preventDefault();
            var data = {
                'userId': $("input[name=userId]").val(),
                'email': $("input[name=email]").val(),
                'password': $("input[name=password]").val(),
                'firstname': $("input[name=firstname]").val(),
                'lastname': $("input[name=lastname]").val(),
                'phonenum': $("input[name=phonenum]").val()
            };
            ajaxRequest("/include/api/auth.php?action=register", data, 'POST', function (response) {
                // console.log(response);
                response = JSON.parse(response);
                // console.log(response);
                // alert(response['message']);
                showSnackbar(response.message);
                if (response.status) {
                    window.location.href = '/login.php';
                }
            });
        });
    });
</script>
<?php
    require 'include/footer.php';
?>
