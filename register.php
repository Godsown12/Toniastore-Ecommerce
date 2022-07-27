<?php
	include 'includes/header.php';
	require 'includes/include_register.php';		
?>
		<div class="row row-register">
			<div class="col account">
				<form method="post" action="register">
					<!-- register form -->
					<div id="register">
						<div class="form-group form-box">
							<h2>ToniaStore Welcome You</h2>
							<?php 
							if(!empty($errors)){
								echo display_errors($errors);
							}							
							?>
							<label for="name">FULL Name:</label><?php echo $errorName; ?>
							<input type="text" class="form-control text" placeholder="Your Full Name" name="name" tabindex="1" autocomplete="off" value="<?=$name;?>" required /><br />
							<label for="password">Password:</label><?php echo $errorPassword; ?>
							<input type="password" class="form-control text" pattern=".{6,}" required title="6 characters minimum" placeholder="Your Password" name="password" tabindex="2" autocomplete="off" required ><br />
							<label for="passwordCheck">Re-write Password:</label> <?php echo $errorpasswordCheck; ?>
							<input type="password" class="form-control text" pattern=".{6,}" required title="6 characters minimum" placeholder="Repeat Password" name="passwordCheck" tabindex="3" autocomplete="off" required><br />
							<label for="email">Email:</label><?php echo $errorEmail; ?>
							<input type="email" class="form-control text" placeholder="Your Email" name="email" tabindex="4" autocomplete="off" value="<?=$email;?>" required ><br />
							<label for="phone_no">Contact No:</label><?php echo $errorPhone_no; ?>
							<input type="text" class="form-control text" placeholder="Your Contact No" name="phone_no" tabindex="4" autocomplete="off" value="<?=$phone_no;?>" required ><br />
							<input type="submit" name="submit" class=" btn btn-lg  register_button" value="Submit" tabindex="5">
							<p class="login-here" tabindex="6"><a href="#">Registerd? <span> &nbsp;Login Here</span></a></p>
						</div>
					</div>	
				</form>

				<form method="post" action= "login ">
					<!--login form -->
					<div class="login" id="login">
						<div class="form-group form-box">
							<h2>Welcome Pal, Please Login</h2>
							<label for="name">Email:</label><?php echo $erroremailLogin; ?>
							<input type="email" class="form-control text" placeholder="Your Email" name="email" tabindex="1" autocomplete="off" required ><br />
							<label for="password">Password:</label><?php echo $errorPasswordLogin; ?>
							<input type="password" class="form-control text" placeholder="Your Password" name="passwordLogin" tabindex="2" autocomplete="off" required><br />
							<input type="submit" name="submitLogin" class="btn btn-lg register_button" value="Submit" tabindex="3">
							<p class="register-here"><a href="#" tabindex="4">Not registered?<span> &nbsp;Create an account</span></p>
						</div>
					</div>	
				</form>  
			</div>
		</div>
	</div>
</body>
</html>
