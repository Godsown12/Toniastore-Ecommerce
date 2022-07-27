<?php
	include 'includes/header.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include 'includes/navigation.php';
	// fetch from database
	$sql="SELECT * FROM categories ";
	$result= $conn->query($sql);
	$img_path = mysqli_fetch_assoc($result);
	$errors = array();
	$dbpath = '';
	$category = ((isset($_POST['category']) && $_POST['category'] != '')?sanitize($_POST['category']):'');
	$photo = ((isset($_POST['photo']) && $_POST['photo'] != '')?sanitize($_POST['photo']):'');

	if(isset($_GET['edit']) && !empty($_GET['edit'])){
		$edit_id=(int)$_GET['edit'];
		$edit_id= sanitize($edit_id);
		$esql= "SELECT * FROM categories WHERE categories_id= '$edit_id'";
		$eresult= $conn->query($esql);
		$editcategory = mysqli_fetch_assoc($eresult);
		$category = ((isset($_POST['category']) && $_POST['category'] != '')?sanitize($_POST['category']):$editcategory['category']);
		$photo = ((isset($_POST['photo']) && $_POST['photo'] != '')?sanitize($_POST['photo']):$editcategory['background_img']);
	}
	// delete category
	if(isset($_GET['delete']) && !empty($_GET['delete'])){
		$delete_id = (int)$_GET['delete'];
		$delete_id= sanitize($delete_id);
		$dsql="DELETE FROM categories WHERE categories_id= '$delete_id'";
		$conn->query($dsql);
		$dproducts="DELETE FROM products WHERE categories = '$delete_id'";
		$conn->query($dproducts);
		header("location: categories");
	}

	if(isset($_POST['upload'])){

		if(!isset($_GET['edit'])){
			if($_FILES['photo']['name'] == ''){
				$errors[] = 'categories image is empty.';	
			}
		}else{
			$photo = $image['background_image'];
		}
		//validation 
		if($category == ''){
			$errors[] = 'Category is empty';
		}
		
		if($_FILES['photo']['name'] != ''){
            $photo = $_FILES['photo'];
            $name = $photo['name'];
            $image = $photo["type"];
            $nameArray = explode('.',$name);
            $fileName = $nameArray[0];
            $fileExt = $nameArray[1];
            $mime = explode('/',$image);
            $mimeType = $mime[0];
            $mimeExt = $mime[1];
            $tmpLoc = $photo['tmp_name'];
            $fileSize = $photo['size'];
            $allowed = array('png','jpg','jpeg','gif','JPG','PNG','JPEG');
            $uploadName = md5(microtime()).'.'.$fileExt;
            $uploadPath = BASEURL.'img/video/'.$uploadName;
            $dbpath = 'img/video/'.$uploadName;
            if($mimeType != 'image'){
                $errors[] = 'The file must be an image baby.';
            }
            
            if(!in_array($fileExt, $allowed)){
                $errors[] = 'The image extension must be a png, jpg, jpeg, or gif.';
            }
            if($fileSize > 8000000){
                $errors[] = 'The image size must be below 8MB.';
            }
            if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
                $errors[]= 'Image extension does not match the file.';
			}
			
		}
		if(!empty($errors)){
            $display = display_errors($errors);?>
			<script>
				jQuery('Document').ready(function(){
					jQuery('#errors').html('<?=$display?>');
				});
			</script><?php
		}
		
		//insert into database
		else{
			if(!empty($_FILES)){
                move_uploaded_file($tmpLoc,$uploadPath);
            }
			$sql3="INSERT INTO categories (category, background_img) values ('$category','$dbpath')";
			//update into database
			if(isset($_GET['edit'])){
				if(!empty($dbpath)){
					$sql3="UPDATE categories SET `category` = '$category', `background_img` = '$dbpath' WHERE categories_id = '$edit_id'";
				}else{
					$sql3="UPDATE categories SET `category` = '$category' WHERE categories_id = '$edit_id'";
				}	
			}
			$conn->query($sql3);
			header("location: categories");
		}	
	}

?>
	<div class="container-fluid">
		<h2 class="text-center"> Categories</h2><hr>
		<div class="row">
			<div class="col-md-6">
				<!-- categories form-->
					<form class="form" method="post" action="categories<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>"enctype="multipart/form-data">
						<legend><?=((isset($_GET['edit']))?'Edit Category':'Add Categories');?></legend>
						<div id="errors"></div>
						<!--<div class="form-group">
							<label for="parent">Categories</label>
							<select class="form-control" name="parent" id="parent">
								<option value="0">categories</option>
								<?php// while($parent = mysqli_fetch_assoc($result)) : ?>
									<option value="<?//=$parent['categories_id']?>"><?//=$parent['category']?></option>
								<?php// endwhile; ?>
							</select>
						</div>-->
						<div class="form-group">
							<label for="categories">Category</label>
							<input type="text" class="form-control" name="category" id="category" value="<?=$category;?>">
						</div>
						<div class="form-group">
							<label for="background_img">Background Image</label>
							<input type="file"id="photo" name="photo" class="form-control" value="<?=$photo;?>">
						</div>
						<div class="form-group">
							<input type="submit" name="upload"  class="btn btn-success" value="<?=((isset($_GET['edit']))?'Edit Category':'Add Category');?>">
						</div>
					</form>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered">
					<thead>
						<th>Categories</th><th>background Image</th><th> </th>
					</thead>
					<tbody>
						<?php 
							$sql="SELECT * FROM categories WHERE parent= 0";
							$result= $conn->query($sql);
							while( $parent = mysqli_fetch_assoc($result)) : ?>
							<tr class="bg-primary">
								<td><?=$parent['category']; ?></td>
								<td><img style="width:100px; height:100px" src="/toniastore/<?=$parent['background_img'];?>" alt=""></td>
								<td><a href="categories?edit=<?=$parent['categories_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>&#160;
									<a href="categories?delete=<?=$parent['categories_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
								</td>
							</tr>
						<?php endwhile;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>