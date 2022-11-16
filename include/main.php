<?php
    require('include/db_credentials.php');
	
    if (isset($_REQUEST['logout'])){
        AUTH::Logout();
    }
?>