<div class="modulcarousel" id="carousel<?php echo $module; ?>">
  <ul class="jcarousel-skin-opencart">
    <?php foreach ($banners as $banner) { ?>
    <li><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></li>
    <?php } ?>
  </ul>
</div>
<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?> ul').jcarousel({
	vertical: false,
	visible: <?php echo $limit; ?>,
	scroll: <?php echo $scroll; ?>
});

$( document ).ready(function() {
$('#carousel<?php echo $module; ?> .jcarousel-prev').html('<i class="fa fa-chevron-circle-left fa-2x"></i>');
$('#carousel<?php echo $module; ?> .jcarousel-next').html('<i class="fa fa-chevron-circle-right fa-2x"></i>');
});
//--></script>