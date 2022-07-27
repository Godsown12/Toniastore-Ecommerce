<?php
require 'includes/include_login.php';

?>
<!DOCTYPE html>
<html lang="en-us">
<html>

		<div class="row">
			<div class="col account">
				<form method="post" action="../account/register.php">
					<!-- register form -->
					<div id="register-l" class="register-l">
						<div class="form-group form-box">
							<h2>ToniaStore Welcome You</h2>
							<label for="name">User Name:</label>
							<input type="text" class="form-control text" placeholder="Your Name" name="name" tabindex="1" autocomplete="off" required><br />
							<label for="password">Password:</label>
							<input type="password" class="form-control text" placeholder="Your Password" name="password" tabindex="2" autocomplete="off" required><br />
							<label for="passwordCheck">Re-write Password:</label>
							<input type="password" class="form-control text" placeholder="Repeat Password" name="passwordCheck" tabindex="3" autocomplete="off" required ><br />
							<label for="email">Email:</label>
							<input type="email" class="form-control text" placeholder="Your Email" name="email" tabindex="4" autocomplete="off" required><br />
							<input type="submit" name="submit" class="button btn btn-lg submit" value="Submit">
							<p class="login-here-l" tabindex="5"><a href="#">Login Here</a></p>
						</div>
					</div>	
				</form>

				<form method="post" action="../account/login.php">
					<!--login form -->
					<div id="login-l">
						<div class="form-group form-box">
							<h2>Welcome Pal, Please Login</h2>
							<?php
							if(isset($_GET['error'])) {
								if($_GET['error'] == "sqlerror"){

									echo '<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Opps! SQL Error</div>';
								}
								if ($_GET['error'] == "success"){

									echo '<div class="alert alert-success alert-dismissible fade in alert-edit "><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>successful pal, login now</div>';
								}
								elseif($_GET['error'] == "emptyfield"){

									echo '<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Come on! some fields are empty</div>';
								}
								elseif($_GET['error'] == "worngpassword"){

									echo '<div class="alert alert-danger alert-dismissible fade in  "><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Opps! worng password</div>';
								}
								elseif($_GET['error'] == "nouser"){

									echo '<div class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Opps! No User</div>';
								}
							}

							?>
							<label for="username">UserName:</label><?php echo $errornameLogin; ?>
							<input type="text" class="form-control text" placeholder="Your Email Or Username" name="userNameLogin" tabindex="1" autocomplete="off" required><br />
							<label for="password">Password:</label><?php echo $errorPasswordLogin; ?>
							<input type="password" class="form-control text" placeholder="Your Password" name="passwordLogin" tabindex="2" autocomplete="off" required><br />
							<input type="submit" name="submitLogin" class="button btn btn-lg submit" value="Submit" tabindex="3">
							<p class="register-here-l" tabindex="4"><a href="#">Register Here</a></p>
						</div>
					</div>	
				</form>
			</div>
		</div>
	</div>
</body>
</html>