<?php 
require_once 'core/init.php';
require 'includes/include_logout.php'; 
?>
<!DOCTYPE HTML>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="x-ua-compatible" content="ie=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale = 1.0"/>
	<!-- Meta for Search Engine -->
	<meta name="description" content="Special Offers for best products at cheap wholesale price: Hairs, hairs extentions, hair products, women fashion and clothing...Come let us bulid your beauty together ">
	  <meta name="keywords" content="Hair,toniastore,tonia,Virgin silky straight,ombre,ombre wig,wig,deep curl,single straight,
	  Deepwave,bob,F427,Body wave,kinky curl,bone straight,Double Drawn,silky straight,spring curl,water curl,Funmi egg curl,Raw Donor,
	  store,T-shirt,jump suit,Crop-top,Gown,2pcs,Body suit,polo,Off-shoulder top,top,L'uodais,straightener,Hot brush,Hair Dryer,Hawaiian Silky">
	<meta name="author" content="Toniastore">
	<title>Tonia Store</title>
	<link rel="shortcut icon" type="image/x-icon" href="img/logo.jpg" media="all"/>
	<!--code to import jquery javascript-->
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="jquery-ui/jquery-ui.min.js"></script>
	<script src="js/header.js"></script>
	<script src="js/index.js"></script>
	<script src="js/responsiveslides.min.js"></script>
	<script src="js/login.js"></script>
	<script src="js/register.js"></script>
	<script src="js/checkout.js"></script>
	<script src="js/cart.js"></script>
	<script src="js/jquery.fadeCarousel.min.js"></script>
	<script src="js/checkingfadeCarousel.js"></script>
	<script src="js/jquery.slide.js"></script>
	<script src="js/jquery.easing.min.js"></script>
	<script src="js/demo.js"></script>
	<!--logo-brand -->
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery.rcbrand.js"></script>
	<script type="text/javascript" src="js/logo-brand.js"></script>
	<!--Camera JS with Required jQuery Easing Plugin-->
	<script src="js/easing.min.js" type="text/javascript"></script>
	<script src="js/camera.min.js" type="text/javascript"></script>
	<!-- Custom JS --->
	<script src="js/plugins.js"></script>
	<!--HTML5shiv Js-->
	<script src="js/modernizr-3.5.0.min.js"></script>
	<!--code to import bootsrap css file-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css ">
	<!--css files-->
	<link rel="stylesheet" type="text/css" href="css/index.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/header.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/footer.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/responsiveslides.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/products.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/login.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/register.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/contact.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/cart.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/checkout.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/table.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/about.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/video.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/demo.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/pagination.css" media="all">
	<!-- flick gal on top-promo-->
	<link rel="stylesheet" type="text/css" href="css/flickgal.css" media="all">
	<script type="text/javascript" src="js/jquery.flickgal.min.js"></script>
	<!-- Camera Slider CSS -->
	<link href="css/camera.css" rel="stylesheet" type="text/css"/>
	<!--code to import fontawesome css-->
	<link rel="stylesheet" type="text/css" href="fontawesome-free-5.5.0-web/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="fontawesome-free-5.5.0-web/css/v4-shims.min.css">
	<!--jquery-ui css-->
	<link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.structure.min.css">
	<link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.theme.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
	<!--code to import google font
    <link href="https://fonts.googleapis.com/css?family=KoHo" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="js/jquery.js">-->
</head>
<body>
	<header>	
		<div class="logo">
			<a href="index">ToniaStore</a>
		</div>
		<?php
			if (!isset($_SESSION['userId'])) {
				echo '
				<div id="welcome-name"><p> Sweet <i>'.$guest.'</i>!</p></div>
				<nav id="navId">
					<ul>
						<li><a href="contact"><i class="fa fa-address-book-o" aria-hidden="true"></i>&nbsp;Contact</a></li>
						<li><a href="cart"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i>&nbsp;My Cart&#160;<span class="badge" id="comparison-count">'.$cart_count.'</span></a></li>
						<li><a href="register"><i class="fa fa-user-circle-o" aria-hidden="true">&nbsp;</i>Register</a></li>
						<li><a href="login"><i class="fa fa-sign-in" aria-hidden="true">&nbsp;</i>login</a></li>
					</ul>
				</nav>	
					';
			}else{
				echo '
				<div id="welcome-name"><p> Sweet <i>'.$user_data['first'].'</i>!</p></div>
				<nav id="navId">
					<ul>
						<li><a href="contact"><i class="fa fa-address-book-o" aria-hidden="true"></i>&nbsp;Contact</a></li>
						<li><a href="cart"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;My Cart&#160;<i class="badge" id="comparison-count">'.$cart_count.'</i></a></li>
						<li><a href="index?logout=true"><i class="fa fa-sign-in" aria-hidden="true">&nbsp;</i>logout</a></li>
					</ul>
				</nav>	
				';
			}
		?>
		<div id="bar" class="toggle-menu"><i class="fa fa-bars"></i></div>
		<div class="clear"></div>
	</header>
<?php include 'includes/widgets/filters.php';?>	
<div class="header-bottom">
	<Ul>
		<li class="l1"><img class="fashion" src="img/cup.gif" alt=""></li>
		<li class="l2"><img src="img/fr.gif" alt=""></li>
		<li class="l3"><img class="img-log" src="img/logo.jpg" alt=""></li>
		<li class="l4"><img src="img/toniastore.gif" alt=""></li>
		<li class="l5"><img src="img/is.gif" alt=""></li>
	</Ul>
</div>
<div class="clear"></div>
<!-- script to reduce the nav bar when the login session is on-->
<script type="text/javascript">
	var welcomeName = document.getElementById("welcome-name");
	var bar = document.getElementById("bar");
	var container = document.getElementById("container");
	if (welcomeName) { 
		bar.style.top ="20px";

		document.getElementById("navId").style.top="130px";	
	}else{
		document.getElementById("navId").style.top="130px";	
	}
</script>


