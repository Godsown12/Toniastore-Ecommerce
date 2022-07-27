<?php 
$Special_products= $conn->query($sqlp);
$sliderSql= $conn->query("SELECT * FROM slider_images");
$i = 1; 

?>
<html>
<head>

<link rel="stylesheet" type="text/css" href="css/slider.min.css">
<link rel="stylesheet" type="text/css" href="css/prism.css">
<style>
    .slider{
        display:none;
    }
</style>
</head>
<body>
    <ul class="slider" id="fullscreen-slider">
        <?php while($slider = mysqli_fetch_assoc($sliderSql)) : ?>
        <li><img src="<?=$slider['images'];?>" alt="slide".<?=$i;?> /></li>
        <?php $i++; ?>
        <?php endwhile; ?>
    </ul>
</body>
<script type="text/javascript" src="js/slider.min.js"></script>
<script type="text/javascript" src="js/prism.js"></script>
<script type="text/javascript">
    $(window).on("load", function() {
          $("#fullscreen-slider").slider({
            speed : 500,
            delay: 2500,
            responsive : true,
            paginationType : 'dots',
            navigation : false
          });   
    });   
</script>
</html>
