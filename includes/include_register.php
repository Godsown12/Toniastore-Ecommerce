<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/toniastore/PHPMailer/src/SMTP.php';
ob_start();
require( $_SERVER["DOCUMENT_ROOT"] . '/toniastore/htmlemails/welcome-html.php');
$body = ob_get_contents();
ob_end_clean();
	$errorEmail="";
    $errorpasswordCheck="";
    $errorName="";
    $errorPassword="";
    $errorPhone_no="";
    $errors= array();
    $name =((isset($_POST['name']))?sanitize($_POST['name']):'');
    $name= trim($name);
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $password = trim($password);
    //$password = strtolower($password);
    $passwordCheck = ((isset($_POST['passwordCheck']))?sanitize($_POST['passwordCheck']):'');
    $passwordCheck = trim($passwordCheck);
    //$passwordCheck = strtolower($passwordCheck);
    $phone_no =((isset($_POST['phone_no']))?sanitize($_POST['phone_no']):'');
    $phone_no= trim($phone_no);
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $email = trim($email);
    $email = strtolower($email);
    $customers="customer";

   
    //validation for registration page... 
    if(isset($_POST['submit'])){

    	if(!$email){

    		$errorEmail='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter your email</div>';
    	}

    	if ($email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {

			 $errorEmail='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_POST['email'].'&nbsp;is a not valid email address</div>';
		}
    	
    	if(!$name){

    		$errorName='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter your name</div>';
    	}

        if (!preg_match("/^[a-zA-Z0-9]*$/",$name)) {

            $errorName='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please a valid name</div>';
        }
        if(!$phone_no){

    		$phone_no='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter your contact number</div>';
    	}
    	if(!$password){

    		$errorPassword= '<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter your password </div>';
    	}

	    if(strlen($password) < 6 ){
            
             $errorPassword='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your password must be at least 7 character long</div>';
	    		
 		}

    	if(!$passwordCheck){

    		$errorpasswordCheck='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please re-write your password</div>';
    	}

    	if($passwordCheck !== $password){

            $errorpasswordCheck='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Password does not match </div>';
        }

        else{
             $sql="SELECT email FROM users WHERE email=?";
             $stmt= mysqli_stmt_init($conn);
             if (!mysqli_stmt_prepare($stmt, $sql)) {
                $errors[] = 'Opps! SQL Error';
             }
            else{
                mysqli_stmt_bind_param($stmt , "s" ,$email );
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultCheck=mysqli_stmt_num_rows($stmt);

                if ($resultCheck > 0) {

                    $errors[] ='Opps! email already exits';
                }

                else{
                    $sql="INSERT INTO users (full_name, password, email, phone_no, permissions) VALUES (?, ?, ?, ?, ?)";
                    $stmt= mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                       $errors[] = 'Opps! SQL Error';
                    }
                    else{
                        $hashedPasword= password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt , "sssss" ,$name, $hashedPasword, $email, $phone_no, $customers);
                        mysqli_stmt_execute($stmt);
                        $_SESSION['success_flash'] = 'Thank You, Your Registration is Successful';
                        header("location: login");
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
                        $mail->Subject = 'Welcome to Toniastore';
                        $mail->msgHTML(file_get_contents('message.html'), __DIR__);
                        $mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/toniastore/img/logo1.jpg', 'logo','../img/logo1.jpg');
                        $mail->isHTML(true);
                        $mail->Body = $body.'<br><hr><img style="width:100px; height:100px;" src="cid:logo" alt="">';
                        $mail->send();
                    }
                }
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        
        }    
        
    }


    $erroremailLogin="";
    $errorPasswordLogin="";
    $emailLogin=((isset($_POST['email']))?sanitize($_POST['email']):'');
    $emailLogin = trim($emailLogin);
    $emailLogin = strtolower($emailLogin);
    $passwordLogin =((isset($_POST['passwordLogin']))?sanitize($_POST['passwordLogin']):'');
    $passwordLogin = trim($passwordLogin);
    $passwordLogin = strtolower($passwordLogin);

//validation for login form pop-model in registration page.... 
    if(isset($_POST['submitLogin'])){

        if(empty($emailLogin)){
            $error[] ='Please enter your names';

        }

        if(empty($passwordLogin)){

            $error[] = 'Please enter your password ';
        }
        
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            $error[] ='Invalid Email';
        }

        else {

            $sql="SELECT * FROM users WHERE email=?;";
            $stmt= mysqli_stmt_init($conn);
             if (!mysqli_stmt_prepare($stmt, $sql)) {
                $errors[] = 'Sorry sql error.';
             }
            else {
                mysqli_stmt_bind_param($stmt, "s", $emailLogin);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                if($row < 1){
                    $errors[] = 'Email is not in our database.';
                }
                if(!password_verify($passwordLogin, $row['password'])){
                    $errors[] = 'Your password is wrong.';
                }
                if(empty($errors)){
                    $user_id = $row['users_id'];
                     login($user_id);
                }
                
            }

        }                
    } 

