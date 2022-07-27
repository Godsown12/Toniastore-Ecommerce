<?php  
	include 'includes/header.php';
	$sql = "SELECT * FROM categories WHERE parent = 0";
	$pquery = $conn->query($sql);
	$sqlp = "SELECT * FROM products WHERE deleted = 0 AND special = 1";
	$Special_products= $conn->query($sqlp);
	$sliderSql= $conn->query("SELECT * FROM slider_images");
?>
<div class="container">
<?php include 'includes/widgets/cart_update.php';?>
<?=$flash;?>
<!--Camera Slide-->
	<div class="camera_wrap">
		<?php while($slider = mysqli_fetch_assoc($sliderSql)) : ?>
		<div data-src="<?=$slider['images'];?>">
			<img src="<?=$slider['images'];?>" class="img-responsive" alt="Toniastore">
			<div class="camera_caption">
				<p><?=$slider['description'];?></p>
			</div>
		</div>
		<?php endwhile;?>
	</div>   
<!--------Camera Slide End-->
<!--- to mobile silder-->
<?php include 'includes/mobile-slider.php'; ?>
	<div class="main-content">
		<div id="categorise-boxes">
			<?php while($parent = mysqli_fetch_assoc($pquery)) : ?>
				<div id="categorise-type" class="categorise-type" style="background-image: url(<?=$parent['background_img'];?>);">
				<p><a href="category?cat=<?=$parent['categories_id'];?>"><?php echo $parent['category']; ?></a></p>
				</div>
			<?php endwhile; ?>
		</div>
		<div class="clear"></div>
		<div class="billboard1">
			<div class="row">
				<div class="col-sm-12 bill1">
					<img src="img/flat.gif" class="img-responsive" alt="toniastore_straightener">
				</div>
			</div>
		</div>
		<!-- cheap hair product -->
		<div class="cheap-hair">
			<h4>Our Most Cheap Hairs </h4>
			<?php include 'includes/widgets/cheap.php';?>
		</div>
		<div class="billboard1">
			<div class="row">
				<div class="col-sm-6">
					<img src="img/h2.png" class="img-responsive" alt="">
				</div>
				<div class="col-sm-6">
				<img src="img/2pcs.gif" class="img-responsive" alt="">
				</div>
			</div>
		</div>
		<div id="special_products">
			<h4>Features Produts</h4>
			<div class="products-wrapper">
				<?php while ($products = mysqli_fetch_assoc($Special_products)) : ?>
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
						<div onclick="detailsmodel(<?= $products['products_id'];?>)" class="col-xs-3 products">
							<?php if($sizeCount==0) :?>
								<?php if($products['discount'] != 0 ) : ?>
									<span>-<?=$products['discount'];?>%</span>
								<?php else  :?>
									<span class="spa"> &nbsp;</span>	
								<?php endif; ?>	
								<h6><?= $products['title'];?></h6>
									<img class="img-responsive image-resize" src="<?= $products['image'];?>">
								<?php if ($products['list_price'] == 0.00 || $products['discount'] == 0):?>
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
									<img class="img-responsive image-resize" src="<?= $products['image'];?>">
								<?php if ($sizeList_price == 0.00 AND $products['list_price'] == 0.00 || $products['discount'] == 0 ) :?>
									<p class="list-price text-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
								<?php else :?>
									<p class="list-price text-danger">Price: <s><?=(($sizeList_price == 0)?money($products['list_price']):money($sizeList_price));?></s></p>
								<?php endif; ?>
								<?php if($sizePrice != 0.00):?>
								<p class="price"><?=money($sizePrice);?></p>
								<?php else :?>
								<p class="price"><?=money($products['price']);?></p>
								<?php endif;?>
							<?php endif;?>
						</div>
				<?php endwhile; ?>	
				<div class="clear"></div> 	
			</div>
		</div>
		<div class="billboard1">
			<div class="row">
				<div class="col-sm-6">
					<img src="img/bill2.gif" class="img-responsive" alt="">
				</div>
				<div class="col-sm-6">
				<img src="img/u.gif" class="img-responsive" alt="">
				</div>
			</div>
		</div>
		<div class=" top-promo">
			<h4>Hot Promo!</h4>
			<?php include 'includes/widgets/hot-promo.php';?>
		</div>
		<div class="colection">
			<h4>Our Collections!</h4>
			<div class="container">
				<div class="row">
					<ul id="rcbrand1">
						<li>
						<img src="img/brands/dye-brush.png" /></li>
						<li>
						<img src="img/brands/single-logo.png" /></li>
						<li>
						<img src="img/brands/dryer.png" /></li>
						<li>
						<img src="img/brands/silky-straight.png" /></li>
						<li>
						<img src="img/brands/cloths3.png" /></li>
						<li>
						<img src="img/brands/kinky-curly.png" /></li>
						<li>
						<img src="img/brands/cloths2.png" /></li>	
					</ul>
					<ul id="rcbrand2">
						<li>
						<img src="img/brands/virgin.png" /></li>
						<li>
						<img src="img/brands/cloths.png" /></li>
						<li>
						<img src="img/brands/straightener.png" /></li>
						<li>
						<img src="img/brands/curly.png" /></li>
						<li>
						<img src="img/brands/cloths1.png" /></li>
						<li>
						<img src="img/brands/omber.png" /></li>
					</ul>
				</div>
			</div>
		</div>
		<div class=" top-promo">
			<h4>Top Promo</h4>
			<?php include 'includes/widgets/top-promo.php';?>
		</div>
		<div class="billboard1">
			<div class="row">
				<div class="col-sm-6">
					<img src="img/h1.gif" class="img-responsive" alt="">
				</div>
				<div class="col-sm-6">
				<img src="img/h.png" class="img-responsive" alt="">
				</div>
			</div>
		</div>
		<?php include 'includes/widgets/recent.php';?>
		<div class="clear"></div> 
	</div>
</div>
</body>
</html>
<!--code to import bootsrap javascript-->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/logo-brand.js"></script>
<?php include 'includes/footer.php';  ?>
