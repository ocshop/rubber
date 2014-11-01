<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>  
<div id="content" class="testimonials">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <?php if (!$single)	 { ?>
	<div id="filter">
		<span <?php if ($active == 'all') echo 'class="active"';?>><a href="<?php echo $showall_url;?>"><?php echo $text_rating_all; ?>(<?php echo $total;?>)</a></span>
		<span <?php if ($active == 'good') echo 'class="active"';?>><a href="<?php echo $good;?>"><?php echo $text_rating_good; ?>(<?php echo $total_good;?>)</a></span>
		<span <?php if ($active == 'bad') echo 'class="active"';?>><a href="<?php echo $bad;?>"><?php echo $text_rating_bad; ?>(<?php echo $total_bad;?>)</a></span>
		<div><a class="button" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><?php echo $write;?></a></div>
	</div>
	 
	 <?php } ?>
  <div class="middle">

    <?php if (true/*$testimonials*/) { ?>
    
      <?php foreach ($testimonials as $testimonial) { ?>
      <table class="content" width="100%" border=0>
      <tr>
         <td valign="top" style="text-align:left;" colspan="2"><b><?php echo $testimonial['title']; ?></b></td>
      </tr>
      <tr>
      	<td colspan="2" style="text-align:left;">
                <?php echo $testimonial['description']; ?>
            </td>
      </tr>    

     <tr>
		<td style="font-size: 0.9em; text-align: right;">
				<div class="rating">
				  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($testimonial['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
				  </div>
			<br>
-&nbsp;<i><?php echo $testimonial['name'].' '.$testimonial['city'].' '.$testimonial['date_added']; ?></i>
             </td>
        </td>
      </tr>

	</table>
      <?php } ?>

    	<?php if ( isset($pagination)) { ?>
    		<div class="pagination"><?php echo $pagination;?></div>
    		<div class="buttons" align="right"><a class="button" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><span><?php echo $write;?></span></a></div>
    	<?php }?>
    <?php } ?>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 