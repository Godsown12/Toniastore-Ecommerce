<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/core/init.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/SMTP.php';
$address_id = ((isset($_POST['addressId']) && $_POST['addressId'] != '')?sanitize($_POST['addressId']):'');
$address_id = (int)$address_id;
// sql statement to get the address name.
$sqlAddress = $conn->query("SELECT * FROM `address` WHERE address_id = '$address_id'");
$addressUser = mysqli_fetch_assoc($sqlAddress);
$email = $addressUser['email'];
$name = $name = $addressUser['full_name'];
$body='<html>
<head>
<link rel="stylesheet" type="text/css" href="css/video.css" media="all">
<style type="text/css">
    body{
    width:89%;
    margin:20px auto;
    background-color: rgb(240, 240, 240);
    padding:0;
   
    a{
        color:#fff;
    }
    tbody{
        background-position: center top;
		background-size: cover;
        background-repeat: no-repeat;
    }
    .u{
        width:50%;
    }
}

</style>
</head>
<body>                
<table width="100%" border="0" cellspacing="0" cellpadding="20" >
   <thead>
       <th style="background-color:#E002BB;">
       <a style="float:left; color:#fff;" href="www.toniastore.com">ToniaStore.com</a>
       <p style="font-size:1.4em; margin: 0 0 2px 0;">Thank You For Your Order </p>
       </th>
       <tbody background="https://toniastore.com/toniastore/img/hair3.jpg" >
           <td style=" min-height:700px;"><p style="color:#fff; font-size:1.3em; border: 1px solid #fff; width:80%; margin:50px auto; padding:30px;">Thank you so much <b>'.$addressUser['full_name'].'.</b> Please exercise patience while your order is brought to you at your doorsteps. You can call or message us on 07031038456. <br> We love you, your looks is our most priority.<br><br>
           <a style="color:#fff;" href="https://toniastore.com">Explore More From Us.</a>
        </p></td>
       </tbody>
   </thead>
   <table width="100%" border="0" cellspacing="0" cellpadding="20" >
       <thead style="background-color:#E002BB;">
       <th>  
       </th>
       <th style="font-size:1em;" >
        Gets Discounts On Our Products 
       </th>
       <th>
       </th>
       </thead>
      <tbody>
        <td style="width:50%;"><a href="https://toniastore.com/toniastore/category?cat=1"><img style="width:250px; height:250px;" src="https://toniastore.com/toniastore/img/email1.jpeg" alt=""></a></td>
        <td style="width:50%;"><a href="https://toniastore.com/toniastore/category?cat=2"><img style="width:250px; height:250px;" src="https://toniastore.com/toniastore/img/email2.jpeg" alt=""></a></td>
        <td style="width:50%;"><a href="https://toniastore.com/toniastore/category?cat=1"><img style="width:250px; height:250px; " src="https://toniastore.com/toniastore/img/email3.jpeg" alt=""></a></td>
      </tbody>
   </table>
   <table width="100%" border="0" cellspacing="0" cellpadding="20" >
       <thead>
       <th >
       </th>
       <th>
       </th>
       <th></th>
       </thead>
      <tbody style="background-color:#E002BB;">
        <td >Here to sign in:<br> <a style="color:#fff;" href="https://toniastore.com/toniastore/login">Toniastore/Login</a></td>
        <td> Where Fashion Is Freedom</td>
        <td  >Forget Password? <a style="color:#fff;" href="https://toniastore.com/toniastore/forgot_password">Click Here</a></td>
      </tbody>
   </table>
</table>
</body>
</html>
';
//for users
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = SMTP_USERS;
$mail->Password = SMTP_PASS;
$mail->setFrom('support@toniastore.com', 'Toniastore.com');
$mail->addReplyTo('support@toniastore.com', 'Toniastore.com');
$mail->addAddress($email, $name);
$mail->Subject = 'Your Order Form Toniastore';
$mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/toniastore/img/logo1.jpg', 'logo','../img/logo1.jpg');
$mail->isHTML(true);
$mail->Body = $body.'<br><hr><img style="width:100px; height:100px;" src="cid:logo" alt="">';
//$mail->addAttachment('../img/logo.jpg');
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'The email message was sent.';
}
