<?php
	include 'includes/header.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
		
	$hashed = $user_data['password']; 
	$old_password=((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
	$old_password = trim($old_password);
	$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
	$password = trim($password);
	$comfirm=((isset($_POST['comfirm']))?sanitize($_POST['comfirm']):'');
	$comfirm = trim($comfirm);
	$new_hashed = password_hash($password, PASSWORD_DEFAULT);
	$user_id = $user_data['users_id'];
	$errors= array();
?>
<style type="text/css">
	body{
		background-image: url("/toniastore/img/3.jpg");
		/*background-size: 100vw 100vh;*/
		background-attachment: fixed;
	}
</style>
<div class="container-fluid login-background">
	<div class="row">
		<div id="login-form">
			<div>

			<?php
				if($_POST){
					//form validation
					if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['comfirm'])){
						$errors[] = 'You must fill out all field';
					}
					
					//check for lenght of the password
					if (strlen($password) < 5 ) {
						$errors[] = 'The password must be at least 8 characters.'; 
					}
					// check if new password matches comfrim password
					if($password != $comfirm){
						$errors[] = 'Comfirm password and new password doesn\'t matches.';
					}
					if (!password_verify($old_password, $hashed)) {
						$errors[] = 'Your old password is not correct';
					}

					//check if there is errors
					if(!empty($errors)){
						echo display_errors($errors);
					}
					else{
						//change password
						$conn->query("UPDATE users SET password = '$new_hashed' WHERE users_id = '$user_id'");
						$_SESSION['success_flash'] = 'Your password have been updated!';
						header("Location: index");
					}
				}

			?>
			<!--<div class="flash"><p><?=$flash;?></p></div>-->
			</div>
			<h2 class="text-center">Change Password</h2><hr>
			<form method="post" action="change_password">
				<div class="form-group">
					<label for="old_password">Old Password</label>
					<input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
				</div>
				<div class="form-group">
					<label for="password">New Password</label>
					<input type="password" name="password" id="password"  class="form-control" value="<?=$password;?>">
				</div>
				<div class="form-group">
					<label for="comfirm">Comfirm New Password</label>
					<input type="password" name="comfirm" id="comfirm"  class="form-control" value="<?=$comfirm;?>">
				</div>
				<div class="form-group submit">
					<input type="submit" class="btn " value="Change">
					<a href="index" class="btn btn-default">Cancel</a>
					<p><a href="/toniastore/index" alt="home" >Visit Site</a></p>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>