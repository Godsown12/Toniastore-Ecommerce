<?php
	require_once '../core/init.php';
	$products_id = sanitize($_POST['products_id']);
	$products_id = (int)$products_id;
	//$size_id =  ((isset($_POST['size']) && $_POST['size'] != '')?sanitize($_POST['size']):'');
	//$size_id = (int)$size_id;
	$sql="SELECT * FROM products WHERE products_id = '$products_id'";
	$result= $conn->query($sql);
	$products = mysqli_fetch_assoc($result);
	$brand_id = $products['brand'];
	$sql= "SELECT brand FROM brand WHERE brand_id = '$brand_id'";
	$brand_query = $conn->query($sql);
	$brand = mysqli_fetch_assoc($brand_query);
	$color_array= $products['color']; 
	$colorstring= $products['color'];
	$colorstring = rtrim($colorstring,',');
	$color_array = explode(',', $colorstring);
	$color = $color_array[0];
// to get size from size table
	$sizeCount = 0;
	$sizeTable = $conn->query("SELECT * FROM products_size WHERE products_id = '$products_id'");
	$sizeCount = mysqli_num_rows($sizeTable);
	$sizeTab = $conn->query("SELECT * FROM products_size WHERE products_id = '$products_id' GROUP BY '$products_id'");
	$discountCount = mysqli_fetch_assoc($sizeTab);
?> 

<style>
#list_price, #discount_span{
	display: none;
}
</style>
<?php ob_start(); ?>
<!--Details model-->
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-modal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header H-M">
				<button class="close" type="button" onclick="closeModal()" aria-label="close">
					<span aria-hidden="true">&times;</span></button>
					<h2 class="modal-title text-center"><?= $products['title']?></h2>
			</div>
			<div class="modal-body"> 
				<div class="container-fluid">
					<div class="row">
						<span id="modal_errors" class="bg-danger"></span>
						<div class="col-sm-6">
							<div class="center-block">
								<img src="<?= $products['image']?>" alt="<?= $products['products_id']?>" class="details img-responsive">
							</div>
						</div>
						<div class="col-sm-6 details">
							<h4>Details</h4>
							<p><?= nl2br($products['description'])?></p>
							<hr>
							<!--there is size -->
							<?php if($sizeCount == 0):?>
								<?php if($products['discount'] != 0) : ?>
								<p>Price:&nbsp;<b><?=money($products['price']);?></b> &nbsp; &nbsp;
								<s><?=money($products['list_price']);?></s> &nbsp;<span>-<?=$products['discount'];?>&#37;</span>
								</p>
								<?php else :?>
								<p>Price:&nbsp;<b><?=money($products['price']);?></b></p>
								<?php endif; ?>
								<?php if($brand['brand'] != '') :?>
								<p>Brand: <?= $brand['brand']?></p>
								<?php endif; ?>
								<form action="add_cart.php" method="post" id="add_product_form">
								<input type="hidden" name="product_id" value="<?=$products_id;?>">								
								<?php
									if ($color != ''){
										echo'<hr>';
										echo'<p>Color</p>';
										echo'	<div class="form-check form-check-inline">';
										foreach ($color_array as $string) {
											$string_array = explode(',', $string);
											$color = $string_array[0];
									
											echo '<input class="form-check-input" type="radio" name="color" id="'.$color.'" value="'.$color.'">';
											echo '<label style="background-color:'.$color.';" class="form-check-label" for="'.$color.'"></label>';
										}
										echo'</div>';
										?>
										<script>
											$('input[name="color"]').first().prop('checked', true)
										</script>
										<?php
									}		
								?>
								<hr>
								<div class="form-group size">
									<div class="">
										<label for="quantity">Quantity:</label>
										<input type="number" class="form-control" id="quantity" name="quantity" min="0">
									</div>
									<br>
								<!-- To display if size is available-->
							<?php else : ?>
								<?php if($discountCount['price'] != 0) :?>
								<p id="discountCount">Price:&nbsp;<b id="price"></b> &nbsp; &nbsp;
									<s id="list_price"></s> &nbsp;<span id="discount_span" >-<i id="discount"></i>&#37;</span>
								</p>
								<?php else :?>
									<?php if($products['discount'] != 0) : ?>
									<p>Price:&nbsp;<b><?=money($products['price']);?></b> &nbsp; &nbsp;
									<s><?=money($products['list_price']);?></s> &nbsp;<span>-<?=$products['discount'];?>&#37;</span>
									</p>
									<?php else :?>
									<p>Price:&nbsp;<b><?=money($products['price']);?></b></p>
									<?php endif; ?>
								<?php endif;?>
								<?php if($brand['brand'] != '') :?>
								<p>Brand: <?= $brand['brand']?></p>
								<?php endif; ?>
								<form action="add_cart.php" method="post" id="add_product_form" name="form">
								<input type="hidden" name="product_id" value="<?=$products_id;?>">
								<?php
									if ($color != ''){
										echo'<hr>';
										echo'<p>Color</p>';
										echo'	<div class="form-check form-check-inline">';
										foreach ($color_array as $string) {
											$string_array = explode(',', $string);
											$color = $string_array[0];
									
											echo '<input class="form-check-input" type="radio" name="color" id="'.$color.'" value="'.$color.'">';
											echo '<label style="background-color:'.$color.';" class="form-check-label" for="'.$color.'"></label>';
										}
										echo'</div>';
										?>
										<script>
											$('input[name="color"]').first().prop('checked', true)
										</script>
										<?php
									}		
								?>
								<hr>
									<div class="form-group size">
									<div class="">
										<label for="quantity">Quantity:</label>
										<input type="number" class="form-control" id="quantity" name="quantity" min="0">
									</div>
									</br>
										<div class="">
											<label for="size">Size:</label>
											<select name="size" id="size" class="form-control">
												
												<?php
												$sizeTable = $conn->query("SELECT * FROM products_size WHERE products_id = '$products_id'");
												while($sizeT = mysqli_fetch_assoc($sizeTable)){
													echo '<option data-price="'.(($sizeT['price'] == 0)?'':money($sizeT['price'])).'" value="'.$sizeT['products_size_id'].'" data-list_price="'.(($sizeT['list_price'] == 0)?'':money($sizeT['list_price'])).'" data-discount="'.$sizeT['discount'].'">'.$sizeT['size'].'&nbsp;&nbsp;'.(($sizeT['price'] == 0)?'':money($sizeT['price'])).' (Available)</option>';
													?>
													<script>
													$(document).ready(function (e) {
											 			$("#size option:first").attr('selected','selected').trigger('change');
														 var price = $(this).find(':selected').attr('data-price');
														
																$('#price').html(price);
																
															var list_price = $(this).find(':selected').attr('data-list_price');
															$('#list_price').html(list_price);
															var discount = $(this).find(':selected').attr('data-discount');
															$('#discount').html(discount);
															 
															if (discount != 0) {
																$('#list_price').css('display', 'inline-block');
																$('#discount_span').css('display', 'inline-block');
															}else{
																$('#list_price').css('display', 'none');
																$('#discount_span').css('display', 'none');
															}
														 $('#size').change(function(){
															var price = $(this).find(':selected').attr('data-price');
														
																$('#price').html(price);
																
															var list_price = $(this).find(':selected').attr('data-list_price');
															$('#list_price').html(list_price);
															var discount = $(this).find(':selected').attr('data-discount');
															$('#discount').html(discount);
															 
															if (discount != 0) {
																$('#list_price').css('display', 'inline-block');
																$('#discount_span').css('display', 'inline-block');
															}else{
																$('#list_price').css('display', 'none');
																$('#discount_span').css('display', 'none');
															}
														});
													});
													</script>
													<?php
												}?>
											</select>
										</div>
									<?php endif; ?>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer F-M">
				<button class="btn btn-default" onclick="closeModal()">close</button>
				<button class="btn btn-default" onclick="add_to_cart();return false;"><i class="fa fa-cart-plus" aria-hidden="true"></i>&#160; Add to cart</button>
			</div>
		</div>	
	</div>
</div>
<script >
/*jQuery('#size').change(function(){
	var available = jQuery('#size option:selected').data("available");
	jQuery('#available').val(available);
});*/


	function closeModal(){
		jQuery('#details-modal').modal('hide');
		setTimeout(function(){
			jQuery('#details-modal').remove();
			jQuery('.modal-backdrop').remove();
		},500);
	}
	var backgroundModal = document.getElementById('details-modal');

			window.onclick=function(event){
				if(event.target==backgroundModal){

					jQuery('#details-modal').modal('hide');
					setTimeout(function(){
						jQuery('#details-modal').remove();
						jQuery('.modal-backdrop').remove();
					},500);
				}
			} 
</script>
<?php echo ob_get_clean(); ?>