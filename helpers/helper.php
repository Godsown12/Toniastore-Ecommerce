<?php
ob_start();
function display_errors($errors){
	$display = '<ul style="text-align:center; font-weight:bolder" class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
	foreach ($errors as $error) {
		$display .= '<li style="list-style:none;">'.$error.'</li>';
	}
	$display .='</ul>';
	return $display;
}
// display function on checkout page
function displayErrors($errors){
	$display ='';
	foreach ($errors as $error) {
		$display .='<p style="text-align:center; font-weight:bolder" class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$error.'</p>';
	}

	return $display;
}

function sanitize($dirty){
	return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}

function money($number){
	return '&#8358;'.number_format($number,2);
}
//session....
function login($user_id){
	$_SESSION['userId'] = $user_id; 
	global $conn;
	date_default_timezone_set('Africa/Lagos');
	$date = date("Y-m-d H:i:s");
	$conn->query("UPDATE users SET last_login = '$date' WHERE users_id = '$user_id'");
	$_SESSION['success_flash'] = 'Welcome you are now login';
	header("Location: index");
}


function is_logged_in(){
	if (isset($_SESSION['userId']) && $_SESSION['userId'] > 0) {
		return true;
	}
	else{
		return false;
	}
}

function login_error_redirect($url = 'login'){
	$_SESSION['error_flash'] = 'You must login first baby!.';
	header('Location: '.$url);
}

function login_error_cart_redirect($url = 'login'){
	$_SESSION['error_flash'] = 'Please loging to continue!.';
	header('Location: '.$url);
}

function permission_error_redirect($url = 'login'){
	$_SESSION['error_flash'] = 'You don\'t have permission to that page.';
	header('Location: '.$url);
}

function has_permission($permission = 'super_admin'){
	global $user_data;
	$permissions = explode(',', $user_data['permissions']); 
	if (in_array($permission, $permissions,true)) {
		return true;
	}
		return false;
}
function permission($permission = 'customer'){
	global $user_data;
	$permissions = explode(',', $user_data['permissions']); 
	if (in_array($permission, $permissions,true)) {
		return true;
	}
		return false;
}
// not to grant permission if the cart is empty
function cart_permission(){
	global $cart_data;

	if(!empty($cart_data)){
		return true;
	}
		return false;
}
function cart_permission_error_redirect($url = 'cart'){
	$_SESSION['errors_flash'] = 'your cart is empty.';
	header('location: '.$url);
}

function pretty_date($date){
	return date("M d, Y h:i A",strtotime($date));
}
ob_flush();
?>