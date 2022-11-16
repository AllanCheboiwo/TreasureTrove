<?php
    require 'include/main.php';
    $page_name = "Account - Treasure Trove";
    $readonly = "readonly";
    $mode = null;
    $address = null;
    $res;
    $tab = null;
    $tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : null;
    $customer = new Customer($_SESSION['userId']);
    if ($customer) {
        $address = $customer->GetAddress();
    } else {
        header('Location: /login.php');
    }
    if (isset($_REQUEST['mode'])) {
        $mode = $_REQUEST['mode'];
        if ($mode === "edit") {
            $readonly = null;
        } else {
            $readonly = "readonly";
        }
    }

    if ($res) {
        if ($res['status'] === true) {
            $mode = null;
        } else {
            // header("Refresh: 0");
        }
        // echo "<script>alert('".$res['message']."');</script>";
    }
    
    require "include/header.php";
?>
<!-- <section>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#"><span>Home</span></a></li>
        <li class="breadcrumb-item"><a href="#"><span>Library</span></a></li>
        <li class="breadcrumb-item"><a href="#"><span>Data</span></a></li>
    </ol>
</section> -->
<!-- Start: 2 Rows 1+2 Columns -->
<div class="container">
    <br/>
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 16px 0;">
                <div class="card-body" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                    <h4 class="card-title" style="color: #1cc4ab;">Your Account</h4>
                    <h6 class="text-muted card-subtitle mb-2"></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-0">
        <ul class="nav nav-pills flex-grow-0 align-content-start row col-md-3 flex-md-3" role="tablist" style="background: #364652; padding: 16px 8px 16px 0px; border-style: none; margin: 0px; border-radius: 8px; box-shadow: 0 0 6px #364652; height: fit-content;">
            <li class="nav-item" role="presentation" style="margin: 4px;">
                <a class="nav-link active" role="tab" data-bs-toggle="pill" href="#details">Details</a>
            </li>
            <!-- <li class="nav-item" role="presentation" style="margin: 4px;"><a class="nav-link" role="tab" data-bs-toggle="pill" href="#tab-2">Orders</a></li> -->
            <li class="nav-item" role="presentation" style="margin: 4px;">
                <a class="nav-link" role="tab" data-bs-toggle="pill" href="#address">Address</a>
            </li>
            <li class="nav-item" role="presentation" style="margin: 4px;">
                <a class="nav-link" role="tab" data-bs-toggle="pill" href="#payment">Payment</a>
            </li>
            <li class="nav-item" role="presentation" style="margin: 4px;">
                <a class="nav-link" role="tab" data-bs-toggle="pill" href="#security">Security</a>
            </li>
        </ul>
        <div class="tab-content col-md-9 flex-md-9" style="padding: 10px 20px">
            <div class="tab-pane active" role="tabpanel" id="details">
                <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 0;">
                    <div class="card-body align-content-center" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                        <h5 class="card-title" style="color: #1cc4ab;margin:0px">Details</h5>
                    </div>
                </div>
                <section style="margin: 8px 0px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 6px #fafafa;">
                        <div class="card-body">
                            <h4 class="card-title">Personal Information</h4>
                            <h6 class="text-muted card-subtitle mb-2">Update your personal information</h6>
                            <a class="btn btn-primary" role="button" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;" id="editPersonal">Edit Personal Information</a>
                            <form method="post" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <input disabled value="<?php echo $customer->firstName; ?>" class="form-control" type="text" name="firstName" placeholder="Firstname" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <input disabled value="<?php echo $customer->lastName; ?>" class="form-control" type="text" name="lastName" placeholder="Lastname" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <input disabled value="<?php echo $customer->phonenum; ?>" class="form-control" type="tel" name="phoneNum" placeholder="Phone" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" inputmode="tel">
                                <button class="btn btn-primary" type="submit" name="updatePersonal" id="updatePersonal" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
                <section style="margin: 8px 0px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 6px #fafafa;">
                        <div class="card-body">
                            <h4 class="card-title">Email</h4>
                            <h6 class="text-muted card-subtitle mb-2">Update your email</h6>
                            <a class="btn btn-primary" role="button" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;" id="editEmail">Edit Email</a>
                            <form method="post" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <input disabled value="<?php echo $customer->email; ?>" class="form-control" type="email" name="email" placeholder="Email" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" inputmode="email">
                                <input disabled value="<?php echo ''; ?>" autocomplete="off" class="form-control" type="email" name="email2" placeholder="Re-enter Email" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" inputmode="email">
                                <input disabled value="<?php echo ''; ?>" class="form-control" type="password" name="passwordEmail" placeholder="Password" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <button class="btn btn-primary" type="submit" name="updateEmail" id="updateEmail" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
            <!-- <div class="tab-pane" role="tabpanel" id="tab-2">
                <ul class="list-group" style="border-style: none;">
                    <li class="list-group-item" style="border-style: none;border-radius: 6px;margin: 4px 0px;">
                        <div class="card" style="border-style: none;box-shadow: 0px 0px 6px #ffffff;">
                            <div class="card-body">
                                <h4 class="card-title">OrderName</h4>
                                <h6 class="text-muted card-subtitle mb-2">orderDate</h6>
                                <p class="card-text">Order Description</p><button class="btn btn-primary" type="button" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 6px #0d6efd;">View Order</button>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item" style="border-style: none;border-radius: 6px;margin: 4px 0px;"><span>List Group Item 2</span></li>
                    <li class="list-group-item" style="border-style: none;border-radius: 6px;margin: 4px 0px;"><span>List Group Item 3</span></li>
                </ul>
            </div> -->
            <div class="tab-pane" role="tabpanel" id="address">
                <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 0;">
                    <div class="card-body align-content-center" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                        <h5 class="card-title" style="color: #1cc4ab;margin:0px">Address</h5>
                    </div>
                </div>
                <section style="margin: 8px 0px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 6px #fafafa;">
                        <div class="card-body">
                            <h4 class="card-title">Shipping Address</h4>
                            <h6 class="text-muted card-subtitle mb-2">This address will be used for shipping</h6>
                            <a class="btn btn-primary" role="button" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;" id="editShipping">Edit Shipping Address</a>
                            <form method="post" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <input disabled value="<?php echo $address['address']; ?>" class="form-control" type="text" name="address" placeholder="Street Address" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <input disabled value="<?php echo $address['UAPN']; ?>" class="form-control" type="text" name="UAFN" placeholder="Apt/Unit/Flat Number" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <div class="row">
                                    <div class="col">
                                        <input disabled value="<?php echo $address['city']; ?>" class="form-control" type="text" name="city" placeholder="City" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                    </div>
                                    <div class="col">
                                        <input disabled value="<?php echo $address['state']; ?>" class="form-control" type="text" name="state" placeholder="State" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <input disabled value="<?php echo $address['country']; ?>" class="form-control" type="text" name="country" placeholder="Country" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                    </div>
                                    <div class="col">
                                        <input disabled value="<?php echo $address['postalCode']; ?>" class="form-control" type="text" name="postalCode" placeholder="Postal Code" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" name="updateShipping" id="updateShipping" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;">
                                    Save Shipping Address
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
                <section style="margin: 8px 0px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 6px #fafafa;">
                        <div class="card-body">
                            <h4 class="card-title">Billing Address</h4>
                            <h6 class="text-muted card-subtitle mb-2">This address will be used for billing</h6>
                            <a class="btn btn-primary" role="button" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;" id="editBilling">Edit Billing Address</a>
                            <form method="post" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <input disabled value="<?php echo $address['address']; ?>" class="form-control" type="text" name="billaddress" placeholder="Street Address" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <input disabled value="<?php echo $address['UAPN']; ?>" class="form-control" type="text" name="billUAFN" placeholder="Apt/Unit/Flat Number" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <div class="row">
                                    <div class="col">
                                        <input disabled value="<?php echo $address['city']; ?>" class="form-control" type="text" name="billcity" placeholder="City" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                    </div>
                                    <div class="col">
                                        <input disabled value="<?php echo $address['state']; ?>" class="form-control" type="text" name="billstate" placeholder="State" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <input disabled value="<?php echo $address['country']; ?>" class="form-control" type="text" name="billcountry" placeholder="Country" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                    </div>
                                    <div class="col">
                                        <input disabled value="<?php echo $address['postalCode']; ?>" class="form-control" type="text" name="billpostalCode" placeholder="Postal Code" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" name="updateBilling" id="updateBilling" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;">
                                    Save Billing Address
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
            <div class="tab-pane active" role="tabpanel" id="payment">
                <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 0;">
                    <div class="card-body align-content-center" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                        <h5 class="card-title" style="color: #1cc4ab;">Payment</h5>
                        <a style='text-decoration: none' id="addPaymentMethod" href="#"><h6 class="text-muted card-subtitle mb-2">Add a Payment Method</h6></a>
                    </div>
                </div>
                <?php
                    $methods = $customer->GetPaymentMethods();
                    foreach($methods as $method) {
                        echo '
                        <section style="margin: 8px 0px;">
                            <div class="card" style="border-style: none;box-shadow: 0px 0px 3px #cccccc;">
                                <div class="card-body">
                                    <h4 class="card-title" style="color: #364652;">Payment Method</h4>
                                    <h6 class="text-muted card-subtitle mb-2">Subtitle</h6>
                                    <form method="post" data-id='.$method["paymentMethodId"].' enctype="application/x-www-form-urlencoded" action="'.$_SERVER['PHP_SELF'].'">
                                        <input class="form-control" type="text" name="cardName" placeholder="Card Number" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" readonly value="'.$customer->FullName().'" />
                                        <div class="row g-0">
                                            <div class="col-4 col-sm-2 col-md-3 col-lg-2 col-xxl-2 d-inline-flex justify-content-center align-items-center align-content-center">
                                                <select class="form-select" name="cardType" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">
                                                    <option value="visa" '.($method["paymentType"] === "visa" ? "selected" : "").'>Visa</option>
                                                    <option value="mastercard" '.($method["paymentType"] === "mastercard" ? "selected" : "").'>Master Card</option>
                                                    <option value="express" '.($method["paymentType"] === "express" ? "selected" : "").'>Express</option>
                                                    <option value="other" '.($method["paymentType"] === "other" ? "selected" : "").'>Other</option>
                                                </select>
                                            </div>
                                            <div class="col"><input class="form-control" type="text" name="cardNumber" placeholder="Card Number" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" readonly inputmode="numeric" maxlength=16 value="'.(str_repeat('*', strlen($method["paymentNumber"]) - 4) . substr($method["paymentNumber"], -4)).'"/></div>
                                        </div>
                                        <div class="row g-0">
                                            <div class="col-sm-5 col-lg-4 col-xl-3 col-xxl-3 d-inline-flex justify-content-center align-items-center align-content-center">
                                                <select class="form-select" name="cardType" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">';
                                                        $ncount = 0;
                                                        // count to 12
                                                        for ($i = 0; $i < 12; $i++) {
                                                            $num = $i + 1;
                                                            echo '<option value="'.$num.'" '.(explode("-", $method["paymentExpiryDate"])[1] === $num ? "selected" : "").'>'.$num.'</option>';
                                                        }
                                            echo '</select>
                                                <select class="form-select" name="cardType" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">';
                                                        $currentYear = date("Y");
                                                        $count = 0;
                                                        for ($i = 0; $i < 20; $i++) {
                                                            $year = (intval($currentYear)+$i);
                                                            echo '<option value="'.$year.'" '.(explode("-", $method["paymentExpiryDate"])[0] === $year ? "selected" : "").'>'.$year.'</option>';
                                                        }
                                            echo '</select>
                                            </div>
                                            <div class="col">
                                                <input class="form-control" type="text" name="cardCVV" maxlength=3 placeholder="Card CVV" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" readonly />
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="button" data-pm-id="'.$method['paymentMethodId'].'" onclick="deletePM(event);" style="background: #fb565a;border-style: none;box-shadow: 0px 0px 3px #ea4040;margin-top: 8px;">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </section>
                        ';
                    }
                ?>
            </div>
            <div class="tab-pane" role="tabpanel" id="security">
                <div class="card" style="box-shadow: 0px 0px 6px #cccccc;border-style: none;border-radius: 8px;margin: 0;">
                    <div class="card-body align-content-center" style="background: #364652;border-radius: 8px;box-shadow: 0px 0px 3px #364652;">
                        <h5 class="card-title" style="color: #1cc4ab;margin:0px">Security</h5>
                    </div>
                </div>
                <section style="margin: 8px 0px;">
                    <div class="card" style="border-style: none;box-shadow: 0px 0px 6px #fafafa;">
                        <div class="card-body">
                            <h4 class="card-title">Password</h4>
                            <h6 class="text-muted card-subtitle mb-2">Change your password</h6>
                            <a class="btn btn-primary" role="button" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;" id="editPassword">Change Password</a>
                            <form method="post" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <input disabled value="<?php echo ''; ?>" autocomplete="off" class="form-control" type="password" name="passwordOld" placeholder="Old Password" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <input disabled value="<?php echo ''; ?>" autocomplete="off"  class="form-control" type="password" name="password" placeholder="New Password" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <input disabled value="<?php echo ''; ?>" autocomplete="off"  class="form-control" type="password" name="password2" placeholder="Re-enter New Password" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;">
                                <button class="btn btn-primary" type="submit" name="updatePassword" id="updatePassword" style="background: rgb(13, 110, 253);border-style: none;box-shadow: 0px 0px 3px #0d6efd;margin: 8px 0px;">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
                <div class="card" style="border-style: none;box-shadow: 0px 0px 6px #fafafa;">
                    <div class="card-body" style="background: #ffffff;box-shadow: 0px 0px 6px #ffffff;">
                        <h4 class="card-title">Delete Your Account</h4>
                        <h6 class="text-muted card-subtitle mb-2">Sad to see you go :(</h6>
                        <p class="card-text">Completely delete your account history from our system</p>
                        <button class="btn btn-primary" role="button" id="deleteAccount" name="deleteAccount" style="background: #ea4040;border-style: none;box-shadow: 0px 0px 6px #ea4040;">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- End: 2 Rows 1+2 Columns -->
<div id="newPaymentMethod" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Payment Method</h4><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card" style="border-style: none;box-shadow: 0px 0px 3px #cccccc;">
                    <div class="card-body">
                        <h4 class="card-title" style="color: #364652;">Payment Method</h4>
                        <h6 class="text-muted card-subtitle mb-2">Subtitle</h6>
                        <form id="npmForm" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo $_SERVER['PHP_SELF'];?>">
                            <input class="form-control" type="text" name="newcardName" placeholder="Card Name" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" value="<?php echo $customer->FullName(); ?>"/>
                            <div class="row g-0">
                                <div class="col-4 col-sm-2 col-md-3 col-lg-2 col-xxl-2 d-inline-flex justify-content-center align-items-center align-content-center">
                                    <select class="form-select" name="newcardType" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">
                                        <option value="visa" selected>Visa</option>
                                        <option value="mastercard">Master Card</option>
                                        <option value="express">Express</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col"><input class="form-control" type="text" name="newcardNumber" placeholder="Card Number" style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;" inputmode="numeric" maxlength=16/></div>
                            </div>
                            <div class="row g-0">
                                <div class="col-sm-5 col-lg-4 col-xl-3 col-xxl-3 d-inline-flex justify-content-center align-items-center align-content-center">
                                    <select class="form-select" name="newcardExpMonth" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">
                                            <?php $ncount = 0;
                                            // count to 12
                                            for ($i = 0; $i < 12; $i++) {
                                                $num = $i + 1;
                                                echo '<option value="'.$num.'">'.$num.'</option>';
                                            } ?>
                                    </select>
                                    <select class="form-select" name="newcardExpYear" style="border-style: none;box-shadow: 0px 0px 3px #169884;background: #1cc4ab;color: #364652;font-weight: bold;">
                                            <?php $currentYear = date("Y");
                                            $count = 0;
                                            for ($i = 0; $i < 20; $i++) {
                                                $year = (intval($currentYear)+$i);
                                                echo '<option value="'.$year.'">'.$year.'</option>';
                                            } ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <input class="form-control" type="text" name="newcardCVV" placeholder="Card CVV" maxlength=3 style="background: #fafafa;border-style: none;box-shadow: inset 0px 0px 3px #fafafa;margin: 8px 0px;"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button class="btn btn-light" id="closePaymentMethod" type="button" data-bs-dismiss="modal">Close</button><button class="btn btn-primary" id="donePaymentMethod" type="button">Save</button></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(() => {
        
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('lastTab', $(this).attr('href'));
        });
        var lastTab = localStorage.getItem('lastTab');

        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        }

        let ajaxUrl;
        ajaxUrl = '/include/api/account.php?action=';
        //Ajax deleteAccount

        $('#deleteAccount').click(function (e) {
            e.preventDefault();
            var data = {
                'deleteAccount': true
            };
            ajaxRequest(ajaxUrl + 'deleteAccount', data, 'POST', function (response) {
                response = JSON.parse(response);
                console.log(response);
                alert("Delete Account:\n\t"+response['message']);
                if (response.status) {
                    window.location.href = '/logout.php';
                }
            });
        });

        // Find all editBtns which are links that contain edit in its id
        // for each editBtn find all input fields in the <form> of its sibling editBtn
        // Find the editBtn's respective saveBtn
        // if edit mode is view, disable the respective saveBtn, else enable it
        // if edit mode is view, disable all input fields, else enable them
        // if edit mode is edit, string replace the Edit part of the editBtn's text to Cancel, change the mode to edit, else change the mode to view
        // on a saveBtn click, send an ajax request to the server to update the respective Data
        // request is successful if the response's status is true
        // create a custom alert box with jquery and set a title to the a split and uppercase of the first letter, joined by space of the action then set the body message of the alert to the response's message and trigger the custom alert
        // if the request is successful, change the edit mode to view
        // if the request is successful, disable the saveBtn
        // if the request is successful, disable all input fields
        // if the request is successful, string replace the Cancel part of the editBtn's text to Edit, change the mode to view, else change the mode to edit

        var editBtns = $('a[id*="edit"]');
        editBtns.each(function (index, editBtn) {
            var inputs = $(editBtn).siblings('form').find('input');
            var saveBtn = $(editBtn).siblings('form').find('button');
            ajaxUrl = '/include/api/account.php?action=';
            var mode = 'view';
            if (mode == 'view') {
                saveBtn.attr('disabled', true);
                inputs.attr('disabled', true);
            } else {
                saveBtn.attr('disabled', false);
                inputs.attr('disabled', false);
            }
            $(editBtn).click(function (e) {
                e.preventDefault();
                if (mode == 'view') {
                    saveBtn.attr('disabled', false);
                    inputs.attr('disabled', false);
                    $(editBtn).text($(editBtn).text().replace('Edit', 'Cancel'));
                    mode = 'edit';
                } else {
                    saveBtn.attr('disabled', true);
                    inputs.attr('disabled', true);
                    $(editBtn).text($(editBtn).text().replace('Cancel', 'Edit'));
                    mode = 'view';
                }
            });
            saveBtn.click(function (e) {
                e.preventDefault();
                var data = {};
                inputs.each(function (index, input) {
                    data[$(input).attr('name')] = $(input).val();
                });
                ajaxRequest(ajaxUrl + $(editBtn).attr('id'), data, 'POST', function (response) {
                    console.log(response);
                    response = JSON.parse(response);
                    var titleToast = ($(editBtn).attr('id').split('_').map((word) => word.charAt(0).toUpperCase() + word.slice(1)).join(' '));
                    // alert("" + titleToast + "\n\t" +response.message);
                    showSnackbar(response.message);
                    if (response.status) {
                        saveBtn.attr('disabled', true);
                        inputs.attr('disabled', true);
                        $(editBtn).text($(editBtn).text().replace('Cancel', 'Edit'));
                        mode = 'view';
                    }
                });
            });
        });
        let modal = $('#newPaymentMethod');
        const closeModal = () => {
            modal.css("display", "none !important")
            modal.hide();
        }
        // modal.hide();
        $('a[id=addPaymentMethod]').click((e) => {
            e.preventDefault();
            modal.css("display", "block")
            modal.show();
        })
        $("#closePaymentMethod").click((e) => {
            e.preventDefault();
            closeModal();
        })
        $("#donePaymentMethod").click((e) => {
            e.preventDefault();
            let form = $('#npmForm')[0];
            // console.log(form);
            let elements = form.elements;
            // console.log(elements);
            data = {}
            for (let i = 0; i < elements.length; i++) {
                console.log(elements[i]);
                data[elements[i].name] = elements[i].value;
            }
            console.log(data);
            ajaxUrl = '/include/api/account.php?action=';
            ajaxRequest(ajaxUrl + "addpm", data, 'POST', (resp) => {
                console.log(resp);
                resp = JSON.parse(resp);
                showSnackbar(resp.message);
                if (resp.status) {
                    form.reset();
                    closeModal();
                    window.location.reload();
                }
            })
        })
        // Find all paymentMethods
    });
</script>
<?php
    require "include/footer.php";
?>