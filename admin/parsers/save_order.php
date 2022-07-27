<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/core/init.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/SMTP.php';

$address_id = ((isset($_POST['addressid']) && $_POST['addressid'] != '')?sanitize($_POST['addressid']):'');
$address_id = (int)$address_id;
$grand_total = ((isset($_POST['amount']) && $_POST['amount'])?sanitize($_POST['amount']): '');
$sub_total = ((isset($_POST['subtotal']) && $_POST['subtotal'])?sanitize($_POST['subtotal']): '');
$delivery = ((isset($_POST['delivery']) && $_POST['delivery'])?sanitize($_POST['delivery']): '');
$tax = TAXRATE;
// sql statement to get the address name.
$sqlAddress = $conn->query("SELECT * FROM `address` WHERE address_id = '$address_id'");
$addressUser = mysqli_fetch_assoc($sqlAddress);
$email = $addressUser['email'];
$name = $addressUser['full_name'];

//save the cart_items in database and create a order....
$cart_data = json_encode($cart_data, true);
$description = $cart_count.' Product'.(($cart_count > 1 )?'s':'').' From Toniastore To Be Shipped';
$sqlOrder ="INSERT INTO `order_save`(`address_id`,`description`,`cart_items`,`sub_total`,`delivery`,`tax`,`grand_total`) VALUES ('$address_id','$description','$cart_data','$sub_total','$delivery','$tax','$grand_total')";
$conn->query($sqlOrder);
$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
setcookie(CART_COOKIE,'',1,"/",$domain,false);

//to send mail
// for admin
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = SMTP_USERS;
$mail->Password = SMTP_PASS;
$mail->setFrom('support@toniastore.com', 'Toniastore.com Order');
$mail->addReplyTo('support@toniastore.com', 'Toniastore.com Order page');
$mail->addAddress('support@toniastore.com', 'Toniastore.com Order');
$mail->Subject = 'Toniastore Order';
$mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/toniastore/img/logo1.jpg', 'logo','../img/logo1.jpg');
$mail->isHTML(true);
$mail->Body = '<h2 style="background-color:#e002bb; padding: 10px;">Toniastore Order Just Came In.</h2><hr><br>You have received an order from '.$name.'.<br><br> Please Angel there is an order, so check your Admin page order list...This order is to pay on delivery.<br><br> <img src="cid:logo" style="width:100px; height:100px;">';
//$mail->addAttachment('../img/logo.jpg');
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'The email message was sent.';
}



