<?php
	// css is in index.css
	include 'includes/header.php';


	if(!cart_permission()){
		cart_permission_error_redirect();
	}
	/*if (!is_logged_in()) {
			login_error_cart_redirect();
	}*/

	if($cart_data != ''){ // for cart items form cookies
		$sub_total = 0;
	 	$item_count = 0;	
	}

	// getting user varaible
	$user_id = '';
	$guest_id = '';
	//to get user email
	if(isset($_SESSION['userId']) && $_SESSION['userId'] != ''){
		$user_id = (int)$_SESSION['userId'];
	}
	$sqluser = $conn->query("SELECT * FROM users WHERE users_id = '$user_id'");
	$user = mysqli_fetch_assoc($sqluser);
	$userEmail= $user['email'];

	// varibles for guest form
	$guestEmail = ((isset($_POST['guestEmail']) && $_POST['guestEmail'] != '')?sanitize($_POST['guestEmail']): '' );
	$guestEmail = trim($guestEmail);

	// varaibles for chechout address form
	$errors = array();
	$emailcount='';
	$displayError='';
	$clicked = '';
	$fullName = ((isset($_POST['fullName']) && $_POST['fullName'] != '')?sanitize($_POST['fullName']):'');
	$fullName = trim($fullName);
	$fullName = ucwords($fullName);
	$email = ((isset($_POST['email']) && $_POST['email'] != '')?sanitize($_POST['email']):'');
	$email = trim($email);
	$street = ((isset($_POST['street']) && $_POST['street'] != '')?sanitize($_POST['street']):'');
	$street = trim($street);
	$street = ucwords($street);
	$city = ((isset($_POST['city']) && $_POST['city'] != '')?sanitize($_POST['city']):'');
	$city = trim($city);
	$city = ucwords($city);
	$state = ((isset($_POST['state']) && $_POST['state'] != '')?sanitize($_POST['state']):'');
	$state = trim($state);
	$state = ucwords($state);
	$phone = ((isset($_POST['number']) && $_POST['number'] != '')?sanitize($_POST['number']):'');
	$phone = trim($phone);

	
	if(isset($_SESSION['userId']) && $_SESSION['userId'] != ''){
		$user_id = (int)$_SESSION['userId'];
		//to fetch variables from database to the textboxs.......
		$clicked = 'yes';
		$sqlAddress = $conn->query("SELECT * FROM `address` WHERE `user_id` = '$user_id' || email = '$userEmail' && guest_id = '0'");
		$addressUser = mysqli_fetch_assoc($sqlAddress);
		$fullName = ((isset($_POST['fullName']) && $_POST['fullName'] != '')?sanitize($_POST['fullName']):$addressUser['full_name']);
		$fullName = trim($fullName);
		$fullName = ucwords($fullName);
		$email = ((isset($_POST['email']) && $_POST['email'] != '')?sanitize($_POST['email']):$addressUser['email']);
		$email = trim($email);
		$street = ((isset($_POST['street']) && $_POST['street'] != '')?sanitize($_POST['street']):$addressUser['street']);
		$street = trim($street);
		$street = ucwords($street);
		$city = ((isset($_POST['city']) && $_POST['city'] != '')?sanitize($_POST['city']):$addressUser['city']);
		$city = trim($city);
		$city = ucwords($city);
		$state = ((isset($_POST['state']) && $_POST['state'] != '')?sanitize($_POST['state']):$addressUser['state']);
		$state = trim($state);
		$state = ucwords($state);
		$phone = ((isset($_POST['number']) && $_POST['number'] != '')?sanitize($_POST['number']):$addressUser['phone_number']);
		$phone = trim($phone);
	}

	//vallidation on guest form
	if(isset($_POST['guestButton'])){
		if(empty($guestEmail)){
			$errors[] = 'Please guest enter your email address';
		}
		if($guestEmail != '' && !filter_var($guestEmail,FILTER_VALIDATE_EMAIL)){
			$errors[] = 'Please enter a vaild email address';
		}
		//print errors
		if(!empty($errors)){
			$displayError = displayErrors($errors);
		}else{
			// check if guest email already in database
			$sqlGuest =  $conn->query("SELECT * FROM guest WHERE guest_email = '$guestEmail'");
			$guestEmailCount = mysqli_num_rows($sqlGuest);
			$dbGuest = mysqli_fetch_assoc($sqlGuest);
			$dbGuest_id = $dbGuest['guest_id'];
			if($guestEmailCount > 0){
				$emailcount = 'yes';//  to open develiry form.....
				$sqlAddress = $conn->query("SELECT * FROM `address` WHERE guest_id = '$dbGuest_id'");
				$addressGuest = mysqli_fetch_assoc($sqlAddress);
				//to display the database value....
				$fullName = ((isset($_POST['fullName']) && $_POST['fullName'] != '')?sanitize($_POST['fullName']):$addressGuest['full_name']);
				$fullName = trim($fullName);
				$fullName = ucwords($fullName);
				$email = ((isset($_POST['email']) && $_POST['email'] != '')?sanitize($_POST['email']):$addressGuest['email']);
				$email = trim($email);
				$street = ((isset($_POST['street']) && $_POST['street'] != '')?sanitize($_POST['street']):$addressGuest['street']);
				$street = trim($street);
				$street = ucwords($street);
				$city = ((isset($_POST['city']) && $_POST['city'] != '')?sanitize($_POST['city']):$addressGuest['city']);
				$city = trim($city);
				$city = ucwords($city);
				$state = ((isset($_POST['state']) && $_POST['state'] != '')?sanitize($_POST['state']):$addressGuest['state']);
				$state = trim($state);
				$state = ucwords($state);
				$phone = ((isset($_POST['number']) && $_POST['number'] != '')?sanitize($_POST['number']):$addressGuest['phone_number']);
				$phone = trim($phone);

			}else{
				$sqlGuest ="INSERT INTO `guest`(`guest_email`) VALUES ('$guestEmail')";
				$conn->query($sqlGuest);
				$emailcount = 'yes';
			}	
		}
	}
	$sqlGuest =  $conn->query("SELECT * FROM guest WHERE guest_email = '$guestEmail'");
	
	
	// validation on delivery address form
	if(isset($_POST['deliveryButton'])){
		//to keep the form still open when you clicked contiune
		$clicked = 'yes';

		$required = array(
			'fullName' => 'Full Name',
			'email'    => 'Email',
			'street'   => 'Street',
			'city'     => 'City',
			'state'    => 'State',
			'number'   => 'Phone Number'
		);
		foreach ($required as $fields => $display) {
			if (empty($_POST[$fields]) || $_POST[$fields] == '') {
				$errors[] = $display.' is required please';
			}
		}
		if($email != '' && !filter_var($email,FILTER_VALIDATE_EMAIL)){
			$errors[] = 'Please enter a vaild email address';
		}
		if(empty($phone) && !preg_match('/^[0-9]{11}+$/',$phone)){
			$errors[] = 'Please enter a vaild phone number';
		}
		//print errors
		if(!empty($errors)){

			$displayError = displayErrors($errors);

		}else{
			// To register the address to the guest ID....
			//bring out guset_id.....
			$sqlG = $conn->query("SELECT * FROM guest WHERE guest_email = '$email'");
			$guest = mysqli_fetch_assoc($sqlG);
			$guest_id =  $guest['guest_id']; 

			if (!$user_id) {
				//to get guest address for update
				$sqlAddress = $conn->query("SELECT * FROM `address` WHERE guest_id = '$guest_id'");
				$addressGuest = mysqli_fetch_assoc($sqlAddress);
				$addressId = $addressGuest['address_id'];
				if(!$addressId){
					$addressSql = "INSERT INTO `address`(`full_name`, `email`, `street`, `city`, `state`, `phone_number`,`guest_id`) VALUES ('$fullName','$email','$street','$city','$state','$phone','$guest_id')";	
				}else{
					$addressSql = "UPDATE `address` SET `full_name`='$fullName',`email`='$email',`street`='$street',`city`='$city',`state`='$state',`phone_number`='$phone',`user_id`='0',`guest_id`='$guest_id' WHERE `address_id` = '$addressId'";
				}
				
			}else{
				//to get user address for update
				$sqlAddress = $conn->query("SELECT * FROM `address` WHERE `user_id` = '$user_id'");
				$addressGuest = mysqli_fetch_assoc($sqlAddress);
				$addressId = $addressGuest['address_id'];
				if(!$addressId){
					$addressSql = "INSERT INTO `address`(`full_name`, `email`, `street`, `city`, `state`, `phone_number`,`user_id`) VALUES ('$fullName','$email','$street','$city','$state','$phone','$user_id')";
				}else{
					$addressSql = "UPDATE `address` SET `full_name`='$fullName',`email`='$email',`street`='$street',`city`='$city',`state`='$state',`phone_number`='$phone',`user_id`='$user_id',`guest_id`='0' WHERE `address_id` = '$addressId'";
				}
				
			}
			$conn->query($addressSql);
			//to move to the next payment form
			$clicked = 'addresss';
		}
	}
?>
<div class="container-fluid" id = "container">
<div id="loader-background"><div id="loader" class="animate__animated animate__wobble animate__infinite	infinite center"><img src="img/logo.jpg" alt=""></div></div>
	<div class="row">
		<div class="secure">
			<h3>ToniaStore Secure checkout&#160;<i style="color:#010433;" class="fa fa-lock" aria-hidden="true"></i>&#160;<img class="paystack-img" src="img/paystack.jpg" alt=""><img src="https://www.merchantequip.com/image/?logos=v|m|g|wu&height=64" alt="Merchant Equipment Store Credit Card Logos"/> </h3>
		</div>
		<div class="col-md-6 process-Steps ">
			<div class="customer-user">
				<h4><span class="badge">1</span>&#160;Customer</h4>
				<br/>
				<!-- what to display when user is login-->
				<?php if(!$user_id) : ?>
					<div id="customer-user-form" class="customer-user-form">
						<p>Login if you are a User or continue as a Guest</p><br />
						<div><?=$displayError;?></div><br />
						<form method="post" action="checkout">
							<div class="row">
								<div class="col-md-7 guestEmail">
									<label for="guestEmail">Email Address</label>
									<input type="email" name="guestEmail" value="<?=$guestEmail;?>" class="form-control" autocomplete="off" required >
								</div>
								<div class="col-md-5 guestButton">
									<button type="submit" name="guestButton" class="btn btn-md btn-primary">continue As Guest</button>
								</div>
							</div>
						</form>
						<p>Already have an account?<a href="login">&#160;Sign in now</a></p>
					</div>
				<?php else : ?>	
					<div id="sucessful-billing">
						<p>Thank you <?=$user_data['first']?></p>
						<p>Have a sucessful chechout.</p>
					</div>
				<?php endif; ?>
			</div>
			<div class="delivery">
				<h4><span class="badge">2</span>&nbsp;Delivery Address</h4>
				<div id="delivery-form" class="delivery-form">
					<?php
					//if there is guest email bring out the form address 
						if($emailcount == 'yes' || $clicked == 'yes' ){
							?>
							<script type="text/javascript"> 
								$('#delivery-form').css("display","block");
								$('#customer-user-form').css("display","none");
							</script>
							<?php
						}
					?>
					<p>Add Delivery Address</p>
					<div><?=$displayError;?></div>
					<form method="post" action="checkout">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="fullName"><i class="fa fa-user"></i>&nbsp;Full Name</label>
									<input type="text" id="fullName" name="fullName" value="<?=$fullName;?>" placeholder="eg. Ajodo Godsown" autocomplete="off" class="form-control" required >

									<label for="email"><i class="fa fa-envelope"></i>&nbsp;Email</label>
									<input type="text" id="email" name="email" value="<?=((isset($_POST['guestButton']))?$guestEmail: $user['email']);?>" placeholder="eg. ajogodsown@gmail.com" autocomplete="off" class="form-control" readonly >

									<label for="street"><i class="fa fa-road"></i>&nbsp;Street</label>
									<input type="text" id="street" name="street" value="<?=$street;?>" placeholder="eg. No12, yola street" autocomplete="off" class="form-control" required >

									<label for="city"><i class="fa fa-institution"></i>&nbsp;City</label>
									<input type="text" id="city" name="city" value="<?=$city;?>" placeholder="eg. Sabon" autocomplete="off" class="form-control" required  >

									<label for="state"><i class="fa fa-institution"></i>&nbsp;State</label>
									<input type="text" id="state" name="state" value="<?=$state;?>" placeholder="eg. Kaduna" autocomplete="off" class="form-control" required >

									<label for="number"><i class="fa fa-phone"></i>&nbsp;Phone Number</label>
									<input type="text" id="number" name="number" value="<?=$phone;?>" placeholder="eg. 08136779046" autocomplete="off" class="form-control" required  >

									<button type="submit" name="deliveryButton" class="btn  btn-primary">continue</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="payment">
				<h4><span class="badge">3</span>&nbsp;Payment</h4>
				<div class="payment-form" id="payment-form">
					<?php
					// to display the form
						if($clicked == 'addresss'){
							?>
							<script type="text/javascript"> 
								$('#payment-form').css("display","block");
								$('#delivery-form').css("display","none");
								$('#customer-user-form').css("display","none");	
							</script>
							<?php
		
						}
					?>
					<div class="row">
						<p>Verify method of payment</p>
						<div class="col-sm-5 p">
							<?php
							//to get address of the user......
								if (isset($_SESSION['userId']) && $_SESSION['userId'] != '') {
									$user_id = (int)$_SESSION['userId'];
				
									//var_dump($user_id);
									$sqlAddressOrder = $conn->query("SELECT * FROM `address` WHERE `user_id` = '$user_id'");
								}else{
									global $guest_id;
									//var_dump($guest_id);
									$sqlAddressOrder = $conn->query("SELECT * FROM `address` WHERE guest_id = '$guest_id'");
								}
								$addressOrder = mysqli_fetch_assoc($sqlAddressOrder);
								$addressOrder_id = $addressOrder['address_id'];
								$addressOrder_email = $addressOrder['email'];
								$addressOrder_name = $addressOrder['full_name'];
							?>
							<button  onclick="save_order();">Pay on Delivery</button>
						</div>
						<div class="col-sm-5 p">
						<form action="#">
						<script type="text/javascript" src="https://js.paystack.co/v1/inline.js"></script>
							<button type="button" name="pay_now" id="pay-now" title="Pay now"  onclick="saveOrderThenPayWithPaystack()">Pay with Paystack</button> 
						</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- for order summary */ -->
		<div class="col-md-5 order-summary">
			<h4>Order Summary</h4>
			<div class="row">
				<div class="col-md-11" id="order-table">
					<table>
						<thead>
							<tr>
								<th>Products</th>
								<th>Quantity</th>
								<th class="right">Price</th>
							</tr>
						</thead>
						<tbody>
						<?php
							foreach ($cart_data as $items) {
								$product_id = $items['id'];
								$Size_id = $items['size'];
								$productQ = $conn->query("SELECT * FROM products WHERE products_id = '{$product_id}'"); 
								$product = mysqli_fetch_assoc($productQ);
								$sizeQ = $conn->query("SELECT * FROM `products_size` WHERE `products_size_id` = '{$Size_id}'");
								$size = mysqli_fetch_assoc($sizeQ);
						?>
								<tr>
									<td class="item-pics">											
										<img src="<?= $product['image']?>" alt="<?= $product['products_id']?>" class="img-responsive">
										<span class="left"><?=$product['title'];?></span>								
									</td>
									<td class="quantity"><span class="left"><?=$items['quantity'];?></span></td>
									<td class="right"><span class="left">
										<?php
											if($items['size'] != ''){
												echo (($size['price'] != 0 )?money($size['price']):money($product['price']));
											}else{
												echo money($product['price']);
											}
										?>
									</td>
								</tr>
						<?php
								$item_count += $items['quantity'];
								if($items['size'] != ''){
									$sub_total += (($size['price'] != 0)?$size['price'] * $items['quantity'] :$product['price'] * $items['quantity']);
								}else{
									$sub_total += $product['price'] * $items['quantity'];
								}
							}
								$state_name= array('Abuja','abuja','ABUJA');
									//$tax = snumber_format($tax,2); 
								$tax = TAXRATE + $sub_total;
								// constants delivery fee.
								$abuja = ABUJA;
								$outSide = OUTSIDE;
								$grand_total = $tax;
								if(isset($_POST['deliveryButton']) || isset($_SESSION['userId']) || isset($_POST['guestButton'])){
									if(!empty($state) && !in_array($state, $state_name)){
										$grand_total = $tax + $outSide;
									}else{
										$grand_total = $grand_total + $abuja;
									}
								}
								
						?>
								<tr class="none big-amount">
									<td class="none"></td>
									<td class="none"></td>
									<td class="none"></td>
								</tr>
								<tr class="big-amount">
									<td>Subtotal before Delivery</td>
									<td></td>
									<td class="right"><?=money($sub_total);?></td>
								</tr>
								<?php if (isset($_POST['deliveryButton']) || isset($_SESSION['userId']) || isset($_POST['guestButton']) && $state != '') : ?>
									<tr class="big-amount">
										<td>Delivery</td>
										<td></td>
										<td class="right"><?=money(((!in_array($state, $state_name))? $outSide: $abuja));?></td>
									</tr>
								<?php endif; ?>
								<tr class="big-amount">
									<td class="total">TOTAL</td>
									<td class="total"></td>
									<td class="right total"><?=money($grand_total);?></td>
								</tr>
						</tbody>
					</table>
					<div class="row">
						<div class="col-md-12 small-amount">
							<p>Subtotal before Delivery<span> <?=money($sub_total);?></span></p>
							<?php if (isset($_POST['deliveryButton']) || isset($_SESSION['userId']) || isset($_POST['guestButton']) && $state != '') : ?>
							<p>Delivery<span><?=money(((!in_array($state, $state_name))? $outSide: $abuja));?></span></p>
							<?php endif; ?>
							<p class="total">Total<span><?=money($grand_total);?></td></span></p>
							<input type="hidden" id="total" value="<?=$grand_total;?>">
							<input type="hidden" id="subtotal" value="<?=$sub_total;?>">
							<input type="hidden" id="delivery" value="<?=((!in_array($state, $state_name))?$outSide:$abuja);?>">
						</div>
					</div>
					<div class=" order-note">
						<h4>Delivery Fee</h4>
						<p>Delivery fee is base on your billing state.</p>
						<p>toniastore@support.com</p>
						<p>07031038456</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- TO GET ARRAY OF ALL IDS to use in our pay stack-->
<?php
$idArray = array();
$products = array();
$pr = '';
	foreach ($cart_data as $items) {	
		$idArray[] = $items['id'];
	}
	$ids = implode(',',$idArray);
	//var_dump($ids);
// to select the title products
$productSql = $conn->query("SELECT i.products_id as 'id', i.title as 'title'
FROM products i
WHERE i.products_id IN ({$ids});
");
while ($p = mysqli_fetch_assoc($productSql)) {  
	$products[]= $p['title'];  //var_dump($products);
}
$product_name = implode(', ',$products);
	//var_dump($product_name);
?>
<Script>
	var orderObj = {
		email_prepared_for_paystack: '<?=$addressOrder_email;?>',
		amount_prepared_for_paystack: <?=$grand_total;?> * 100,
		amount:<?=$grand_total;?>, 
		subtotal: <?=$sub_total;?>,
		addressid: <?=$addressOrder_id;?>,
		delivery: <?=((!in_array($state, $state_name))?$outSide:$abuja);?>,
		items_count: <?=$cart_count;?>,
		products_name: '<?=$product_name;?>',
		address_name: '<?=$addressOrder_name;?>',
		cartid: <?=$product_id;?>
		
		// other params you want to save
	};
	function save_order() {
		var posting = $.post('/toniastore/admin/parsers/save_order.php', orderObj );
		//document.getElementById("loader-background").style.display = "block";
		email(<?=$addressOrder_id;?>);
		thank_you(<?=$addressOrder_id;?>);
	};
	function saveOrderThenPayWithPaystack(){		
		// Send the data to save using post
		var posting = $.post( '/toniastore/admin/parsers/save_order_paystack.php', orderObj );

		posting.done(function( data ) {
		/* check result from the attempt */
		payWithPaystack(data);
		});
		posting.fail(function( data ) { /* and if it failed... */ });
	};

	function payWithPaystack(data){
		var handler = PaystackPop.setup({
			// This assumes you already created a constant named
			// PAYSTACK_PUBLIC_KEY with your public key from the
			// Paystack dashboard. You can as well just paste it
			// instead of creating the constant
			key: '<?=PAYSTACK_PUBLIC;?>',
			email: orderObj.email_prepared_for_paystack,
			amount: orderObj.amount_prepared_for_paystack,
			metadata: {
				cartid: orderObj.cartid,
				orderid: data.orderid,
				custom_fields: [
				{
					display_name: "Paid on",
					variable_name: "paid_on",
					value: 'Toniastore'
				},
				{
					display_name: "Paid via",
					variable_name: "paid_via",
					value: 'PayStack'
				},
				{
					display_name: "No Of Items",
					variable_name: "No of Items",
					value: orderObj.items_count
				},
				{
					display_name: "Items",
					variable_name: "items",
					value: orderObj.products_name
				},
				{
					display_name: "Depositor",
					variable_name: "depositor",
					value: orderObj.address_name
				}			
				]
			},
			callback: function(response){
				// post to server to verify transaction before giving value
				var orderObj = {
					amount: <?=$grand_total;?>,
					subtotal: <?=$sub_total;?>,
					addressid: <?=$addressOrder_id;?>,
					delivery: <?=((!in_array($state, $state_name))?$outSide:$abuja);?>,
					reference: response.reference
					// other params you want to save
				};
							
				var verifying = $.post( '/toniastore/admin/parsers/save_order_paystack.php', orderObj);
				verifying.done(function( data ) { // give value saved in data 
				});
				email(<?=$addressOrder_id;?>);
				thank_you(<?=$addressOrder_id;?>);
				//thankyou page
			},
			onClose: function(){
			  	jQuery('#paystackClose').modal('toggle');
			}
		});
		handler.openIframe();
	};
	
</Script>
<!-- Modal -->
<?php require 'includes/paystackClose.php';?>
<?php include 'includes/footer.php';?>
<!-- place below the html form -->
