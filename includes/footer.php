</div>
<footer >
	<div class="container">
		<div class="row footer-top">
			<div class="col-sm-3">
				<h4>Subscribe to our Newsletter</h4>
				<?php include 'includes/widgets/news-letter.php';?>
			</div>
			<div class="col-sm-2">
				<h4>About Us</h4>
				<ul>
					<li><a href="about">About Us</a></li>
					<li><a href="contact" title="Contact Us">Contact Us</a></li>
				</ul>
			</div>
			<div class="col-sm-2">
				<h4>Popular locations</h4>
				<ul>
					<li>Abuja</li>
					<li>Kaduna</li>
					<li>Lagos</li>
					<li>Warri</li>
					<li>Ebonyi</li>
				</ul>
			</div>
			<div class="col-sm-2">
				<h4>Phone Number</h4>
				<ul>
					<li>Call Us</li>
					<li class="phone"><i class="fas fa-phone-square">&#160;</i>+2347031038456</li>
				</ul>
			</div>
			<div class="col-sm-2 join-us">
				<h4>Stay connected</h4>
				<ul>
					<li><a href="https://web.facebook.com/tonia.egwu.7" target="_blank"><img src="img/face-b.png" alt=""></a></li>
					<li><a href="https://api.whatsapp.com/send?phone=2347031038456&text=&source=&data=&app_absent=" target="_blank"><img src="img/what-App.png" alt=""></a></li>
					<li><a href="https://www.instagram.com/toniastore_/?hl=en" target="_blank"><img src="img/ins.png" alt=""></a></li>
				</ul>
			</div>
			<div class="clear"></div>
			<div class="row">
				<div class="col-sm-4 secure">
				<img class="paystack-img" src="img/paystack.jpg" alt=""><img
                        src="https://www.merchantequip.com/image/?logos=v|m|g|wu&height=64" alt="Merchant Equipment Store Credit Card Logos"/> </h3>
				</div>
			</div>
		</div>
		<div class="row footer-bottom">
		<!--<div class="logo-img"><img src="img/logo.jpg" alt="">
		</div> -->
			<ul>
				<li>copyright &copy;<?php echo date("Y");?> ToniaStore.</li>
				<li><a href="articles-terms-of-use"><span>Terms of Use</span></a></li>
				<li><a href="articles-policy"><span>Privacy</span></a></li>
			</ul>
		</div>
	
	</div>
</footer>
<script type="text/javascript">
	
 	function detailsmodel(products_id){
 		var data ={"products_id" : products_id};
 		jQuery.ajax({
 			url: '/toniastore/includes/detailsmodal.php',
 			method: "post",
 			data: data,
 			success: function(data){ 
 				//we append the content of the modal to this page before the closing body
 				jQuery('body').append(data);
 				//we open our modal by using our #id
 				jQuery('#details-modal').modal('toggle');
 			},
 			error: function(){
 				alert('Something went wrong');
 			}

 		});
	};
	 
	function update_cart(mode,row_id,cart_id,cart_color,cart_size){
		 jQuery('#quantity_update_errors_'+row_id).html("");
		 var quantity = jQuery('#quantity_'+row_id).val();
		// alert(quantity);	
		//console.log($('#quantity_'+cart_id).val());
		var data = {"mode" : mode, "row_id": row_id, "cart_id" : cart_id, "cart_color" : cart_color, "cart_size" : cart_size, "quantity" : quantity};
		//alert(row_id);
		//alert(mode);
		var error= '';
		if(mode == "update_quantity"){
			//alert('dont go straight');to validate the update quantity textbox
			if(quantity == "" || quantity == 0){
				error +='<p style="text-align:center" class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please quantity must be at least one.</p>';
				jQuery('#quantity_update_errors_'+row_id).html(error);
				return;
			}else{
				jQuery.ajax({
					url: '/toniastore/admin/parsers/update_cart.php',
					method: 'post',
					data: data,
					success: function(){
						location.reload();
					},
					error: function(){
						alert('something went wrong');
					}
				});
			}
		}else{
			//alert('go straight'); to validate the update quantity textbox
			jQuery.ajax({
				url: '/toniastore/admin/parsers/update_cart.php',
				method: 'post',
				data: data,
				success: function(){
					location.reload();
				},
				error: function(){
					alert('something went wrong');
				}
			});
		}
		
		
	};
	 
 	function add_to_cart(){
 		jQuery('#modal_errors').html("");
 		var size = jQuery('#size').val();
 		var quantity = jQuery('#quantity').val(); 

 		//var available = jQuery('#available').val(); alert(available); 
 		var error = '';
 		var data = jQuery('#add_product_form').serialize();
 		if(quantity == '' || quantity == 0){
 			error += '<p style="text-align:center" class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please choose quantity.</p>';
 			jQuery('#modal_errors').html(error);
 			return;
 		}
 		if(size == ''){
 			error += '<p style="text-align:center" class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please choose size.</p>';
 			jQuery('#modal_errors').html(error);
 			return;
 		}else{
 			jQuery.ajax({
 				url : '/toniastore/admin/parsers/add_cart.php',
 				method : "post",
 				data : data,
 				success : function(){
 					location.reload();
 				},
 				error : function(){alert('Something went wrong');}
 			});
 		}
 	};
	function ref(data) {
		jQuery.ajax({
			url : '/toniastore/admin/parsers/save_order_paystack.php',
			method : "post",
			data : data,
			success : function(){
				
			},
			error : function(){alert('Something went wrong');}
		});   
	};
	//emailfunction 
	function email(addressId){
		var data = {"addressId" : addressId };
		document.getElementById("loader-background").style.display = "block";
		jQuery.ajax({
			url : '/toniastore/includes/email.php',
 			method: "post",
 			data : data,
 			success :function(){
				//location.reload();
 			},
 			error : function(){alert('Something went wrong');}
		});
	};
 	function thank_you(addressId){
 		var data = {"addressId" : addressId };
 		jQuery.ajax({
 			url : '/toniastore/includes/thankyoumodal.php',
 			method: "post",
 			data : data,
 			success :function(data){
 				//we append the content of the modal to this page before the closing body
 				jQuery('body').append(data);
 				//we open our modal by using our #id
 				jQuery('#thank-you').modal('toggle');
   				$("#thank-you").on("hidden.bs.modal", function () {
    			window.location = "index";
				});
 			},
 			error : function(){alert('Something went wrong');}
 		});
 	};
	
</script>
</body>
</html>