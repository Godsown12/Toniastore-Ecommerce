<?php
 include 'includes/header.php';
	 if($cart_data != ''){
	 	$i = 1;
	 	$sub_total = 0;
	 	$item_count = 0;
	 }
?>

	<div class="row">
		<div class="col content">
			<h1 id="title">Shopping Cart</h1><hr>
			<?=$flash;?>
			<?php if($cart_data == '') : ?>
				<div id="empty-cart">
					<div class="p">
						<p> Your shopping cart is empty!</p>
					</div>
					<div class="img">
						<img src="img/logo.jpg" class="img-responsive">
					</div>
					<div class="add-link">
						<a href="index.php">Add items to cart</a>
					</div>
					<div class="clear"></div>
				</div>
			<?php else : ?>
			<div class="cart-table">
				<table>
					<thead>
						<tr>
							<th class="sw"></th>
							<th  class="description">Description</th>
							<th  class="sw">Price</th>
							<th  class="sw">Quantity</th>
							<th  class="sw">Total</th>
							
						</tr>	
					</thead>
					<tbody>
						<?php 
							foreach ($cart_data as $item) {
								$product_id = $item['id'];
								$Size_id = $item['size'];
								$productQ = $conn->query("SELECT * FROM products WHERE products_id = '{$product_id}'");
								$product = mysqli_fetch_assoc($productQ);
								$sizeQ = $conn->query("SELECT * FROM `products_size` WHERE `products_size_id` = '{$Size_id}'");
								$size = mysqli_fetch_assoc($sizeQ);
								$sArray = explode(',', $product['color']);
								foreach ($sArray as $colorString) {
									$s = explode(':', $colorString);
									$s[0] = $item['color'];
								}

								if($product['products_id'] == $product_id){
						?>
								<tr>
									<td class="sw pic"><img src="<?= $product['image']?>" alt="<?= $product['products_id']?>" class="img-responsive"></td>
									<td class="description" style="font-variant:small-caps;">
										<p> item: &#160;<b><?=$product['title'];?></b></p>
										<p><?=(($product['description'] == '')?'':'Des: '.$product['description']);?></p>
										<p style="font-variant:small-caps;"><?=(($item['color'] == '')?'':'Color: ' .$item['color']);?></p>
										<p style="font-variant:small-caps;"><?=(($item['size'] == '')?'':'Size: ' .$size['size']);?></p>
										<p><button style="margin-top: 15px;" class="btn btn-xs btn-danger" onclick="update_cart('deleted','<?=$item['row'];?>','<?=$item['id'];?>','<?=$item['color'];?>','<?=$item['size'];?>');"><i class="far fa-trash-alt"></i></button></p>
										<div>
										<p><span id="quantity_update_errors_<?=$item['row'];?>" class="bg-danger" ></span></p>
										<form >
											<input type="number" onchange="update_cart('update_quantity','<?=$item['row'];?>','<?=$item['id'];?>','<?=$item['color'];?>','<?=$item['size'];?>');" id="quantity_<?=$item['row'];?>" value="<?=$item['quantity'];?>"  class='form-control' min='1' />
											
										</form>	
										<button class="btn btn-default" onclick="update_cart('update_quantity','<?=$item['row'];?>','<?=$item['id'];?>','<?=$item['color'];?>','<?=$item['size'];?>');">Update</button>
										</div>
									</td>
									<td class="sw">
										<?php
											if($item['size'] != ''){
												echo (($size['price'] != 0 )?money($size['price']):money($product['price']));
											}else{
												echo money($product['price']);
											}
										?>
									</td>
									<td class="sw">
											<?=$item['quantity'];?>
									</td>		
									<td class="sw">
										<?php if($item['size'] != ''): ?>
											<?=(($size['price'] != 0)?money($item['quantity'] * $size['price']):money($item['quantity'] * $product['price']));?>
										<?php else : ?>
											<?=money($item['quantity'] * $product['price']);?>
										<?php endif;?>
									</td>
									
								</tr>
							<?php 
									$i++;
									$item_count += $item['quantity'];
									if($item['size'] != ''){
										$sub_total += (($size['price'] != 0)?$size['price'] * $item['quantity'] :$product['price'] * $item['quantity']);
									}else{
										$sub_total += $product['price'] * $item['quantity'];
									}
								}
							}
									$tax = TAXRATE + $sub_total;
									//$tax = number_format($tax,2); 
									$grand_total = $tax; 
							?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<a href="javascript:history.go(-1)" title="Return to the previous page" class="btn btn-default">Continue Shopping</a>
							</td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<button class="btn btn-default b" onclick="update_cart('clearCart','<?=$item['row'];?>','<?=$item['id'];?>','<?=$item['color'];?>','<?=$item['size'];?>');">Clear Shopping Cart</button>
							</td>
						</tr>
					</tfoot>
				</table>
				<div class="row ">
					<div class="col-md-12 under-table">
						<a href="javascript:history.go(-1)" title="Return to the previous page" class="btn btn-default b">Continue Shopping</a>
						<button class="btn btn-default b" onclick="update_cart('clearCart','<?=$item['row'];?>','<?=$item['id'];?>','<?=$item['color'];?>','<?=$item['size'];?>');">Clear Shopping Cart</button>
					</div>
				</div>
			</div>
				<br>
				<div class="row">
					<div class="col-md-4 amount-table">
						<p>Quantity <span><?=$item_count;?></span></p>
						<p>Sub_Total<span><?=money($sub_total);?></span></p>
						<p>Total <span ><?=money($grand_total);?></span></p><hr>
						<a href="checkout" class="btn btn-default checkout-button"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> checkout >></a><hr>
						<p>Delivery fee is on checkout page.</p>
					</div>
					<div class="clear"></div>
				</div>	
			<?php endif ?>
			<?php include 'includes/widgets/recent.php';?>
		</div>
	</div>
<?php include 'includes/footer.php'; ?>