<?php
include 'includes/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
$name= ((isset($_POST['name']) && $_POST['name'] != '')?sanitize($_POST['name']):'');
$email= ((isset($_POST['email']) && $_POST['email'] != '')?sanitize($_POST['email']):'');
$message = ((isset($_POST['message']) && $_POST['message'] != '')?sanitize($_POST['message']):'');
$msg='';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERS;
    $mail->Password =  SMTP_PASS;
    $mail->setFrom('support@toniastore.com', 'Contact page');
    $mail->addAddress('support@toniastore.com', 'Contact page');
    if ($mail->addReplyTo($email, $name)) {
        $mail->Subject = 'Contact Us Page';
        $mail->isHTML(false);
        $mail->Body = <<<EOT
Email: {$_POST['email']}
Name: {$_POST['name']}
Message: {$_POST['message']}
EOT;
        if (!$mail->send()) {
            $msg = '<p id="flash" style="text-align:center; font-weight:bolder; font-size:16px;" class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Sorry, something went wrong. Please try again later.</p>.';
        } else {
            $_SESSION['success_flash']= 'Message sent! Thanks for contacting us.';
            header("Location: index");
        }
    }
?>
<div class="row contact">
	<div class="col-sm-9 ">
		<form method="post" action="contact">
			<div class="form-group form-box">
				<h2>Please Get In Touch With ToniaStore</h2>
				<hr>
				<?php
				echo $msg;
				?>
				<input type="text" name="name" placeholder=" Full Name" class="form-control text" tabindex="1" autocomplete="off" required><br>
				<input type="email"  name="email" placeholder="Your Email" tabindex="1" class="form-control text" autocomplete="off" required><br>
				<textarea name="message" placeholder="Your message" tabindex="1" class="form-control" autocomplete="off" value="<?=$message;?>" required><?=$message;?></textarea><br>
				<input type="submit" name="submit" class=" btn btn-success btn-lg submit" value="Submit">
			</div>
		</form>
    </div>
    <div class="col-sm-3 address">
    <address>
        <h3>Phone Support</h3>07031038456<br><hr>
        <h3>Technical Support</h4>08136779046<hr>
        <h3>Service Hours</h3>8am - 7pm (Monday - Friday)<br>
        8am - 6pm Weekends
    </address>
    <div>
        <img src="img/address.png" alt="">
    </div>
    </div>
</div>
<?php include 'includes/contact-footer.php'; ?>