<?php
include 'includes/header.php';
if (!is_logged_in()) {
    login_error_redirect();
}

include 'includes/navigation.php';

//complete the order
if (isset($_GET['complete']) && $_GET['complete'] == 1) {
    $order_id = sanitize((int)$_GET['order_id']);
    $conn->query("UPDATE order_save SET shipped = 1, paid = 1 WHERE order_id = '{$order_id}'");
    $_SESSION['success_flash'] = "The Order Has Been Completed";
    header("Location: index");
}

//get order id
$or_id = ((isset($_GET['or_id']) && $_GET['or_id'] != '' )?sanitize($_GET['or_id']):'');
$or_id = (int)$or_id;
// get shipped order
$shipped =  ((isset($_GET['shipped']) && $_GET['shipped'] != '')?sanitize($_GET['shipped']):'');
$shipped = (int)$shipped;

// select from db

if(isset($_GET['shipped'])){
    $orQuery= $conn->query("SELECT * FROM order_save WHERE order_id = '{$shipped}' AND shipped = 1");
}else{
    $orQuery= $conn->query("SELECT * FROM order_save WHERE order_id = '{$or_id}'");
}

$or = mysqli_fetch_assoc($orQuery);
$items = json_decode($or['cart_items'], true);
$idArray = array();
$products = array();
foreach ($items as $item) {
    $idArray[] = $item['id'];
}
$ids = implode(',',$idArray);
$productQ = $conn->query("SELECT i.products_id as 'id', i.title as 'title', i.price as 'price', c.categories_id as 'categories_id', c.category as 'category'
FROM products i
LEFT JOIN categories c ON i.categories = c.categories_id
WHERE i.products_id IN ({$ids});
"); //var_dump($ids);
while ($p = mysqli_fetch_assoc($productQ)) {
    foreach($items as $item){
        if($item['id'] == $p['id']){
            $x = $item;
            continue;
        }
    }
    $products[]= array_merge($x,$p);// var_dump($products);
}
?>

<h2 class="text-center">Items Ordered</h2>
<div class="row">
    <div class="col-md-12">
    <table class="table table-condensed table-bordered table-stripe">
    <thead>
        <th>Quantity</th>
        <th>Title</th>
        <th>Category</th>
        <th>Size</th>
        <th>Color</th>
        <th>Price</th>
    </thead>
    <tbody>
        <?php foreach($products as $product) :?>
            <tr>
                <td><?=$product['quantity'];?></td>
                <td><?=$product['title'];?></td>
                <td><?=$product['category'];?></td>
                <?php 
                //get size
                $Size_id = $product['size'];
                $sizeQ = $conn->query("SELECT * FROM `products_size` WHERE `products_size_id` = '{$Size_id}'");
				$size = mysqli_fetch_assoc($sizeQ);
                ?>
                <td><?=(($product['size'] == '')?'N/A':$size['size']);?></td>
                <td><?=(($product['color'] == '')?'N/A':$product['color']);?></td>
                <td>
                    <?php 
                        if($product['size'] != ''){
                          echo (($size['price'] != 0)?money($size['price']):money($product['price']));
                        }else{
                          echo  money($product['price']); 
                        }
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <h2 class="text-center">Order Details</h2>
        <table class="table table-condensed table-bordered table-striped">
        <tbody>
            <tr>
                <td>Sub Total</td>
                <td><?=money($or['sub_total']);?></td>
            </tr>
            <tr>
                <td>Delivery Fee</td>
                <td><?=money($or['delivery']);?></td>
            </tr>
            <tr>
                <td>Tax</td>
                <td><?=money($or['tax']);?></td>
            </tr>
            <tr>
                <td>Grand Total</td>
                <td><?=money($or['grand_total']);?></td>
            </tr>
            <tr>
                <td>Paid</td>
                <?php if(isset($_GET['shipped'])): ?>
                <td><?=(($or['reference'] == '' )?'YES (On Delivery':'YES (Online)');?></td>
                <?php echo $or['reference']; ?>
                <?php else :?>
                <td><?=(($or['paid'] == 1 )?'YES (Oline)':'NO (On Delivery)');?></td>    
                <?php endif;?>
            </tr>
            <tr>
                <td>Reference</td>
                <td><?=(($or['reference'] == '')?'0':$or['reference']);?></td>
            </tr>
            <tr>
                <td>Order Date</td>
                <td><?=$or['order_date'];?></td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h3 class="text-center">Shipping Address</h3>
        <?php
        if(!$shipped){
            $add = $conn->query("SELECT o.order_id, o.address_id, a.full_name, a.email, a.street, a.city, a.state, a.phone_number
            FROM order_save o
            LEFT JOIN `address` a ON o.address_id = a.address_id
            WHERE o.order_id = $or_id;
            ");
        }
        else{
            $add = $conn->query("SELECT o.order_id, o.address_id, a.full_name, a.email, a.street, a.city, a.state, a.phone_number
            FROM order_save o
            LEFT JOIN `address` a ON o.address_id = a.address_id
            WHERE o.order_id = $shipped AND o.shipped = 1
            ");
        }
        
        $orderA = mysqli_fetch_assoc($add);
        ?>
        <address>
        <?=$orderA['full_name'];?><br>
        <?=$orderA['street'];?><br>
        <?=$orderA['city'];?><br>
        <?=$orderA['state'];?><br><br>
        <?=$orderA['email'];?><br>
        <?=$orderA['phone_number'];?><br>
        </address>
        <div class="pull-right">
            
           
            <?php if(!$shipped) : ?>
            <a href="index" class="btn btn-lg btn-default">Cancel</a>
            <a href="orders?complete=1&order_id=<?=$or_id;?>" class="btn btn-primary btn-lg">complete</a>
            <?php else :?>
             <a href="sales" class="btn btn-lg btn-default">Cancel</a>
            <?php endif; ?>
         </div>
    </div>
    
</div>
<?php
	include 'includes/footer.php';
?>