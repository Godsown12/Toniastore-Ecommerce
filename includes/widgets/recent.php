<h3 class="text-center">Recent Items</h3>
<?php
    $orderQ = $conn->query("SELECT * FROM order_save WHERE paid = 1 ORDER BY order_id DESC LIMIT 10");
    $result = array();
    while($row = mysqli_fetch_assoc($orderQ)){
        $results[] = $row;
    }
    $row_count = $orderQ->num_rows;
    $used_ids = array();
    for ($i=0; $i < $row_count; $i++) { 
        $json_items = $results[$i]['cart_items'];
        $items = json_decode($json_items,true);
        foreach ($items as $item) {
            if(!in_array($item['id'], $used_ids)) {
                $used_ids[] = $item['id'];
            }
        }
    }
   $used_ids = array_splice($used_ids, 0, 10);
?>
<script>
  $(function() {
    $('.yourFlickgalWrap').flickGal({
      'infinitCarousel': true,
      'startIndex': 2
    });     
  });
</script>
<div class="yourFlickgalWrap" id="recent_widget">
    <div class="container">
        <div class="containerInner">
            <?php  foreach ($used_ids as $id) : 
            //  echo($id);
                $productQ = $conn->query("SELECT * FROM products WHERE products_id = '{$id}'");
                $product = mysqli_fetch_assoc($productQ);
                $sizeCount = 0;
                $sizeDiscount = 0;
                $sizeTable = $conn->query("SELECT DISTINCT  * FROM products_size WHERE products_id = '{$id}' GROUP BY products_id");
                $sizeCount = mysqli_num_rows($sizeTable);
                $size = mysqli_fetch_assoc($sizeTable);
                $sizePrice = $size['price'];
                $sizeDiscount = $size['discount'];
                $sizeList_price = $size['list_price'];
                ?> 
                <div onclick="detailsmodel(<?=$id;?>);" id="sea01" class="item">
                    <?php if($sizeCount == 0) :?>
                        <?php if($product['discount'] != 0) : ?>
                            <span>-<?=$product['discount'];?>%</span>
                        <?php else  :?>
                            <span class="spa"> &nbsp;</span>	
                        <?php endif; ?>	
                        <img class="img-responsive image-resize" src="<?=$product['image'];?>" alt="">
                        <p style="margin-bottom:20px;"><b><?=money($product['price']);?></b> &nbsp; &nbsp;<?=(($product['list_price'] != 0 )?'<s>'.money($product['list_price']).'</s>':'');?></p>
                        <h6><?=$product['title'];?></h6>
                    <?php else : ?>
                        <?php if($sizeDiscount != 0 || $product['discount'] != 0) : ?>
							<span><?=(($sizeDiscount == 0)?$product['discount']:$sizeDiscount);?>%</span>
						<?php else  :?>
							<span class="spa"> &nbsp;</span>	
						<?php endif; ?>			
                        <img class="img-responsive image-resize" src="<?=$product['image'];?>" alt="">
                        <p style="margin-bottom:20px;">
                            <?php 
                                if($sizePrice != 0.00){
                                  echo'<b>'.money($sizePrice).'</b>&nbsp;';
                                }else{
                                    echo'<b>'.money($product['price']).'</b>&nbsp;';
                                }
                                if($sizeDiscount != 0 || $product['discount'] != 0){
                                    if($sizeList_price == 0){
                                        echo'<s>'.money($product['list_price']).'</s>';
                                    }else{
                                        echo'<s>'.money($sizeList_price).'</s>';
                                    }
                                }else{
                                    echo'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                }
                            ?>
                        </p>
                        <h6><?=$product['title'];?></h6>
                    <?php endif;?><b><?=money($product['price']);?></b> &nbsp; &nbsp;<?=(($product['list_price'] != 0 )?'<s>'.money($product['list_price']).'</s>':'');?>
                </div>
             <?php endforeach; ?>
        </div>
    </div>    
    <div class="arrows">
        <a style="color:#000;" href="javascript:void(0);" class="prev"><i class="fa fa-angle-double-left"></i>&nbsp;</a>
        <a style="color:#000;" href="javascript:void(0);" class="next">&nbsp;<i class="fa fa-angle-double-right"></i></a>
    </div>
</div>    

