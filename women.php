<?php
 include "includes/header.php";
include 'includes/rightside.php';
$sql="SELECT * FROM products WHERE Special= 1";
$women= $conn->query($sql);
?>
	<!-- the main body-->
	<div class="col-md-8 ">
		<div class="row">
			<h2 class="text-center" >WOMEN CLOTHES</h2>
			<div id="products-container">
				<?php while ($products = mysqli_fetch_assoc($women)) : ?>
					<div class="products">
						<h4><?= $products['title']?></h4>
						<img src="<?= $products['image']?>">
						<p class="list-price text-danger">List Price: <s>#<?= $products['list_price']?></s></p>
						<p class="price">Our Price: #<?= $products['price']?></p>    
						<button type="button" class="btn btn-sm " onclick="detailsmodel(<?= $products['products_id'];?>)">View</button>                        
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
	
<?php
include 'includes/leftside.php';

 include "includes/footer.php"; ?>