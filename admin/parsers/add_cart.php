<?php 

	require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/core/init.php';
	$products_id = ((isset($_POST['product_id']) && $_POST['product_id'] != '')?sanitize($_POST['product_id']):'');
	$size =  ((isset($_POST['size']) && $_POST['size'] != '')?sanitize($_POST['size']):'');
	$quantity =  ((isset($_POST['quantity']) && $_POST['quantity'] != '')?sanitize($_POST['quantity']):'');
	$color =  ((isset($_POST['color']) && $_POST['color'] != '')?sanitize($_POST['color']):'');
	$item = array();
	$row = 0;
	
	$item[] = array(
		'id'          => $products_id,
		'size'        => $size,
		'quantity'    => $quantity,
		'color'       => $color,
		'row'         => $row,
	);

	if (empty($products_id)) {
		# code...
		header('location: ' . $_SERVER['HTTP_REFERER']);
		exit;
	}else {
		# code...
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
		$query = $conn->query("SELECT * FROM products where products_id = '{$products_id}'");
		$product = mysqli_fetch_assoc($query);
		$_SESSION['success_flash'] = $product['title'].' was added to your cart.';
		

		// check to see if the cart exits

		if($cart_data!= ''){
			$previous_items = $cart_data;
			$item_match = 0;
			$new_items = array();
			foreach ($previous_items as $pitem) {
				if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size'] && $item[0]['color'] == $pitem['color']){
					$pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
					/*if ($pitem['quantity'] > $available) {
						$pitem['quantity'] = $available;
					}*/
					
					$item_match = 1;
				}
				$new_items[] = $pitem;
				$row++;
			}
			if($item_match != 1){
				$items[] = array(
					'id'          => $products_id,
					'size'        => $size,
					'quantity'    => $quantity,
					'color'       => $color,
					'row'         => $row,
				);
				$new_items = array_merge($items,$previous_items);	  
			}
			$cart_data = $new_items;
			$item_data = json_encode($new_items);
			//setcookie(CART_COOKIE,'',1,"/",$domain,false);
			setcookie(CART_COOKIE,$item_data,CART_COOKIE_EXPIRE,'/',$domain,false);
		}
		else{
			// we add the cart to the data base and set cookies
			$cart_data = $item;
			$item_data = json_encode($cart_data);
			setcookie(CART_COOKIE,$item_data,CART_COOKIE_EXPIRE,'/',$domain,false);
		}
	}
	

?>