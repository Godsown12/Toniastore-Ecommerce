<?php
include 'includes/header.php';
if (!is_logged_in()) {
		login_error_redirect();
	}
include 'includes/navigation.php';
//delete the product form the database
if (isset($_GET['delete']) || isset($_GET['delete']) != '') {
		$delete_id = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);
		$sqlD = "DELETE FROM products WHERE products_id = '$delete_id'";
		$conn->query($sqlD);
		header("Location: archived.php");
}
//To return the products to the products page
if(isset($_GET['return']) || isset($_GET['return']) != ''){
	$return_id =(int)$_GET['return'];
	$return_id = sanitize($return_id); 
	$sql2 =" UPDATE products SET deleted = 0 WHERE products_id = '$return_id'";
	$conn->query($sql2);
	header("Location: archived");
}
//to display products
$sql="SELECT * FROM products WHERE deleted = 1";
$result = $conn->query($sql);

?>
<div class="container-fluid">
	<h2 class="text-center">Archived Products</h2><hr>
	<div class="row">
		<table class="table table-condensed table-bordered table-striped ">
			<thead>
				<th></th><th>Product</th><th>Price</th><th>Category</th><th>Sold</th><th></th>
			</thead>
			<?php while($Products = mysqli_fetch_assoc($result)) : 
			$categories = $Products['categories'];
			$catsql="SELECT * FROM categories WHERE categories_id = '$categories'";
			$catresult= $conn->query($catsql);
			$cat = mysqli_fetch_assoc($catresult);
			?>
			<tbody>
				<td><a href="archived?return=<?=$Products['products_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a></td>
				<td><?=$Products['title'];?></td>
				<td><?=money($Products['price']);?></td>
				<td><?=$cat['category'];?></td>
				<td>0</td>
				<td><a href="archived?delete=<?=$Products['products_id'];?>" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
			</tbody>
			<?php endwhile;?>
		</table>
	</div>
</div>
<?php include 'includes/footer.php';?>