<?php 
include 'includes/header.php';
if (!is_logged_in()) {
	login_error_redirect();
}
include 'includes/navigation.php';
//delete product
if(isset($_GET['delete'])){
	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);
	$conn->query("UPDATE products SET deleted = 1 WHERE products_id = '$delete_id'");
	header("Location: products");
}
// for search...
$search =((isset($_POST['search']) && $_POST['search'] != '' )?sanitize($_POST['search']):'');
$sqlsearch= "SELECT * FROM products WHERE deleted = 0 AND title LIKE '%$search%'";
// for products
$sql="SELECT * FROM products WHERE deleted = 0 ORDER BY products_id DESC";
if($search != ''){
	$presult = $conn->query($sqlsearch);
}else{
	$presult = $conn->query($sql);
}

$dbpath='';
$videoDbpath = '';
//when you click the add button
if(isset($_GET['add']) || isset($_GET['edit'])){
	$brandQuery = $conn->query("SELECT * FROM brand ORDER BY brand");
	$categoriesQuery = $conn->query("SELECT * FROM categories ORDER BY category");
	//to edit in the form
	$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
	$title = ucwords($title);
	$brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):'');
	$categories = ((isset($_POST['categories']) && $_POST['categories'] != '')?sanitize($_POST['categories']):'');
	$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
	$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
	$discount = ((isset($_POST['discount']) && $_POST['discount'] != '')?sanitize($_POST['discount']):'');
	$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
	$color = ((isset($_POST['color']) && $_POST['color'] != '')?sanitize($_POST['color']):'');
	$color = rtrim($color,',');
	$saved_image='';
	$save_video = '';
	//for sizes
	//$size_array = isset($_POST['size'])?sanitize($_POST['size']) : array();
	//$sizePrice_array = isset($_POST['sizePrice']) && $_POST['sizePrice'] != '' ? sanitize($_POST['sizePrice']): array();
	//$sizeListPrice_array = isset($_POST['sizeListPrice']) && $_POST['sizeListPrice'] != '' ? sanitize($_POST['sizeListPrice']) : array();
	$editSize_id = array();
	if (isset($_GET['edit'])) {
		$edit_id =(int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$editProducts = $conn->query("SELECT * FROM products WHERE products_id = '$edit_id'");
		$proResult= mysqli_fetch_assoc($editProducts);

		//to delete size rows from database
		if(isset($_GET['delete_size'])){
			$deleteSize_id =(int)$_GET['delete_size'];
			$deleteSize_id = sanitize($deleteSize_id);
			$conn->query("DELETE FROM `products_size` WHERE products_size_id = '$deleteSize_id'");
			header("Location: products?edit=".$edit_id);
		}

		if(isset($_GET['delete_image'])){
			$image_url = $_SERVER['DOCUMENT_ROOT'].$proResult['image'];
			unlink($image_url);
			$conn->query("UPDATE products SET image = '' WHERE products_id = '$edit_id'");
			header("Location: products?edit=".$edit_id);
		}
		if(isset($_GET['delete_video'])){
			$video_url = $_SERVER['DOCUMENT_ROOT'].$proResult['video'];
			unlink($image_url);
			$conn->query("UPDATE products SET video = '' WHERE products_id = '$edit_id'");
			header("Location: products?edit=".$edit_id);
		}
		$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$proResult['title']);
		$title = ucwords($title);
		$brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$proResult['brand']);
		$categories = ((isset($_POST['categories']) && $_POST['categories'] != '')?sanitize($_POST['categories']):$proResult['categories']);
		$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$proResult['price']);
		$list_price = ((isset($_POST['list_price']))?sanitize($_POST['list_price']):$proResult['list_price']);
		$discount = ((isset($_POST['discount']))?sanitize($_POST['discount']):$proResult['discount']);
		$description = ((isset($_POST['description']))?sanitize($_POST['description']):$proResult['description']);
		$color = ((isset($_POST['color']))?sanitize($_POST['color']):$proResult['color']);
		$color = rtrim($color,',');
		$saved_image = (($proResult['image'] != '')?$proResult['image']:'');
		$save_video = (($proResult['video'] != '')?$proResult['video']:'');
		$dbpath = $saved_image;
		$videoDbpath = $save_video;
	}
	//to remove the comma at the end of the color
		if(!empty($color)){
			$colorString = sanitize($color);
			$colorString = rtrim($colorString,',');
			$colorString = explode(',',$colorString); 
			$sArray = array();
			//$qArray = array();
			foreach ($colorString as $ss) {
				$s = explode(',', $ss);
				$sArray[] = $s[0];
				//$qArray[] = $s[1];
			}
		}else{
			$colorString = array();
		}

		if($_POST){
			$errors = array();
 			// form vlaidation

			$required =array('title','price','categories');
			foreach ($required as $field) {
				if($_POST[$field] == ''){
					$errors[] = 'All field with astrisk are required.';
					break;
				}
			}
			if(!isset($_GET['edit'])){
				if($_FILES['photo']['name'] == ''){
					$errors[] = 'Product image is empty.';	
				}
			}
			
			//vedio....
			if($_FILES['video']['name'] != ''){
				$max_size = 20971520;
				$nameVideo = $_FILES['video']['name'];
				$video = $_FILES['video'];
				//var_dump($_FILES);
				//var_dump($nameVideo);
				$nameArrayV= explode('.',$nameVideo);
				$fileNameV = $nameArrayV[0];
				$fileExtV = $nameArrayV[1];
				$tmpLocV =  $video['tmp_name'];
				//var_dump($tmpLocV);
				$uploadNameV = md5(microtime()).'.'.$fileExtV;
				$uploadPathV = BASEURL.'video/'.$uploadNameV;
				$videoDbpath = 'video/'.$uploadNameV;
				//select file type
				$videoFileType = strtolower(pathinfo($uploadNameV,PATHINFO_EXTENSION));
				//valid file extentions
				$extension_arr = array('mp4','mkv','mpeg','avi','3pg','mov');
				//check if array exist
				if(!in_array($videoFileType,$extension_arr)){
					$errors[] = 'This is not a video';
			   	}
			   	if(($_FILES['video']['size'] >= $max_size) ||  ($_FILES['video']['size'] == 0)) {
					$errors[] = "File too large. File must be less than 20MB.";
			   	}
			}

			//Image.....
			if($_FILES['photo']['name'] != '' ){
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
				$uploadPath = BASEURL.'img/products/'.$uploadName;
				$dbpath = 'img/products/'.$uploadName;
				$imageFileType = strtolower(pathinfo($uploadName,PATHINFO_EXTENSION));
				if($mimeType != 'image'){
					$errors[] = 'The file must be an image baby.';
				}
				//var_dump($_FILES);
				
				if(!in_array($imageFileType, $allowed)){
					$errors[] = 'The image extension must be a png, jpg, jpeg, or gif.';
				}
				if($fileSize > 1000000){
					$errors[] = 'The image size must be below 1MB.';
				}
				if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
					$errors[]= 'Image extension does not match the file.';
				}
 			}
 			
 			if(!empty($errors)){
				echo display_errors($errors);
			}
			else{
				//upload and insert into database
				if(!empty($_FILES)){
					move_uploaded_file($tmpLoc,$uploadPath);
					move_uploaded_file($tmpLocV,$uploadPathV);
				}
				//to edit
				if(isset($_GET['edit'])){
					$inst="UPDATE `products` SET `title`= '$title',`price`='$price',`list_price`='$list_price',`discount`='$discount',`brand`='$brand',`categories`='$categories',`image`='$dbpath',`video`='$videoDbpath',`description`='$description',`color`='$color' WHERE `products_id`='$edit_id'";
					$conn->query($inst);
						$products_size_id = array();
						$total_rows = count($_POST['size']);
						$checkSize = $conn->query("SELECT * FROM products_size WHERE products_id = '$edit_id'");
						$products_id = array();
						$products_id[] = $edit_id;
						for($i=0; $i < $total_rows; $i++){
							$checkSize_id = mysqli_fetch_array($checkSize);
							$products_size_id[] = $checkSize_id['products_size_id'];
							$products_id[] = $edit_id;
							//for sizes
							if($_POST['size'] != ''){
								$inst = "UPDATE `products_size` SET `size`= '{$_POST['size'][$i]}',`price`='{$_POST['sizePrice'][$i]}',`list_price`='{$_POST['discountPrice'][$i]}',`discount`='{$_POST['discountSize'][$i]}' WHERE `products_size_id`='{$_POST['products_size_id'][$i]}'";
								$conn->query($inst);
							}
						}
	
	
					}else{
						// to insert
						$inst="INSERT INTO `products` (`title`, `price`, `list_price`,`discount`, `brand`, `categories`, `image`,`video`, `description`, `color`) 
						VALUES ('$title', '$price', '$list_price','$discount', '$brand', '$categories', '$dbpath','$videoDbpath', '$description', '$color')";
						$conn->query($inst);
						$latest_id = mysqli_insert_id($conn);
						echo ($latest_id);
						//for sizes....
						$total_rows = count($_POST['size']);
						$products_id = array();
						$discount_size= array();
						for($i=0; $i < $total_rows; $i++){
							$products_id[] = $latest_id; 
							if($_POST['size'] != ''){
								$inst="INSERT INTO `products_size`(`products_id`, `size`, `price`, `list_price`,`discount`) VALUES ('{$products_id[$i]}','{$_POST['size'][$i]}','{$_POST['sizePrice'][$i]}','{$_POST['discountPrice'][$i]}','{$_POST['discountSize'][$i]}')";
								$conn->query($inst);
							}

						}

					}				
   				header("Location: products");
			}
		}
?>
	<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add New A');?> Product</h2><hr>
	<form method="post" action="products?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" enctype="multipart/form-data">
		<div class="form-group col-md-3">
			<label for="title">Title*:</label>
			<input type="text" name="title" class="form-control" value="<?=$title?>">
		</div>
		<div class="form-group col-md-3">
			<label for="brand">brand:</label>
			<select class="form-control" id="brand" name="brand">
				<option value=""<?=(($brand == '')?'Selected':'');?>></option>
				<?php while($b = mysqli_fetch_assoc($brandQuery)) : ?>
					<option value="<?=$b['brand_id'];?>"<?=(($brand == $b['brand_id'])?'Selected':'');?>><?=$b['brand'];?></option>
				<?php endwhile; ?>
			</select>
		</div> 
		<div class="form-group col-md-3">
			<label for="categories">Categories*:</label>
			<select class="form-control" id="categories" name="categories">
				<option value=""<?=(($categories == '')?'Selected':'');?>></option>
				<?php while($category = mysqli_fetch_assoc($categoriesQuery)) : ?>
					<option value="<?=$category['categories_id'];?>"<?=(($categories == $category['categories_id'])?'Selected':'');?>><?=$category['category'];?></option>
				<?php endwhile; ?>
			</select>
		</div> 
		<div class="form-group col-md-3">
			<label for="title">Price*:</label>
			<input type="number" id="price"  name="price" class="form-control" value="<?=$price;?>">
		</div>
		<div class="form-group col-md-3">
			<label for="discount">Discount:</label>
			<input type="number" id="discount" onChange="updateNewPrice();" name="discount" class="form-control" value="<?=$discount;?>">
		</div>
		<div class="form-group col-md-3">
			<label for="list_price">Discount Price:</label>
			<input type="number" id="discount_price" onChange="updateNewPrice();" name="list_price" class="form-control" value="<?=$list_price;?>">
		</div>
		<div class="form-group col-md-3">
			<label>Sizes:</label>
			<button class="btn btn-default form-control" onclick="jQuery('#sizeModal').modal('toggle');return false;">Size</button>
		</div>
		 
		<div class="modal fade" id="sizeModal" tabindex="-1" role="dialog" aria-labelledby="sizeModal" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
		        <h5 class="modal-title" id="sizeModal">Size</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
	      </div>
	      <div class="modal-body">
		  <!-- to edit the sizes -->
		  <?php 
		  ob_start();
		  //$rows = array();
			if(isset($_GET['edit'])) {
				$editSize = $conn->query("SELECT * FROM products_size WHERE products_id = '$edit_id'"); 
				$outPut = '';
				$i=0;
				$resultCount = mysqli_num_rows($editSize);
				if($resultCount > 0){

					while($rows= mysqli_fetch_array($editSize)){
						$productSize = $rows['products_size_id'];
						//echo $productSize;
						echo'<div id="apsection'.$i.'" class="container-fluid apsection">
							<div id="tr" class="tr">
								<div class="form-group col-md-3">
								<label for="size">Size:</label>
								<input type="text" id="size'.$i.'" name="size[]" value="'.$rows['size'].'" class="form-control">
								</div>
								<input type="hidden" id="size'.$i.'" name="products_size_id[]" value="'.$rows['products_size_id'].'" class="form-control">
								<div class="form-group col-md-3">
								<label for="price">Price:</label>
								<input type="number" id="priceSize'.$i.'" name="sizePrice[]" value="'.$rows['price'].'" class="form-control priceSize">
								</div> 
								<div class="form-group col-md-2">
								<label for="discount">discount:</label>
								<input type="number" id="discountSize'.$i.'" name="discountSize[]" value="'.$rows['discount'].'" class="form-control discountSize">
								</div>
								<div class="form-group col-md-3">
								<label for="list_price">Discount Price:</label>
								<input type="number" id ="discount_priceSize'.$i.'"  name="discountPrice[]" value="'.$rows['list_price'].'" class="discount_priceSize form-control">
								</div>
								<div class="col-md-1">remove
								<a href="products?delete_size='.$productSize.'&edit='.$edit_id.'" class="btn btn-sm btn-danger" style="padding:3px; margin-top:9px;"><span class="glyphicon glyphicon-remove"></span></a>&#160;
								</div>
							</div>
						</div>';
						$i++;
						?>
							<script>
								$(document).ready(function() {
									
									var count = <?=$resultCount;?>;
									for(var i = 0; i<= count; i++){	
										$('#apsection'+i).on('change', '#discount_priceSize'+i,function(){					
										var tr = $(this).closest('.tr');
										var discount=tr.find(".discountSize").val();
										var discountPrice = tr.find(".discount_priceSize").val();
										var Price = discountPrice - (discountPrice * discount / 100 );	
										tr.find(".priceSize").val(Price);
										// to change to zero
										var getPrice = $('#discount_priceSize').val();
										if (getPrice != 0) {
											document.getElementById("discount").value = 0;
											document.getElementById("discount_price").value = 0;
										}		
										});

										$("#apsection"+i).on('change', '#discountSize'+i,function(){		
										var tr = $(this).closest('.tr');
										var discount=tr.find(".discountSize").val();
										var discountPrice = tr.find(".discount_priceSize").val();
										var Price = discountPrice - (discountPrice * discount / 100 );	
										tr.find(".priceSize").val(Price);
										// to change to zero
											var getPrice = $('#discountSize').val();
											if (getPrice != 0) {
												document.getElementById("discount").value = 0;
												document.getElementById("discount_price").value = 0;
											}
										});

										$('#apsection'+i).on('change', '#priceSize'+i,function(){
											// to change to zero
											var getPrice = $('.priceSize').val();
											if (getPrice != 0) {
												document.getElementById("discount").value = 0;
												document.getElementById("discount_price").value = 0;
											}	
										});
									}
								});
							
							</script>
						<?php
					}
				}else{
					echo'<a href="addsize?add='.$edit_id.'"class="btn btn-sm btn-success">Add New Size</a>';
				}

			}else{
		?>
			<div id="apsection" class="container-fluid apsection">
				<div class="tr">
					<div class="form-group col-md-3">
					<label for="size">Size:</label>
					<input type="text" id="size" name="size[]" value="" class="form-control">
					</div>
					<div class="form-group col-md-3">
					<label for="price">Price:</label>
					<input type="number" name="sizePrice[]" value="" class="form-control priceSize">
					</div> 
					<div class="form-group col-md-2">
					<label for="discount">discount:</label>
					<input type="number" name="discountSize[]" value="" class="form-control discountSize">
					</div>
					<div class="form-group col-md-3">
					<label for="list_price">Discount Price:</label>
					<input type="number"  name="discountPrice[]" value="" class="discount_priceSize form-control">
					</div>
				</div>
			</div>

		<?php
				}	
		  ?>  
	      </div>
	      <div class="modal-footer">
		  <?php if(!isset($_GET['edit'])) :?>
	        <button type="button" id="btn" class="btn btn-secondary">Add</button>
		  <?php else : ?>
			<a href="addsize?add=<?=$edit_id;?>"class="btn btn-sm btn-success">Add New Size</a> 
		  <?php endif; ?>	
	        <button type="button" class="btn btn-primary" onclick="jQuery('#sizeModal').modal('toggle');return false;">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>
		<div class="form-group col-md-3">
			<label>Color:</label>
			<button class="btn btn-default form-control" onclick="jQuery('#colorModal').modal('toggle');return false;">Color</button>
		</div>
		<div class="form-group col-md-3">
			<label for="color">Color:</label>
			<input type="text" id="color" name="color" class="form-control" value="<?=$color;?>" readonly>
		</div>
		<?php if($saved_image != '') :?>
				<div class=" form-group col-md-6 saved-image">
					<img src="/toniastore/<?=$saved_image;?>" alt="saved image"/>
					<a href="products?delete_image=1&edit=<?=$edit_id;?>" class="btn btn-sm btn-danger">Delete Image</a>
				</div>
		<?php else : ?>
				<div class="form-group col-md-6">
					<label for="photo">Product Image*:</label>
					<input type="file" id="photo" name="photo" class="form-control">
				</div>
		<?php endif; ?>
		<!-- for video -->
		<?php if($save_video != '')  :?>
			<div class=" form-group col-md-6 saved-image ">
				<video src="/toniastore/<?=$save_video;?>" controls width='320px' height='200px' poster="/toniastore/<?=$saved_image;?>" ></video>
				<a href="products?delete_video=1&edit=<?=$edit_id;?>" class="btn btn-sm btn-danger">Delete video</a>
			</div><br />
		<?php else : ?>	
			<div class="form-group col-md-6">
				<label for="video">Video:</label>
				<input type="file" id="video" name="video" class="form-control">
			</div>
		<?php endif; ?>
		<div class="form-group col-md-6">
			<label for="description">Description:</label>
			<textarea id="description" name="description" class="form-control" rows="6"><?=$description;?></textarea>
		</div>
		<div class="form-group pull-right add-products">
			<a href="products" class="btn btn-default">Cancel</a>
			<input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product" class="btn  btn-success">
			<div class="clear-fix"></div>
		</div>
		</div>
	</form>
	<!-- Modal for our color -->
	<div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorModal" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
		        <h5 class="modal-title" id="colorModal">Color</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
	      </div>
	      <div class="modal-body">
		      	<div class="container-fluid">
			      	<?php for($i=1; $i <= 12; $i++) : ?>
			      		<div class="form-group col-md-4">
			      			<label for="color<?=$i;?>">Color:</label>
			      			<input type="text" id="color<?=$i;?>" name="color<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control">
			      		</div>
			      		<!-- quantity field is to be there...-->
			      		
			      	<?php endfor; ?>
		      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" onclick="updateColor();jQuery('#colorModal').modal('toggle');return false;">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>
	</div>
<?php
}
else{
// get from url special product
if(isset($_GET['special'])){
	$id= (int)$_GET['id'];
	$special = (int)$_GET['special'];
	$specialsql="UPDATE products SET special = '$special' WHERE products_id = '$id'";
	$conn->query($specialsql);
	header("location: products");
}
?>
	<div class="container-fluid">
		<h2 class="text-center">Products</h2><br>
		<form action="products" method="post">
		<input type="text" name="search" class="form-control form-group" placeholder="search">
		</form>
		<hr>
		<div class="row">
		<div class="up-btn">
			<a href="products" class="btn btn-primary pull-left">Back</a>
			<a href="products?add=1" class="btn btn-primary pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>
		</div>	
			<table class="table table-bordered table-striped">
				<thead>
					<th></th><th>Product</th><th>Price</th><th>Category</th><th>Special</th><th>Sold</th>
				</thead>
				<tbody>
					<?php while($product = mysqli_fetch_assoc($presult)) : 
						$categories = $product['categories'];
						$catsql = "SELECT * FROM categories WHERE categories_id ='$categories'";
						$catresult = $conn->query($catsql);
						$cat = mysqli_fetch_assoc($catresult);
					?>
					<tr>
						<td><a href="products?edit=<?=$product['products_id'];?>" class="btn btn-xs btn-primary" style="padding:3px;"><span class="glyphicon glyphicon-pencil"></span></a>&#160;
							<a href="products?delete=<?=$product['products_id'];?>" class="btn btn-xs btn-danger" style="padding:3px;"><span class="glyphicon glyphicon-remove"></span></a>&#160;
						</td>
						<td><?=$product['title'];?></td>
						<td><?=money($product['price']);?></td>
						<td>
							<?=$cat['category'];?>
						</td>
						<td><a href="products?special=<?=(($product['special'] == 0 )?'1':'0');?>&id=<?=$product['products_id'];?>"class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?=(($product['special'] == 1)?'minus':'plus');?>"></span></a>
							&#160; <?=(($product['special'] == 1)?'Special Product':'');?>
						</td>
						<td>0</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
	</div>
	<div class="clear"></div>	
	<?php }	?>
	<!-- update function for color-->
<script>
	$(document).ready(function() {

		var apsection   = $("#apsection"); //Input boxes wrapper ID
		var btn       = $("#btn"); //Add button ID

		var x = apsection.length; //initlal text box count
		var FieldCount=1; //to keep track of text box added

		$(btn).click(function (e)  //on add input button click $resultCount
		{
			
			FieldCount++; //text box added increment
			//add input box
			$(apsection).append('<div class="tr"><div class="form-group col-md-3"<label for="size">Size:</label><input type="text" id="size" name="size[]" value="" class="form-control"></div><div class="form-group col-md-3"><label for="price">Price:</label><input type="number" name="sizePrice[]" value="" class="form-control priceSize"></div> <div class="form-group col-md-2"><label for="discount">discount:</label><input type="number" name="discountSize[]" value="" class="form-control discountSize"></div><div class="form-group col-md-3"><label for="list_price">Discount Price:</label><input type="number"  name="discountPrice[]" value="" class="discount_priceSize form-control"></div><div class="col-md-1 removeclass"><i class="btn btn-md btn-danger" style="padding:3px; margin-top:25px;"><span class="glyphicon glyphicon-remove"></span></i>&#160;</div></div>');
			x++; //text box increment
			return false;
		});

			$("body").on("click",".removeclass", function(e){ //user click on remove text
					if( x > 1 ) {
							$(this).parent('div').remove(); //remove text box
							x--; //decrement textbox
					}
			return false;
			}) 

	});

 	$("#apsection").on('change', '.discountSize',function(){
		// to change to zero
		var getPrice = $('.discountSize').val();
		if (getPrice != 0) {
			document.getElementById("discount").value = 0;
			document.getElementById("discount_price").value = 0;
		}
     var tr = $(this).closest('.tr');
	 var discount=tr.find(".discountSize").val();
		var discountPrice = tr.find(".discount_priceSize").val();
		var Price = discountPrice - (discountPrice * discount / 100 );	
		tr.find(".priceSize").val(Price);

	});

  $("#apsection").on('change', '.discount_priceSize',function(){
	  	// to change to zero
		var getPrice = $('.discount_priceSize').val();
		if (getPrice != 0) {
			document.getElementById("discount").value = 0;
			document.getElementById("discount_price").value = 0;
		}		
		var tr = $(this).closest('.tr');
		var discount=tr.find(".discountSize").val();
		var discountPrice = tr.find(".discount_priceSize").val();
		var Price = discountPrice - (discountPrice * discount / 100 );	
		tr.find(".priceSize").val(Price);
	});
	$("#apsection").on('change', '.priceSize',function(){
		// to change to zero
		var getPrice = $('.priceSize').val();
		if (getPrice != 0) {
			document.getElementById("discount").value = 0;
			document.getElementById("discount_price").value = 0;
		}
	});
//for update size
function updateNewPrice() {
	var discountPrice = document.getElementById("discount_price").value;
	var discount = document.getElementById("discount").value;
	var price = discountPrice - (discountPrice * discount / 100 );
	document.getElementById("price").value = price;
}
function updateColor(){
	var colorString='';
	for( var i=1; i <= 12; i++){
		if (jQuery('#color'+i).val() != ''){
			colorString += jQuery('#color'+i).val()+',';
		}
	}
	jQuery('#color').val(colorString);
}

</script> 
	
	