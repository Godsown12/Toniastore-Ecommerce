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
$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
setcookie(CART_COOKIE,'',1,"/",$domain,false);


?>

<!-- Button trigger modal 
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button>->-->

<!-- Modal -->
<?php ob_start(); ?>
<div class="modal fade" id="thank-you" tabindex="-1" role="dialog" aria-labelledby="thank-you" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick="close();" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
         <h5 class="modal-title" id="thank-you">ToniaStore</h5>
      </div>
      <div class="modal-body ">
        <div class="symbol">
          <img src="img/logo.jpg" class="img-responsive">
        </div>
        <div class="thank-you">
          <h1 class="thankheader">Thank You!</h1>
          <p><b><?=$addressUser['full_name'];?></b>,&#160;ToniaStore appreciate you.</p>
          <p>Your order will be shipped to your address.</p>
        </div>
      </div>
      <div class="clear"></div>
        <button type="button" class="btn btn-lg btn-secondary thankClose" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
<?php echo ob_get_clean(); ?>