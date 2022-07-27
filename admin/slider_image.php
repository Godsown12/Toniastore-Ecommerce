<?php
    include 'includes/header.php';
    if (!is_logged_in()) {
            login_error_redirect();
    }
    include 'includes/navigation.php';
    $sliderSql= $conn->query("SELECT * FROM `slider_images`");
    $errors = array();
    $dbpath = '';
    $photo = ((isset($_POST['photo']) && $_POST['photo'] != '')?sanitize($_POST['photo']):'');
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');

    if(isset($_GET['edit'])){
		$edit_id=(int)$_GET['edit'];
		$edit_id= sanitize($edit_id);
        $esql= "SELECT * FROM slider_images WHERE slider_images_id= '$edit_id'";
		$eresult= $conn->query($esql);
		$editslider = mysqli_fetch_assoc($eresult);
        $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$editslider['description']);
        $photo = ((isset($_POST['photo']) && $_POST['photo'] != '')?sanitize($_POST['photo']):$editslider['images']);
    } 
    // to delete the image from slider
    if(isset($_GET['delete'])){
        $id= (int)$_GET['delete'];
        $conn->query("DELETE FROM `slider_images` WHERE slider_images_id = '$id'");
        header("Location: slider_image");
    }
       

    //validate to the form
    if(isset($_POST['upload'])){

        if(!isset($_GET['edit'])){
			if($_FILES['photo']['name'] == ''){
				$errors[] = 'categories image is empty.';	
			}
		}else{
			$photo = $editslider['images'];
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
            $uploadPath = BASEURL.'img/slider/'.$uploadName;
            $dbpath = 'img/slider/'.$uploadName;
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
            echo display_errors($errors);
        }
        else{
            //upload and insert into database
            if(!empty($_FILES)){
                move_uploaded_file($tmpLoc,$uploadPath);
            }
            $insertSql = "INSERT INTO `slider_images`(`images`,`description`) VALUES ('$dbpath','$description')";
            //update into database
			if(isset($_GET['edit'])){

				if(!empty($dbpath)){
					$insertSql="UPDATE `slider_images` SET `images`= '$dbpath',`description`= '$description' WHERE slider_images_id = '$edit_id'";
				}else{
					$insertSql="UPDATE `slider_images` SET `description`= '$description' WHERE slider_images_id = '$edit_id'";
				}	
			}
            $conn->query($insertSql);
            header("Location: slider_image");
        }
    }
?>
<div class= "container-fluid wrapper">
    <div class="row">
        <div class="col content">
            <h1 class="text-center">Slider Image</h1>
            <div class="text-center">
                <h4>Add slider picture here</h4>
                <form class="form-inline form-slider" action="slider_image<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post"enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file"id="photo" name="photo" class="form-control">
                        <input type="text" name="description" class="form-control" placeholder="description" value="<?=$description;?>">
                        <button type="submit" name="upload" class="btn btn-md btn-primary">Enter</button>
                    </div>
                </form>
            </div>
            <hr>
            <?php while($slider = mysqli_fetch_assoc($sliderSql)) : ?>
            <div class="col-md-4">
                <img class="img-responsive image-resize"  src="/toniastore/<?=$slider['images'];?>" alt="<?=$slider['slider_images_id'];?>"/>
                <p><?=$slider['description'];?></p>
                <a href="slider_image?edit=<?=$slider['slider_images_id'];?>" class="btn btn-md btn-success pull-left">Edit</a>
                <a href="slider_image?delete=<?=$slider['slider_images_id'];?>" class="btn btn-md btn-danger pull-right">Delete</a>
            </div>
            <?php endwhile;?>
        </div>
    </div>
<div>
<?php    
include 'includes/footer.php';
?>

