<?php 
$sql =$conn->query("SELECT * FROM `products` WHERE price <= 40000 AND categories = 1");
?>
<script>
  $(function() {
    $('.yourFlickgalWrap').flickGal({
      'infinitCarousel': true,
      'startIndex': 2
    });     
  });
</script>
<div class="yourFlickgalWrap" id="cheap">
    <div class="container">
        <div class="containerInner">
            <?php  while ($products = mysqli_fetch_assoc($sql)):  ?> 
                <?php
					$sizeCount = 0;
					$sizeDiscount = 0;
						$products_id = $products['products_id'];
						$sizeTable = $conn->query("SELECT DISTINCT  * FROM products_size WHERE products_id = '$products_id' GROUP BY products_id");
						$sizeCount = mysqli_num_rows($sizeTable);
						$size = mysqli_fetch_assoc($sizeTable);
						$sizePrice = $size['price'];
						$sizeDiscount = $size['discount'];
						$sizeList_price = $size['list_price'];
						
					?>
                <div  onclick="detailsmodel(<?=$products['products_id']?>);" id="sea01" class="item">
                    <?php if($sizeCount==0) :?>
                        <?php if($products['discount'] != 0) : ?>
                            <span>-<?=$products['discount'];?>%</span>
                        <?php else  :?>
                            <span class="spa"> &nbsp;</span>	
                        <?php endif; ?>	
                        <img class="img-responsive image-resize" src="<?=$products['image'];?>" alt="">
                        <p style="margin-bottom:20px;"><b><?=money($products['price']);?></b> &nbsp; &nbsp;<?=(($products['list_price'] != 0 AND $products['discount'] != 0 )?'<s>'.money($products['list_price']).'</s>':'');?></p>
                        <h6><?=$products['title'];?></h6>
                    <?php else :?>
                        <?php if($sizeDiscount != 0 || $products['discount'] != 0) : ?>
                            <span>-<?=(($sizeDiscount == 0)?$products['discount']:$sizeDiscount);?>%</span>
                        <?php else  :?>
                            <span class="spa"> &nbsp;</span>	
                        <?php endif; ?>	
                        <img class="img-responsive image-resize" src="<?=$products['image'];?>" alt="">
                        <?php if($products['list_price'] == 0 AND $sizeList_price == 0): ?>
                            <p style="margin-bottom:20px;"><b><?=(($sizePrice != 0.00)?money($sizePrice):money($products['price']));?></b> &nbsp; &nbsp;</p>
                        <?php else: ?>
                        <?php if($sizeDiscount != 0 || $products['discount'] != 0): ?>
                        <p style="margin-bottom:20px;"><b><?=(($sizePrice != 0.00)?money($sizePrice):money($products['price']));?></b> &nbsp; &nbsp;<?=(($sizeList_price == 0 )?'<s>'.money($products['list_price']).'</s>':'<s>'.money($sizeList_price).'</s>');?></p>
                        <?php else: ?>
                            <p style="margin-bottom:20px;"><b><?=(($sizePrice != 0.00)?money($sizePrice):money($products['price']));?></b></p>
                        <?php endif;?>
                        <?php endif; ?>
                        <h6><?=$products['title'];?></h6>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
        </div>
    </div>    
    <div class="arrows">
        <a style="color:#000;" href="javascript:void(0);" class="prev"><i class="fa fa-angle-double-left"></i>&nbsp;</a>
        <a style="color:#000;" href="javascript:void(0);" class="next">&nbsp;<i class="fa fa-angle-double-right"></i></a>
    </div>
</div>    
