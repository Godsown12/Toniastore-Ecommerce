<?php
	include 'includes/header.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}

	include 'includes/navigation.php';
	///PAGEING
	if (isset($_GET['page_no']) && $_GET['page_no'] != '') {
        $page_no = $_GET['page_no'];
    }else{
        $page_no = 1;
    }
//set the total records per page
    $total_records_per_page = 15;
// set the offset and next page and previous page and adjecent
    $offset = ($page_no - 1)* $total_records_per_page;
    $next_page = $page_no + 1;
    $previous_page = $page_no - 1;
    $adjacents = "2";
//to get the number of records form the database....
    $sql_count = $conn->query("SELECT COUNT(*) AS total_records FROM users");
    $result_count = mysqli_fetch_assoc($sql_count);
    $total_records = $result_count['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
	$second_last = $total_no_of_pages - 1; // total pages minus 1
	////


	$i=1;
	if(isset($_GET['delete'])){
		$delete_id = (int)$_GET['delete'];
		$delete_id =sanitize($_GET['delete']);
		$conn->query("DELETE FROM users WHERE users_id ='$delete_id'");
		$_SESSION['success_flash'] = 'Users has been delete.';
		header("Location: users");
	}
	// add urses...
	if(isset($_GET['add']) || isset($_GET['edit'])){
		$name = ((isset($_POST['name']) && $_POST['name'] != '')?sanitize($_POST['name']):'');
		$email = ((isset($_POST['email']) && $_POST['email'] != '')?sanitize($_POST['email']):'');
		$password = ((isset($_POST['password']) && $_POST['password'] != '')?sanitize($_POST['password']):'');
		$comfirm = ((isset($_POST['comfirm']) && $_POST['comfirm'] != '')?sanitize($_POST['comfirm']):'');
		$permissions = ((isset($_POST['permissions']) && $_POST['permissions'] != '')?sanitize($_POST['permissions']):'');
		$errors= array();

		if(isset($_GET['edit'])){
			$edit_id = (int)$_GET['edit'];
			$edit_id = sanitize($edit_id);
			$edit_users= $conn->query("SELECT * FROM users WHERE users_id = '$edit_id'");
			$result = mysqli_fetch_assoc($edit_users);
			$name = ((isset($_POST['name']) && $_POST['name'] != '' )?sanitize($_POST['name']):$result['full_name']);
			$email = ((isset($_POST['email']) && $_POST['email'] != '' )?sanitize($_POST['email']):$result['email']);
			$password = ((isset($_POST['password']) && $_POST['password'] != '' )?sanitize($_POST['password']):$result['password']);
			$permissions = ((isset($_POST['permissions']) && $_POST['permissions'] != '' )?sanitize($_POST['permissions']):$result['permissions']);
		}
		// validating the form...
		if($_POST){
			if(!isset($_GET['edit'])){
				$emailQuery = $conn->query("SELECT * FROM users WHERE email ='$email'");
				$emailCount = mysqli_num_rows($emailQuery);

				if($emailCount > 0){
					$errors[] = 'Email already exits';
				}
			}

			if(isset($_GET['edit'])){
				$required = array('name','email','permissions');
				foreach ($required as $f) {
					if(empty($_POST[$f])){
						$errors[] = 'You must fill all field.';
						break;
					}
				}
			}else{
				$required = array('name','email','password','comfirm','permissions');
				foreach ($required as $f) {
					if(empty($_POST[$f])){
						$errors[] = 'You must fill all field.';
						break;
					}
				}
			}
			// when editing...
			if(!isset($_GET['edit'])){

				if(strlen($password) < 6){
					$errors[] = 'Your Password must be at least six characters.';
				}
				if($password != $comfirm){
					$errors[] = 'Your password do not match.';
				}
			}
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors[] = 'Email is not vaild.';
			}

			if(!empty($errors)){
				echo display_errors($errors);
			}
			else{
				//insert into database
				if(!isset($_GET['edit'])){
					$hashed = password_hash($password,PASSWORD_DEFAULT);
					$conn->query("INSERT INTO `users`(`full_name`, `email`, `password`, `permissions`) VALUES ('$name','$email','$hashed','$permissions')");
					$_SESSION['success_flash'] = 'User has been added!.';
					header("Location: users");
				}
				
					$conn->query("UPDATE `users` SET `full_name`='$name',`email`='$email',`permissions`='$permissions' WHERE `users_id`='$edit_id'");
					$_SESSION['success_flash'] = 'The User have been updated.';
				
					header("Location: users");
			}
		}
		?>
			<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add New');?> Users</h2><hr>
			<form method="post" action="users?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>">
				<div class="form-group col-md-6">
					<label for="name">Full Name:</label>
					<input type="text" id="name" name="name" class="form-control" value="<?=$name;?>">
				</div>
				<div class="form-group col-md-6">
					<label for="email">Email:</label>
					<input type="email" id="email" name="email" class="form-control" value="<?=$email;?>">
				</div>
				<?php if(!isset($_GET['edit'])) : ?>
				<div class="form-group col-md-6">
					<label for="password">Password:</label>
					<input type="password" id="password" name="password" class="form-control" value="<?=$password;?>">
				</div>
				<div class="form-group col-md-6">
					<label for="comfirm">Comfirm Password:</label>
					<input type="password" id="comfirm" name="comfirm" class="form-control" value="<?=$comfirm;?>">
				</div>
				<?php endif; ?>
				<div class="form-group col-md-6">
					<label for="permissions">Permissions:</label>
					<select class="form-control" name="permissions">
						<option value=""<?=(($permissions == '' )?' selected':'');?>></option>
						<option value="editor"<?=(($permissions == 'editor' )?' selected':'');?>>Editor</option>
						<option value="admin,editor"<?=(($permissions == 'admin,editor' )?' selected':'');?>>Admin</option>
					</select>
				</div>
				<div class="form-group col-md-6 text-right" style="margin-top:25px;">
					<a href="users" class="btn btn-default">Cancel</a>
					<input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> User" class="btn btn-success">
					<div class="clear-fix"></div>
				</div>
			</form>
			<div class="clear"></div>


		<?php
	}else{


	$userQuery = $conn->query("SELECT * FROM users ORDER BY join_date  LIMIT  $offset, $total_records_per_page");
?>
<div class="container-fluid wrapper">
	<?=$flash;?>
	<h2 class="text-center">USERS</h2><hr>
	
	<div class="row">
		<?php
		 $sql3 = $conn->query("SELECT * FROM users WHERE permissions = 'customer'");
		 $result = mysqli_num_rows($sql3);

		 //$result = (string)$result;
		?>
		<p class="pull-left" style="margin-top:20px; font-size:1.1em;">We have <span class="badge"><?=$result;?></span> register customers.</p>
		<a href="users?add=1" class="btn btn-primary pull-right" id="add-product-btn">Add new User</a><div class="clearfix"></div>
		<table class="table table-bordered table-striped table-condensed">
			<thead><th></th><th>Name</th><th>Email</th><th>Phone_No</th><th>Join Date</th><th>Last Login-Date</th><th>Permission</th></thead>
			<tbody>
				<?php while($user = mysqli_fetch_assoc($userQuery)) : ?>
					<tr>
						<?php if(has_permission('admin') && $user['permissions'] != 'super_admin,admin,editor') : ?>
						<td>
							
						</td>
						<td><?=$user['full_name'];?></td>
						<td><?=$user['email'];?></td>
						<td><?=$user['phone_no'];?></td>
						<td><?=pretty_date($user['join_date']);?></td>
						<td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'Never': pretty_date($user['last_login']));?></td>
						<td><?=$user['permissions'];?></td>
						<td>
							<?php if($user['users_id'] != $user_data['users_id'] && $user['permissions'] != 'customer') : ?>
								<a href="users?delete=<?=$user['users_id'];?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>
								<a href="users?edit=<?=$user['users_id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
							<?php endif; ?>
						</td>
					
					</tr>
					<?php $i++;
					?>	
				<?php endif;  ?>
				<?php endwhile;  ?>
			</tbody>
		</table>
		<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
        <strong>Page <?=$page_no." of ".$total_no_of_pages; ?></strong>
        </div>
        <div>
        <ul class="pagination">
            <?php if($page_no > 1){
            echo "<li><a href='?page_no=1'>First Page</a></li>";
            } ?>
                
            <li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
            <a <?php if($page_no > 1){
            echo "href='?page_no=$previous_page'";
            } ?>>Previous</a>
            </li>
            <?php if ($total_no_of_pages <= 100){   
                        for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                            if ($counter == $page_no) {
                                echo "<li class='active'><a>$counter</a></li>"; 
                            }else{
                                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }
                        }
                    }   
            ?>
            <li <?php if($page_no >= $total_no_of_pages){
            echo "class='disabled'";
            } ?>>
            <a <?php if($page_no < $total_no_of_pages) {
            echo "href='?page_no=$next_page'";
            } ?>>Next</a>
            </li>
            
            <?php if($page_no < $total_no_of_pages){
            echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
            } ?>
			</ul>
		</div>	
	</div>
<?php } include 'includes/footer.php';?>