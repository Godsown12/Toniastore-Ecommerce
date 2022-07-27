<?php
    include 'includes/header.php';
    include 'includes/navigation.php';
    if (!is_logged_in()) {
		login_error_redirect();
	}

    //get page no
    if (isset($_GET['page_no']) && $_GET['page_no'] != '') {
        $page_no = $_GET['page_no'];
    }else{
        $page_no = 1;
    }
//set the total records per page
    $total_records_per_page = 15;
// set the offset and next page and previous page and adjecent
    $offset = ($page_no - 1)* $total_records_per_page;
    $next_page = $page_no + 1;
    $previous_page = $page_no - 1;
    $adjacents = "2";
//to get the number of records form the database....
    $sql_count = $conn->query("SELECT COUNT(*) AS total_records FROM order_save");
    $result_count = mysqli_fetch_assoc($sql_count);
    $total_records = $result_count['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total pages minus 1

    //geting the first table....
    $textQuery = "SELECT o.order_id, o.address_id, a.full_name, o.description, o.cart_items, o.order_date, o.grand_total, o.paid, o.shipped
    FROM order_save o
    LEFT JOIN `address` a ON o.address_id = a.address_id
    where o.shipped = 1
    ORDER BY o.order_date DESC
    LIMIT  $offset, $total_records_per_page";          
    $txtResult = $conn->Query($textQuery);
?>
<div class="container-fluid wrapper">
    <div class="row">
        <div class="col">
			<h3 class="text-center">Sales Made</h3>
			<table class="table table-condensed table-bordered table-stripe orange">
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
						<td><a href="orders?shipped=<?=$order['order_id'];?>" class="btn btn-sm btn-secondry">Details</a></td>
						<td><?=$order['full_name'];?></td>
						<td><?=$order['description'];?></td>
						<td><?=money($order['grand_total']);?></td>
                        <td><?=pretty_date($order['order_date']);?></td>
                       
					</tr>
				<?php endwhile;?>
				</tbody>
			</table>
        </div>
        <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
        <strong>Page <?=$page_no." of ".$total_no_of_pages; ?></strong>
        </div>
        <div>
        <ul class="pagination">
            <?php if($page_no > 1){
            echo "<li><a href='?page_no=1'>First Page</a></li>";
            } ?>
                
            <li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
            <a <?php if($page_no > 1){
            echo "href='?page_no=$previous_page'";
            } ?>>Previous</a>
            </li>
            <?php if ($total_no_of_pages <= 100){   
                        for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                            if ($counter == $page_no) {
                                echo "<li class='active'><a>$counter</a></li>"; 
                            }else{
                                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }
                        }
                    }   
            ?>
            <li <?php if($page_no >= $total_no_of_pages){
            echo "class='disabled'";
            } ?>>
            <a <?php if($page_no < $total_no_of_pages) {
            echo "href='?page_no=$next_page'";
            } ?>>Next</a>
            </li>
            
            <?php if($page_no < $total_no_of_pages){
            echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
            } ?>
            </ul>
        </div>
    </div>
</div>




<?php include 'includes/footer.php';?>