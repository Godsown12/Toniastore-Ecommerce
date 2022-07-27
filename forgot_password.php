<?php
require_once 'core/init.php';
// to get the email address form login page
$mail = '';
if(isset($_GET['email'])){
    $mail =sanitize($_GET['email']);
}

if(empty(isset($_GET['email']))){
    header("Location: login.php");
}



$email = ((isset($_POST['email']) && $_POST['email'] != '')?sanitize($_POST['email']):$mail);
$email = trim($email);
$email = strtolower($email);
$newPassword = ((isset($_POST['newPassword']) && $_POST['newPassword'] != '')?sanitize($_POST['newPassword']):'');
$newPassword = trim($newPassword);
$newPassword = strtolower($newPassword);
$hashed = password_hash($newPassword,PASSWORD_DEFAULT);
$comfirmPassword = ((isset($_POST['comfirmPassword']) && $_POST['comfirmPassword'] != '')?sanitize($_POST['comfirmPassword']):'');
$comfirmPassword = trim($comfirmPassword);
$comfirmPassword = strtolower($comfirmPassword);


// get data from database
$errors = array();
$sqlGet= $conn->query("SELECT email FROM users WHERE email = '$email'");
$getResult = mysqli_fetch_assoc($sqlGet);
$resultEmail = $getResult['email'];


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale = 1.0"/>
	<title>Tonia Store</title>
	<link rel="shortcut icon" type="image/x-icon" href="img/logo.jpg" media="all">
    <!--code to import jquery javascript-->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="jquery-ui/jquery-ui.min.js"></script>
    <!--code to import bootsrap css file-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css ">
    <link rel="stylesheet" type="text/css" href="css/forgot_password.css" media="all">
    <!--code to import fontawesome css-->
	<link rel="stylesheet" type="text/css" href="fontawesome-free-5.5.0-web/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="fontawesome-free-5.5.0-web/css/v4-shims.min.css">
	<!--jquery-ui css-->
	<link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.structure.min.css">
	<link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.theme.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
        <div class="logo">
            <img src="img/ts4.jpg" alt="">
        </div>
            <div class="form-body">
            <h2>Change password with ease</h2>
            <?php
                if(isset($_POST['submit'])){
                    if($email == '' || $newPassword == '' || $comfirmPassword == ''){
                        $errors[] = " All field must be filled";
                    }

                    if($email != $resultEmail){
                        $errors[] = "Email not found in our database";
                    }

                    if(strlen($newPassword < 6)){
                        $errors[] ="The password must be at least 8 characters.";
                    }

                    if($newPassword != $comfirmPassword){
                        $errors[] = "Comfirm password and new password doesn\'t matches.'";
                    }
                    
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $errors[] = "invaild emaill address";
                    }

                    if($errors){
                        echo display_errors($errors);
                    }
                    else{
                        $sqlUpdate = "UPDATE users SET password = '$hashed' WHERE email = '$email'";
                        $conn->query($sqlUpdate);
                        $_SESSION['success_flash'] = 'Your password have been updated!';
						header("Location: login");
                    }

                }
            ?>
                <form method="post" action="forgot_password">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control" name="email" value="<?=$email?>" placeholder="email" autocomplete="off" required><br />
                        <label for="newPassword">New Password:</label>
                        <input type="password" class="form-control" pattern=".{6,}" required title="6 characters minimum" name="newPassword" value="" placeholder="******" autocomplete="off"><br />
                        <label for="comfirmPassword">Comfirm Password:</label>
                        <input type="password" class="form-control" pattern=".{6,}" required title="6 characters minimum" name="comfirmPassword" value="" placeholder="******" autocomplete="off"><br />
                        <button type="submit" name="submit" class="btn btn-lg button" tabindex="3">Login</button>
                        <p><a href="login">Cancel</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

