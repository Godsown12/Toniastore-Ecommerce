<?php 
   global $cart_count; 
?>
<!--css is in index.css file-->
<div id="cart_update">
    <h5>Cart</h5>
    <a href="cart.php"><?=$cart_count;?></a>
</div>
<div class="clear"></div> 
<script>
    var count = <?=$cart_count;?>;
     if (count > 0) {
      document.getElementById("cart_update").style.cssText = "color: green; border: 5px dashed green"; 
     }
    
</script>
