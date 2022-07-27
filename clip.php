<?php
include "includes/header.php";
if(isset($_GET['cat'])){
    $cat_id = (int)sanitize($_GET['cat']);
}
$sql = $conn->query("SELECT * FROM products WHERE deleted = 0 AND categories= '$cat_id' AND video !=''");

?>

<div class="row">
    <?php include 'includes/widgets/cart_update.php';?>
    <h1 id="title">Clips</h1>
			<?=$flash;?>
    <div class="video">
        <div class="row">
            <?php while($products = mysqli_fetch_assoc($sql)) : ?>
                <div onclick="detailsmodel(<?=$products['products_id'];?>)" class="col-sm-3 products-video">
                     <?php if($products['discount'] != 0) : ?>
						<span>-<?=$products['discount'];?>%</span>
					<?php else  :?>
						<span class="spa"> &nbsp;</span>	
					<?php endif; ?>	
                    <h4><?=$products['title'];?></h4>
                    <video src="<?=$products['video'];?>" controls width="100" height="200" poster="<?= $products['image'];?>"></video>
                    <?php if ($products['list_price'] == 0.00) : ?>
                        <p></p><br>
                    <?php else :?>
                        <p class="list-price text-danger"> <s><?= money($products['list_price']);?></s></p>
                    <?php endif; ?>
                    <p class="price"><?= money($products['price']);?></p>    
        
                </div>
            <?php endwhile; ?>    
        </div>
    </div>
    <div class="clear"></div>
    <?php include 'includes/widgets/recent.php';?>
    <div class="clear"></div> 
</div>


<?php include "includes/footer.php";