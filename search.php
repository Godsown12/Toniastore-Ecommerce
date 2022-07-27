<?php
 include "includes/header.php";
 
$search =((isset($_POST['search']) && $_POST['search'] != '' )?sanitize($_POST['search']):'');

if($search != ''){	
	$sql= "SELECT p.products_id, p.title, p.price, p.image, p.list_price, b.brand
	FROM products p
	LEFT JOIN brand b ON p.brand = b.brand_id
	WHERE p.deleted = 0 AND p.title LIKE '%$search%' OR b.brand LIKE '%$search%'";
}else{
	header("location: index");
}
$productQ = $conn->query($sql);
$product_count = mysqli_num_rows($productQ);
//var_dump($product_count); 
?>
	<!-- the main body-->
	<div class="row">
		<div class="col content ">
		<?php include 'includes/widgets/cart_update.php';?>
            <h1 id="title">ToniaStore</h1>
			<?=$flash;?>
			<div id="products-container">
				<div class="row">
				<?php if($product_count != 0 ) : ?>
				<?php while ($products = mysqli_fetch_assoc($productQ)) :?>
					<div class=" col-xs-3 products">
						<h4><?= $products['title'];?></h4>
							<img class="img-responsive image-resize" src="<?= $products['image'];?>">
						<?php if ($products['list_price'] == 0.00) : ?>
							<p class="list-price text-danger">&nbsp;&nbsp;</p>
						<?php else :?>
							<p class="list-price text-danger">List Price: <s><?= money($products['list_price']);?></s></p>
						<?php endif; ?>
						<p class="price">Our Price: <?= money($products['price']);?></p>    
						<button type="button" class="btn btn-sm " onclick="detailsmodel(<?= $products['products_id'];?>)">View</button>
					</div>
				<?php endwhile;?>	
				<?php else : ?>
					<div>
					<h2 class="text-center">Product Not Found</h2>
					</div>
				<?php endif; ?>	
					
				</div>
			</div>
			<?php include 'includes/widgets/recent.php';?>
		</div>
	</div>
	
<?php include 'includes/footer.php';

