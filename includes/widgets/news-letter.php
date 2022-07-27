<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/SMTP.php';
ob_start();
require( $_SERVER["DOCUMENT_ROOT"] . '/toniastore/htmlemails/subscrib-html.php');
$body = ob_get_contents();
ob_end_clean();
$email=((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
if(isset($_POST['submit'])){
  if($email == ''){
    $_SESSION['error_flash'] = 'Please Insert a vaild email';
    header("Location: index");
    
  }else{
    $sql= $conn->query("SELECT * FROM `news_letter` WHERE email = '$email'");
    $count = mysqli_num_rows($sql);
    if($count > 0){
      $_SESSION['error_flash'] = 'Thank You, You Have Subscrib Already';
      header("Location: index");
    }
    else{
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
        $mail->addAddress($email);
        $mail->Subject = 'News Letter Subscription';
        $mail->msgHTML(file_get_contents('message.html'), __DIR__);
        $mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/toniastore/img/logo1.jpg', 'logo','../img/logo1.jpg');
        $mail->isHTML(true);
        $mail->Body = $body.'<br><hr><img style="width:100px; height:100px;" src="cid:logo" alt="">';
        $mail->send();
        $sql2= $conn->query("INSERT INTO `news_letter`(`email`) VALUES('$email')");
        $_SESSION['success_flash'] = 'Thank You, Your Subscription Is Successful';
        header("Location: index");
    }
  }  
  
}

?>
<div class="news-letter">
    <form class="form-inline" method="post" action="index">
        <input type="email" name="email" class="form-control" placeholder="Email Address" >
        <button type="submit" name="submit" class=" btn btn-primary">Submit</button>
        
    </form>
</div>
<div class="clear"></div>

