<?php
	include 'includes/header.php';
    if (!is_logged_in()) {
		login_error_redirect();
	}

    include 'includes/navigation.php';
    $errors= array();
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
	$categories = ((isset($_POST['categories']) && $_POST['categories'] != '')?sanitize($_POST['categories']):'');
    //to get category form database
    $categoriesQuery = $conn->query("SELECT * FROM categories ORDER BY category");
    if(isset($_POST['sumbit']) && $_FILES['video']['name'] != ''){
       $max_size = 20971520;
       $name = $_FILES['video']['name'];
       $video = $_FILES['video'];
       var_dump($_FILES);
       var_dump($name);
       $nameArray= explode('.',$name);
       $fileName = $nameArray[0];
       $fileExt = $nameArray[1];
       $tmpLoc =  $video['tmp_name'];
       var_dump($tmpLoc);
       $uploadName = md5(microtime()).'.'.$fileExt;
       $uploadPath = BASEURL.'video/'.$uploadName;
       $dbpath = 'video/'.$uploadName;
       //select file type
       $videoFileType = strtolower(pathinfo($uploadName,PATHINFO_EXTENSION));
       //valid file extentions
       $extension_arr = array('mp4','mkv','mpeg','avi','3pg','mov');

       //check if array exist
       if(!in_array($videoFileType,$extension_arr)){
            $errors[] = 'This is not a video';
       }
       if(($_FILES['video']['size'] >= $max_size) ||  ($_FILES['video']['size'] == 0)) {
            $errors[] = "File too large. File must be less than 20MB.";
       }
        var_dump($_FILES['video']['size']);
       if($name == '' || $title == '' || $categories == '' || $price == ''){
            $errors[] = 'some field are empty';
       }

       if(!empty($errors)){
            echo display_errors($errors);
       }
       else{
            if(!empty($_FILES)){
                move_uploaded_file($tmpLoc,$uploadPath);
            }

          /* $insertSql = "INSERT INTO `video`( `name`,`price`,`category`,`location`) VALUES ('$title','$price','$categories','$dbpath')";
            $conn->query($insertSql);
            header('location: video.php');
           $_SESSION['success_flash'] = "Uploaded";*/
            
        }
    }
?>
<div class="container">
    <div class="row">
        <h2 class="text-center">VIDEO</h2>
        <br />
        <div class="text-center">
            <?=$flash;?>
            <div class="col content">
                <form class="form-inline" method="post" action="video" enctype="multipart/form-data">
                <input type="text" name="title" class="form-control" placeholder="Title" value="<?=$title;?>">
                <input type="number" name="price" class="form-control" placeholder="price" value="<?=$price;?>"> 
                    <select class="form-control" id="categories" name="categories">
                        <option value=""><?=(($categories == '')?'Select':'');?></option>
                        <?php while($category = mysqli_fetch_assoc($categoriesQuery)) : ?>
                            <option value="<?=$category['categories_id'];?>"<?=(($categories == $category['categories_id'])?'Selected':'');?>><?=$category['category'];?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="file" id="video" name="video" class="form-control" />
                    <button name="sumbit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>