<?php
include 'includes/header.php';
if (!is_logged_in()) {
	login_error_redirect();
}

if(isset($_GET['add'])){
	$add_id =(int)$_GET['add'];
	$add_id = sanitize($add_id);
}
//$add_id = ((isset($_POST['products_id']) && $_POST['products_id'] != '')?sanitize($_POST['products_id']):'');
	if($_POST){
		//$data = $_POST;
		//var_dump($data);
		$total_rows = count($_POST['size']);
		$products_id = array();
		$discount_size= array();
		for($i=0; $i < $total_rows; $i++){
			$products_id[] = $add_id; 
			if($_POST['size'] != ''){
				$inst="INSERT INTO `products_size`(`products_id`, `size`, `price`, `list_price`,`discount`) VALUES ('{$products_id[$i]}','{$_POST['size'][$i]}','{$_POST['sizePrice'][$i]}','{$_POST['discountPrice'][$i]}','{$_POST['discountSize'][$i]}')";
				$conn->query($inst);
			}

		}
		header("Location: products?edit=".$add_id);


	}
?>
<div class="container-fluid">
	<h2 class="text-center">Products Sizes</h2><hr><br>
	<div class="body">
		<div class="row">
			<div class="col">
				<form action="addsize?add=<?=$add_id;?>" method="post">
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
							<div class="form-group col-md-3">
							<label for="discount">discount:</label>
							<input type="number" name="discountSize[]" value="" class="form-control discountSize">
							</div>
							<div class="form-group col-md-3">
							<label for="list_price">Discount Price:</label>
							<input type="number"  name="discountPrice[]" value="" class="discount_priceSize form-control">
							</div>
						</div>
					</div>
					<div class="form-group pull-left adbtn">
						<button type="button" id="btn" class="btn btn-primary">Add</button>
					</div>
					<div class="form-group pull-right">
						<a href="products?edit=<?=$add_id;?>" class="btn btn-default">Cancel</a>
						<input type="submit" value="Submit" class="btn  btn-success">
						<div class="clear-fix"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
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
			$(apsection).append('<div class="tr"><hr><div class="form-group col-md-3"<label for="size">Size:</label><input type="text" id="size" name="size[]" value="" class="form-control"></div><div class="form-group col-md-3"><label for="price">Price:</label><input type="number" name="sizePrice[]" value="" class="form-control priceSize"></div> <div class="form-group col-md-2"><label for="discount">discount:</label><input type="number" name="discountSize[]" value="" class="form-control discountSize"></div><div class="form-group col-md-3"><label for="list_price">Discount Price:</label><input type="number"  name="discountPrice[]" value="" class="discount_priceSize form-control"></div><div class="col-md-1 removeclass"><i class="btn btn-md btn-danger rem" style="padding:3px; margin-top:25px;"><span class="glyphicon glyphicon-remove"></span></i>&#160;</div></div>');
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
		var tr = $(this).closest('.tr');
	 var discount=tr.find(".discountSize").val();
		var discountPrice = tr.find(".discount_priceSize").val();
		var Price = discountPrice - (discountPrice * discount / 100 );	
		tr.find(".priceSize").val(Price);


	});

	$("#apsection").on('change', '.discount_priceSize',function(){
		var tr = $(this).closest('.tr');
		
		var discount=tr.find(".discountSize").val();
		var discountPrice = tr.find(".discount_priceSize").val();
		var Price = discountPrice - (discountPrice * discount / 100 );	
		tr.find(".priceSize").val(Price);

	});
</script>