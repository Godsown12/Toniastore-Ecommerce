<?php
include "includes/header.php";
require 'includes/include_login.php';
//<link rel="stylesheet" href="css/login.css">
//<link rel="stylesheet" type="text/css" href="css/register.css" media="all">
?>

		<div class="row row-login">
			<div class="col account">
				<div class="flash"><p><?=$flash;?></p></div>
					<form method="post" action="register">
						<!-- register form -->
						<div class="register_l" id="register_l" >
							<div class="form-group form-box">
								<h2>ToniaStore Welcome You</h2>
								<label for="name">Full Name:</label>
								<input type="text" class="form-control text" placeholder="Your Full Name" name="name" tabindex="1" autocomplete="off"  required><br />
								<label for="password">Password:</label>
								<input type="password" class="form-control text" pattern=".{6,}" required title="6 characters minimum" placeholder="Your Password" name="password" tabindex="2" autocomplete="off" required><br />
								<label for="passwordCheck">Re-write Password:</label>
								<input type="password" pattern=".{6,}" required title="6 characters minimum" class="form-control text" placeholder="Repeat Password" name="passwordCheck" tabindex="3" autocomplete="off"  required><br />
								<label for="email">Email:</label>
								<input type="email" class="form-control text" placeholder="Your Email" name="email" tabindex="4" autocomplete="off" required><br />
								<label for="phone_no">Email:</label>
								<input type="phone_no" class="form-control text" placeholder="Your Contact No" name="phone_no" tabindex="4" autocomplete="off" required><br />
								<input type="submit" name="submit" class="btn btn-lg login_button" value="Submit">
								<p class="login-here-l" tabindex="5"><a href="#">Registerd? <span> &nbsp;Login Here</span></a></p>
							</div>
						</div>		
					</form>
				<form method="post" action="login">
					<!--login form -->
					<div id="login-l">
						<div class="form-group form-box">
							<h2>Welcome Pal, Please Login</h2>
							<?php
							if(!empty($errors)){
								echo display_errors($errors);
							}							
							?>
							<label for="username">Email:</label><?php echo $erroremailLogin; ?>
							<input type="email" class="form-control text" placeholder="Your Email" name="email" value="<?=$emailLogin;?>" tabindex="1" autocomplete="off" required><br />
							<label for="password">Password:</label><?php echo $errorPasswordLogin; ?>
							<input type="password" class="form-control text" placeholder="Your Password" name="passwordLogin" tabindex="2" autocomplete="off" required>
							<?php if($errors): ?>
										<p class="forgot_password" tabindex="4"><a href="forgot_password?email=<?=$emailLogin?>">forgot password?<span>&nbsp;Click here</span></a></p><br />
							<?php else : ?>
										<br />
							<?php endif; ?>
							<button type="submit" name="submitLogin" class="btn btn-lg login_button" tabindex="3">Login</button>
							<p class="register-here-l" tabindex="4"><a href="#">Not registered?<span> &nbsp;Create an account</span></a></p>
						</div>
					</div>	
				</form>
			</div>
		</div>
	</div>
</body>
</html>