<?php
//connecting to the database
    $dbServer="localhost";
    $dbUsername="root";
    $dbPassword="";
    $dbName="toniastoredb";
    $conn= mysqli_connect($dbServer,$dbUsername,$dbPassword,$dbName);
    if (!$conn) {
        die("Database connection failed whith the following error: ".mysqli_connect_error());
            # code...
    }
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/config.php';
    require_once BASEURL.'helpers/helper.php';
    require_once BASEURL.'Paystack-php/src/autoload.php';

    $cart_data='';
    $cart_count='0';
    if(isset($_COOKIE[CART_COOKIE])){
        $cookie_data = stripslashes($_COOKIE[CART_COOKIE]);
        $cart_data = json_decode($cookie_data, true);
        $cart_count = count($cart_data);
       //var_dump($cart_data);
        //var_dump($cart_count);
    }
      
    $flash= '';
    $guest='';
    if(isset($_SESSION['userId'])) {
        $user_id = $_SESSION['userId'];
        $query=$conn->query("SELECT * FROM users WHERE users_id = '$user_id'");
        $user_data= mysqli_fetch_assoc($query);
        $fullName = explode(' ',  $user_data['full_name']);
        $user_data['first'] = $fullName[0];
        //$user_data['last'] = $fullName[1];
     }else{
         $_SESSION['guest'] = $guest;
         $guest = 'Guest';
     } 
    
    if(isset($_SESSION['success_flash'])){
        $flash= '<p id="flash" style="text-align:center; font-weight:bolder" class="alert alert-success alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['success_flash'].'</p>';
        unset($_SESSION['success_flash']);
    }
    if(isset($_SESSION['error_flash'])){
        $flash= '<p id="flash" style="text-align:center; font-weight:bolder" class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['error_flash'].'</p>';
        unset($_SESSION['error_flash']);
    }
    //to kw the number of users online
    $count_user = '';
    $session = session_id();
    $time = time();
    $time_check = $time-120;
    $sqlOnline = $conn->query("SELECT * FROM useronline WHERE sess = '$session'");
    $countUsers = mysqli_num_rows($sqlOnline);
    if($countUsers == "0"){
        $sqlOnline2 = "INSERT INTO useronline(`sess`,`onlinetime`) VALUES ('$session','$time')";
        $conn->query("$sqlOnline2");
    }else{
        $sqlOnline3 = $conn->query("UPDATE useronline SET `sess` = '$session',`onlinetime` = '$time' WHERE `sess` = '$session'");
    }
    $sqlOnline4 = $conn->query("SELECT * FROM useronline");
    $count_user = mysqli_num_rows($sqlOnline4);
    $sql5="DELETE FROM useronline WHERE onlinetime < $time_check";
    $result4= $conn->query($sql5);

    // to kw the number of user for one day....

    $count_userdate = '';
    $sessiondate = session_id();
    date_default_timezone_set('Africa/Lagos');
    $date = date("Y-m-d H:i:s");
    $time_check = $time-120;
    $sqlOnline = $conn->query("SELECT * FROM dm_session WHERE sess = '$sessiondate'");
    $countUsers = mysqli_num_rows($sqlOnline);
    if($countUsers == "0"){
        $sqlOnline2 = "INSERT INTO dm_session(`sess`,`date_sess`) VALUES ('$sessiondate','$date')";
        $conn->query("$sqlOnline2");
    }else{
        $sqlOnline3 = $conn->query("UPDATE dm_session SET `sess` = '$sessiondate', `date_sess` = '$date' WHERE `sess` = '$session'");
    }







    


   