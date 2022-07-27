<?php

    if (isset($_GET['logout'])) {
        if($_GET['logout'] == "true"){
            session_start();
            unset($_SESSION['userId']);
            unset($_SESSION['userNameId']);
            session_destroy();
            header("location: index.php");

         }
    }