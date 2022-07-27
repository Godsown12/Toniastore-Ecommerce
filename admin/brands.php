<?php
	include 'includes/header.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include 'includes/navigation.php';
	//get from database
	$sql=" SELECT * FROM brand ORDER BY brand";
	$result= $conn->query($sql);
	$brand = mysqli_fetch_assoc($result);
	 
	//  To add brand to the database
	$errors= array();
	$brand_value='';

	//edit brand in database
	if (isset($_GET['edit']) && !empty($_GET['edit'])) {
		$edit_id =(int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$sqledit = "SELECT * FROM brand WHERE brand_id = '$edit_id'";
		$edit_result = $conn->query($sqledit);
		$edit_brand= mysqli_fetch_assoc($edit_result);

		# code...
	}

	//delete brand from database
	//first we need to check if there is delete variable and if is not empty
	if(isset($_GET['delete']) && !empty($_GET['delete'])){
		$delete_id = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);
		$sql=" DELETE FROM brand WHERE brand_id = '$delete_id'";
		$conn->query($sql);
		header("Location: brands");
	}

	if(isset($_POST['submit'])){
		$brand= sanitize($_POST['brand']);

		if($brand == ''){
			$errors[] .= 'Baby! Please add a brand';
		}
		//check if brand exits in database
		$sql="SELECT * FROM brand WHERE brand = '$brand'";
		// to make update on brand
		if(isset($_GET['edit'])){
			$sql = "SELECT * FROM brand WHERE brand= '$brand' AND brand_id != '$edit_id'";
		}
		$result= $conn->query($sql);
		$count = mysqli_num_rows($result);
		if($count > 0){
			$errors[] .='Angel <strong>'.$brand.'</strong> already exits, Please Love chose another name....';

		}
		//display errors
		if(!empty($errors)){

			echo display_errors($errors);
			$sql=" SELECT * FROM brand ORDER BY brand";
			$result= $conn->query($sql);
			$brand = mysqli_fetch_assoc($result);

		}// insert brand into database
		else{
		$sql2="INSERT INTO brand (brand) VALUES ('$brand')";
		// we are updating into data base
		if(isset($_GET['edit'])){
			$sql2 = "UPDATE brand SET brand= '$brand' WHERE brand_id = '$edit_id'";
		}
			$conn->query($sql2);
			header("Location: brands");
		}	
	}
	
?>
	<div class="container-fluid">
		<div class="row">
			<div class="col content">
				<h2 class="text-center"> Brands</h2><hr>
				<!-- brand form-->
				<div class="text-center">
					<form class="form-inline form-brand" method="post" action="brands<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" >
						<div class="form-group">
							<!-- to post the value in the text-box-->
							<?php 
								
								if(isset($_GET['edit'])){
									$brand_value= $edit_brand['brand'];
								}else{
									if(isset($_POST['brand'])){
										$brand_value = sanitize($_POST['brand']);
									}
								}
							?>
							<label for ="brand"><?=((isset($_GET['edit']))?'Edit':'Add A');?> Brand</label>&#160;&#160;&#160;
							<input type="text" class="form-control" id="brand" name="brand" value="<?=$brand_value;?>">&#160;&#160;
							<?php if(isset($_GET['edit'])) :?>
							<a href="brands" class="btn btn-default">Cancel</a>&#160;&#160;
							<?php endif; ?>
							<input type="submit" class="btn btn-primary form-brand-submit"  name="submit" value="<?=((isset($_GET['edit'])))?'Edit':'Add';?> brand">
						</div>
					</form>
				</div>
				<br><br><hr>
				<table class="table table-bordered table-striped table-auto red">
					<thead>
						<th></th><th>Brand</th><th></th>
					</thead>
					<tbody>
						<?php
							$sql=" SELECT * FROM brand ORDER BY brand";
							$results= $conn->query($sql);
							while($brands = mysqli_fetch_assoc($results)) : ?>
							<tr>
								<td><a href="brands?edit=<?=$brands['brand_id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
								<td><?= $brands['brand']; ?></td>
								<td><a href="brands?delete=<?=$brands['brand_id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>	
		</div>	
<?php
	include 'includes/footer.php';
?>