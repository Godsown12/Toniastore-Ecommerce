<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/core/init.php';
$cart_id = sanitize($_POST['cart_id']);
$grand_total = sanitize($_POST['grand_total']);
$item_count = sanitize($_POST['item_count']);
$sub_total = sanitize($_POST['sub_total']);
$summary = $conn->query("SELECT * FROM cart_summary WHERE cart_id = '$cart_id'");
$result = mysqli_fetch_assoc($summary);
$summaryCart = $result['cart_id'];
if($cart_id !=''){

	if($cart_id == $summaryCart){
		$sql="UPDATE `cart_summary` SET `item_count`=$item_count,`sub_total`=$sub_total,`grand_total`=$grand_total WHERE cart_id ='$cart_id'";
	}
	else{
		$sql="INSERT INTO `cart_summary`(`cart_id`, `item_count`, `sub_total`, `grand_total`) VALUES ($cart_id,$item_count,$sub_total,$grand_total)";
	}
	$conn->query($sql);
}

