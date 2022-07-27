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
        $errors= array();
        $erroremailLogin="";
        $errorPasswordLogin="";
        $emailLogin= ((isset($_POST['email']))?sanitize($_POST['email']):'');
        $emailLogin = trim($emailLogin);
        $passwordLogin =((isset($_POST['passwordLogin']))?sanitize($_POST['passwordLogin']):'');
        $passwordLogin = trim($passwordLogin);
// login form 
    if(isset($_POST['submitLogin'])){

        if(empty($emailLogin)){
            $erroremailLogin='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter your name</div>';

        }

        if(empty($passwordLogin)){

            $errorPasswordLogin= '<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter your password </div>';
        }
        
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            $erroremailLogin='<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Invalid Email</div>';
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
                elseif(!password_verify($passwordLogin, $row['password'])){
                    $errors[] = 'Your password is wrong.';
                }
                else{
                    $user_id = $row['users_id'];
                     login($user_id);
                }
            }

        }
        
        
    } 

	//register form in login page

	$errorEmail="";
    $errorpasswordCheck="";
    $errorName="";
    $errorPhone_no="";
    $errorPassword="";
    $name =((isset($_POST['name']))?sanitize($_POST['name']):'');
    $name = trim($name);
    $phone_no =((isset($_POST['phone_no']))?sanitize($_POST['phone_no']):'');
    $phone_no = trim($phone_no);
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $password = trim($password);
    $passwordCheck = ((isset($_POST['passwordCheck']))?sanitize($_POST['passwordCheck']):'');
    $passwordCheck = trim($passwordCheck);
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $email = trim($email);
    $customers="customer";

     //validation for registration page... 
    if(isset($_POST['submit'])){

    	if(empty($email) || empty($name) || empty($passwordCheck) || empty($errorPassword || empty($phone_no))){

    		$errors[] = 'Fill out the empty fields.';
    	}

    	if ($email != '' && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

			 $errors[] = 'Email is invalid.';
		}
    	
    	 
        if (!preg_match("/^[a-zA-Z0-9]*$/",$name)) {

            $errors[] = 'Come on pal! Username contain special characters';
        }

    	if(strlen($password) < 6){
            
			$errors[] = 'Password must be more than 8 characters';	    		
 		}

    	if($passwordCheck !== $password){

        	$errors[] = 'Come on pal! Password does not match';
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
                        $hashedPasword= password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt , "sssss" ,$name, $hashedPasword, $email, $phone_no, $customers);
                        mysqli_stmt_execute($stmt);
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
