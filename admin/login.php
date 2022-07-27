<?php
	include 'includes/header.php';
	$email=((isset($_POST['email']))?sanitize($_POST['email']):'');
	$email = trim($email);
	$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
	$password = trim($password);
	$errors= array();
?>
<style type="text/css">
	
</style>
<div class="container-fluid login-background">
	<div class="row">
		<div id="login-form">
			<div>
			<?php
				if($_POST){
					//form validation
					if(empty($_POST['email']) || empty($_POST['password'])){
						$errors[] = 'Angel please provide email and password.';
					}
					// validate email
					if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
						$errors[] = 'Please enter a valid email dear!.';
					}
					//check for lenght of the password
					if (strlen($password) < 5 ) {
						$errors[] = 'The password must be at least 5 characters.'; 
					}
					// redirect users to frontend if permissions is customer
					$sql2=$conn->query("SELECT * FROM users WHERE email = '$email' AND permissions = 'customer'");
					$User2 = mysqli_fetch_assoc($sql2);
					if($User2['permissions'] != '' && $User2['permissions'] == 'customer'){
						header("location: ../login");
						$_SESSION['error_flash'] = 'Ahaha!..Sorry pal! you can\'t go there.. Am watching you.';
						exit();
					}
					// check if users already exist
					$sql=$conn->query("SELECT * FROM users WHERE email = '$email'");
					$user = mysqli_fetch_assoc($sql);
					$userCount = mysqli_num_rows($sql);
					if($userCount < 1){
						$errors[] = 'Wrong email baby!.';
					}

					if (!password_verify($password, $user['password'])) {
						$errors[] = 'Wrong password Angel. Please try again.';
					}
					
					//check if there is errors
					if(!empty($errors)){
						echo display_errors($errors);

					}

					else{
						//log users in....
						$user_id = $user['users_id'];
						login($user_id);
					}
				}

			?>
			</div>
			<h2 class="text-center">Login</h2><hr>
			<div class="flash"><p><?=$flash;?></p></div>
			<form method="post" action="login">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password"  class="form-control" value="<?=$password;?>">
				</div>
				<div class="form-group submit">
					<input type="submit" class="btn " value="Login">
					<p><a href="/toniastore/index" alt="home" >Visit Site</a></p>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>