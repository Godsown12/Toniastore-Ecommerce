<?php
 include "includes/header.php";
if(isset($_GET['cat'])){
	$cat_id = (int)sanitize($_GET['cat']);
}else{
	$cat_id = '';
}
//$sql = $conn->query("SELECT * FROM products WHERE deleted = 0 AND categories= '$cat_id'");
$checkVideo = $conn->query("SELECT * FROM products WHERE deleted = 0 AND categories= '$cat_id' AND video !=''");
$checkResult = mysqli_fetch_assoc($checkVideo);

///PAGEING
if (isset($_GET['page_no']) && $_GET['page_no'] != '') {
	$page_no = $_GET['page_no'];
}else{
	$page_no = 1;
}
//set the total records per page
$total_records_per_page = 50;
// set the offset and next page and previous page and adjecent
$offset = ($page_no - 1)* $total_records_per_page;
$next_page = $page_no + 1;
$previous_page = $page_no - 1;
$adjacents = "2";
//to get the number of records form the database....
$sql_count = $conn->query("SELECT COUNT(*) AS total_records FROM products WHERE deleted = 0 AND categories= '$cat_id'");
$result_count = mysqli_fetch_assoc($sql_count);
$total_records = $result_count['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total pages minus 1
$sql = $conn->query("SELECT * FROM products WHERE deleted = 0 AND categories= '$cat_id'  LIMIT  $offset, $total_records_per_page");
// to get size from size table


?><!-- the main body-->
	<div class="row">
		<div class="col content ">
			<?php
				$sql2= " SELECT * FROM categories WHERE categories_id = '$cat_id'";
				$cati = $conn->query($sql2);
				$cate = mysqli_fetch_assoc($cati); 
			?>
			<?php include 'includes/widgets/cart_update.php';?>
			<h1 id="title"><?=$cate['category'];?> Section</h1>
			<?=$flash;?>
				<div id="products-container">
					<div class="row">
					 <!--products video -->
					<script>
						$(document).ready(function(){
							$('#video').click(function(e){
								window.location=$(this).find("a").attr("href"); 
								return false;
							})	;
						});

					</script>
					<?php if($checkResult > 1 ) : ?>
						<!--<div id="video" class="col-xs-3 products">
							<span class="spa">&nbsp;</span>
							<h6 style="text-align:center;">clips</h6>
								<img class="img-responsive image-resize" src="img/video/video1.jpg">
							<p class="list-price text-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
							<p class="price">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>  
							<a href="clip?video=1&cat=<?=$cat_id;?>"></a>
						</div>-->
					<?php endif; ?>	
					<?php while ($products = mysqli_fetch_assoc($sql)) : ?>
					<?php
					$sizeCount = 0;
					$sizeDiscount = 0;
						$products_id = $products['products_id'];
						$sizeTable = $conn->query("SELECT DISTINCT  * FROM products_size WHERE products_id = '$products_id' GROUP BY products_id");
						$sizeCount = mysqli_num_rows($sizeTable);
						$size = mysqli_fetch_assoc($sizeTable);
						$sizePrice = $size['price'];
						$sizeDiscount = $size['discount'];
						$sizeList_price = $size['list_price'];
						
					?>
						<div onclick="detailsmodel(<?= $products['products_id'];?>)" class="col-xs-3 products ">
							<?php if($sizeCount==0) :?>
								<?php if($products['discount'] != 0 ) : ?>
									<span>-<?=$products['discount'];?>%</span>
								<?php else  :?>
									<span class="spa"> &nbsp;</span>	
								<?php endif; ?>	
								<h6><?= $products['title'];?></h6>
								<?php if($products['video'] != ''): ?>
									<video class="embed-responsive-item" src="<?=$products['video'];?>" controls poster="<?= $products['image'];?>"type="video/mp4"></video>
								<?php else :?>
									<img class="img-responsive image-resize" src="<?= $products['image'];?>">
								<?php endif;?>	
								<?php if ($products['list_price'] == 0.00 || $products['discount'] == 0 ):?>
									<p class="list-price text-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
								<?php else :?>
									<p class="list-price text-danger">Price: <s><?=money($products['list_price']);?></s></p>
								<?php endif; ?>
								<p class="price"><?=money($products['price']);?></p> 
							<?php else :?> 
								<?php if($sizeDiscount != 0 || $products['discount'] != 0) : ?>
									<span><?=(($sizeDiscount == 0)?$products['discount']:$sizeDiscount);?>%</span>
								<?php else  :?>
									<span class="spa"> &nbsp;</span>	
								<?php endif; ?>	
								<h6><?= $products['title'];?></h6>
								<?php if($products['video'] != ''): ?>
									<video class="embed-responsive-item" src="<?=$products['video'];?>" controls poster="<?= $products['image'];?>" type="video/mp4"></video>
								<?php else :?>
									<img class="img-responsive image-resize" src="<?= $products['image'];?>">
								<?php endif;?>	
								<?php if ($sizeList_price == 0.00 AND $products['list_price'] == 0.00 ) :?>
									<p class="list-price text-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
								<?php else :?>
								<?php if($sizeDiscount != 0 || $products['discount'] != 0) :?>
									<p class="list-price text-danger">Price: <s><?=(($sizeList_price == 0)?money($products['list_price']):money($sizeList_price));?></s></p>
								<?php else :?>
									<p class="list-price text-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
								<?php endif;?>
								<?php endif; ?>
								<?php if($sizePrice != 0.00):?>
								<p class="price"><?=money($sizePrice);?></p>
								<?php else :?>
								<p class="price"><?=money($products['price']);?></p>
								<?php endif;?>
							<?php endif;?>
						</div>
					<?php endwhile;?><div class="clear"></div>
					</div>
				</div>
		</div>
		<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
       	 <strong>Page <?=$page_no." of ".$total_no_of_pages; ?></strong>
        </div>
        <div>
			<ul class="pagination">
				<?php if($page_no > 1){
				echo "<li><a href='?cat=$cat_id&page_no=1'>First Page</a></li>";
				} ?>
					
				<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
				<a <?php if($page_no > 1){
				echo "href='?cat=$cat_id&page_no=$previous_page'";
				} ?>>Previous</a>
				</li>
				<?php if ($total_no_of_pages <= 100){   
							for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
								if ($counter == $page_no) {
									echo "<li class='active'><a>$counter</a></li>"; 
								}else{
									echo "<li><a href='?cat=$cat_id&page_no=$counter'>$counter</a></li>";
								}
							}
						}   
				?>
				<li <?php if($page_no >= $total_no_of_pages){
				echo "class='disabled'";
				} ?>>
				<a <?php if($page_no < $total_no_of_pages) {
				echo "href='?cat=$cat_id&page_no=$next_page'";
				} ?>>Next</a>
				</li>
				
				<?php if($page_no < $total_no_of_pages){
				echo "<li><a href='?cat=$cat_id&page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
				} ?>
			</ul>
		</div>	
		<?php include 'includes/widgets/recent.php';?>
		<div class="clear"></div>
	</div>
	
<?php include 'includes/footer.php';?>


