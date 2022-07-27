<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/toniastore/core/init.php';
	$mode = ((isset($_POST['mode']) && $_POST['mode'] != '')?sanitize($_POST['mode']):"");
	$edit_row_id = ((isset($_POST['row_id']) && $_POST['row_id'] != '')?sanitize($_POST['row_id']):"");
	$edit_row_id = (int)$edit_row_id;
	$edit_id = ((isset($_POST['cart_id']) && $_POST['cart_id'] != '')?sanitize($_POST['cart_id']):"");
	$edit_size = ((isset($_POST['cart_size']) && $_POST['cart_size'] != '')?sanitize($_POST['cart_size']):"");
	$edit_quantity = ((isset($_POST['quantity']) && $_POST['quantity'] != '')?sanitize($_POST['quantity']):"");
	$edit_color = ((isset($_POST['color']) && $_POST['color'] != '')?sanitize($_POST['color']):"");
	$updated_items = array();

	$domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
	// to delete

	//when delete is click to remove one item from cart cookie.
	if ($mode == 'deleted') {
		foreach ($cart_data as $keys => $values) {
			if ($cart_data[$keys]['row'] == $edit_row_id) {
				unset($cart_data[$keys]);
				$item_data = json_encode($cart_data);
				setcookie(CART_COOKIE,$item_data,CART_COOKIE_EXPIRE,'/',$domain,false);
				$_SESSION['success_flash'] = 'Your product have been deleted';
				 
			}
			if(empty($cart_data)){
				setcookie(CART_COOKIE,'',1,"/",$domain,false);
			}
			
		}
		
	}

	if ($mode == 'update_quantity') {
		foreach ($cart_data as $item) {
			if($item['row'] == $edit_row_id){ 
				$item['quantity'] = $edit_quantity;
			}

			$updated_items[] = $item;
		}
	}
	if(!empty($updated_items)){
		$json_updated = json_encode($updated_items);
		$item_data = $json_updated;
		setcookie(CART_COOKIE,$item_data,CART_COOKIE_EXPIRE,'/',$domain,false);
		$_SESSION['success_flash'] = 'Your quantity have been updated';
	}

		

	if ($mode == 'clearCart'){
		unset($_COOKIE[CART_COOKIE]);
		setcookie(CART_COOKIE,'',1,"/",$domain,false);
		$_SESSION['success_flash'] = 'All products have been deleted';
	}
	
?>