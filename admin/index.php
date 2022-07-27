<?php
	include 'includes/header.php';
	if (!is_logged_in()) {
		header("Location: login"); 
	}
	
	if (permission('customer')) {
		permission_error_redirect('../index');
	}
	include 'includes/navigation.php';
?>
<div class="container-fluid wrapper">
	<?=$flash;?>
	<div class="row">
		<!--<div class="col-md-2">
			<div id="slider">
				<a class="btn btn-sm btn-primary" href="slider_image.php">Slider Image </a>	
			</div>-->
			<a class="btn btn-sm btn-primary" style="margin-left:30px; background:red;" href="../index">ToniaStore </a>
			<a class="btn btn-sm btn-primary" href="sales" style="float:right;">All sales</a> 
		
		<!-- Orders to Ship -->
		<?php
			$textQuery = "SELECT o.order_id, o.address_id, a.full_name, o.description, o.cart_items, o.order_date, o.grand_total, o.paid, o.shipped
			 FROM order_save o
			 LEFT JOIN `address` a ON o.address_id = a.address_id
			 where o.shipped = 0
			 ORDER BY o.order_date DESC";
			 $txtResult = $conn->Query($textQuery);
		?>
		<div class="col-md-11">
			<h3 class="text-center">Orders To Ship</h3>
			<table class="table table-condensed table-bordered table-stripe">
				<thead>
					<th></th>
					<th>Name</th>
					<th>Descrpition</th>
					<th>Total</th>
					<th>Date</th>
				</thead>
				<tbody>
				<?php while($order = mysqli_fetch_assoc($txtResult)) : ?>
					<tr>
						<td><a href="orders?or_id=<?=$order['order_id'];?>" class="btn btn-sm btn-info">Details</a></td>
						<td><?=$order['full_name'];?></td>
						<td><?=$order['description'];?></td>
						<td><?=money($order['grand_total']);?></td>
						<td><?=pretty_date($order['order_date']);?></td>
					</tr>
				<?php endwhile;?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<!-- sales by month-->
			<?php
				$thisYr = date("Y");
				$lastYr = $thisYr - 1;
				$thisYrQuery = ($conn->query("SELECT grand_total, order_date FROM order_save WHERE YEAR(order_date) = '{$thisYr}' AND shipped = 1 "));
				$lastYrQuery = ($conn->query("SELECT grand_total, order_date FROM order_save WHERE YEAR(order_date) = '{$lastYr}' AND shipped = 1 "));
				$current = array();
				$last = array();
				$currentTotal = 0;
				$monthA = array();
				$lastTotal = 0;
				while($x = mysqli_fetch_assoc($thisYrQuery)){
					$month = date("n",strtotime($x['order_date']));
				
					if(!array_key_exists($month,$current)){
						$current[(int)$month] = $x['grand_total'];
					}else{
						
						$current[(int)$month] += $x['grand_total'];
					}
					$currentTotal += $x['grand_total'];
				}
				//for last 
				while($y = mysqli_fetch_assoc($lastYrQuery)){
					$month = date("n",strtotime($y['order_date']));
					if(!array_key_exists($month,$last)){
						$last[(int)$month] = $y['grand_total'];
					}else{
						//var_dump($last);
						$last[(int)$month] += $y['grand_total'];
					}
					$lastTotal += $y['grand_total'];
				}
			?>
				<div class="col-md-5">
					<h3 class="text-center">Sales By Month</h3>
					<table class="table table-condensed table-bordered table-striped yellow">
					<thead>
						<th></th>
						<th><?=$lastYr;?></th>
						<th><?=$thisYr;?></th> 
					</thead>
					<tbody>
					<?php for($i = 1; $i <= 12; $i++) : 
						$dt = DateTime::createFromFormat('!m',$i);	
					?>
						<tr <?=((date("m") == $i)?'class="info_index"':'');?>>
							<td><?=$dt->format("F");?></td>
							<td><?=((array_key_exists($i,$last))?money($last[$i]):money(0));?></td>
							<td><?=((array_key_exists($i,$current))?money($current[$i]):money(0));?></td>
						</tr>
					<?php endfor; ?>
						<tr>
							<td>Total</td>
							<td><b><?=money($lastTotal);?></b></td>
							<td><b><?=money($currentTotal);?></b></td>
						</tr>
					</tbody>
					</table>
				</div>
				<div class="col-md-3">
						
				</div>
		</div>
		</div>
	</div>

<?php
  include 'includes/footer.php';
?>