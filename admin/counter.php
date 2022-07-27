<?php
include 'includes/header.php';
include 'includes/navigation.php';
if (!is_logged_in()) {
    header("Location: login"); 
}
if (permission('customer')) {
    permission_error_redirect('../index');
}
// retriving exsting visitors
$sql = $conn->query("SELECT * FROM views");
$total_visitors = mysqli_num_rows($sql);
//
global $count_user;

// for day and month

$thisDay = date("d");
$thisMonth = date("m");
$sqlDay = $conn->query("SELECT * FROM dm_session WHERE DAY(date_sess) = '{$thisDay}'");
$day_count = mysqli_num_rows($sqlDay);
//for month
$sqlmonth = $conn->query("SELECT * FROM dm_session WHERE MONTH(date_sess) = '{$thisMonth}'");
$month_count = mysqli_num_rows($sqlmonth);

?>
<div class="container">
    <div class="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-sm-4 ">
                    <div class="views online">Online
                        <h3><?=(($count_user != '')?$count_user:'0');?></h3>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="views">Day
                        <h3><?=(($day_count != '')?$day_count:'0');?></h3>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="views">Month
                        <h3><?=(($month_count != '')?$month_count:'0');?></h3>
                    </div>
                </div>
            </div>    
        </div>
    </div>      
</div>