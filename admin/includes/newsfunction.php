<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/core/init.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/SMTP.php';
// bring out the email form database
$sql = $conn->query("SELECT email FROM `news_letter`");
while($sqlemail = mysqli_fetch_assoc($sql)){
    $emailArray[] = $sqlemail['email'];
}
$emailArray = array();
$subject=((isset($_POST['subject']))?sanitize($_POST['subject']):'');
$subject = trim($subject);
$message=((isset($_POST['userMessage']))?sanitize($_POST['userMessage']):'');
$message = trim($message);
if(isset($_POST['send'])){
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
    // bring out the email form database
    $sql = $conn->query("SELECT email FROM `news_letter`");
    while($sqlemail = mysqli_fetch_assoc($sql)){
        $emailArray[] = $sqlemail['email'];
    }
    foreach($emailArray as $email){
        $mail->AddCC($email);
    }
    $mail->Subject = $subject;
    $mail->WordWrap = 80;
    //$mail->msgHTML(file_get_contents('message.html'), __DIR__);
    $mail->isHTML(true);
    $mail->Body = $message;
    if (!empty($_FILES['attachment'])) {
        $count = count($_FILES['attachment']['name']);
        if ($count > 0) {
            // Attaching multiple files with the email
            for ($i = 0; $i < $count; $i ++) {
                if (!empty($_FILES["attachment"]["name"])) {
                    
                    $tempFileName = $_FILES["attachment"]["tmp_name"][$i];
                    $fileName = $_FILES["attachment"]["name"][$i];
                    $mail->AddAttachment($tempFileName, $fileName);
                }
            }
        }
    }
    if (! $mail->Send()) {
        $typeMessage = "Problem in sending email";
        $type = "error";
       
    } else {
        $typeMessage = "Mail sent successfully";
        $type = "success";
  
    }
}
