<?php
//to call all product from database
$sql = $conn->query("SELECT products_size.products_id AS 'products_id', products_size.price AS 'price',products_size.list_price AS 'list_price',products_size.discount AS 'discount',products.title AS 'title',products.image AS 'image'   
FROM products_size  
INNER JOIN products  
ON products_size.products_id = products.products_id  
WHERE products_size.discount != 0 
GROUP by products_size.products_id");
?>
<script>
  $(function() {
    $('.yourFlickgalWrap').flickGal({
      'infinitCarousel': true,
      'startIndex': 2
    });     
  });
</script>
<div class="yourFlickgalWrap">
    <div class="container">
        <div class="containerInner">
          <?php while ($discount = mysqli_fetch_assoc($sql)) : ?>
            <div  onclick="detailsmodel(<?=$discount['products_id'];?>);" id="sea01" class="item">
              <img class="img-responsive" src="<?=$discount['image'];?>" alt="">
              <p style="margin-bottom:20px;"><b><?=money($discount['price']);?></b> &nbsp; &nbsp;<s><?=money($discount['list_price']);?></s> &nbsp;<span>-<?=$discount['discount'];?>&#37;</span></p>
              <p><?=$discount['title'];?></p>
            </div>
          <?php endwhile; ?>  
        </div>
    </div>

    <div class="arrows">
    <a href="javascript:void(0);" class="prev"><i class="fas fa-hand-point-left"></i>&nbsp;</a>
    <a href="javascript:void(0);" class="next">&nbsp;<i class="fas fa-hand-point-right"></i></a>
    </div>
</div>