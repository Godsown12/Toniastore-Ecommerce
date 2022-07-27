<nav class="navbar navbar-inverse ">
  <div class="container-fluid">
	    <div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span> 
		      </button>
		      <a class="navbar-brand" href="/toniastore/admin/index">ToniaStore Admin</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
		      <ul class="nav navbar-nav">
			        <li class="active"><a href="/toniastore/admin/index">My Dashboard</a></li>
			        <li><a href="brands">Brands</a></li>
			        <li><a href="categories">Categories</a></li> 
			        <li><a href="products">Products</a></li> 
			        <li><a href="archived">Archived</a></li>
			        <?php if(has_permission('admin')) : ?>
			        <li><a href="users"><span class="glyphicon glyphicon-user"></span> Users</a></li>
					<?php endif; ?>
					<li><a href="slider_image">Slider</a></li> 
					<li><a href="counter">Views</a></li>
					<li><a href="news">Subscribes News</a></li>
		    	</ul>
		      	<ul class="nav navbar-nav navbar-right">
			       	<li ><a href="#">Hello <?=$user_data['first'];?>!</a></li>
			        <li><a href="change_password">Change Password</a></li>
			        <li><a href="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
		      	</ul>
		</div>
  </div>
</nav>