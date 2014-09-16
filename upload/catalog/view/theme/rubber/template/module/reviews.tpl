<div class="box">
  <?php if ($header) {?>
  <div class="box-heading"><?php echo $header; ?></div>
  <?}?>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($reviews as $review) { ?>
      <div style="margin:0px;width:100%;">
        <?php if ($review['product_id']) {?>
          <?php if ($review['prod_thumb']) { ?>
          <div class="image"><a href="<?php echo $review['prod_href']; ?>"><img src="<?php echo $review['prod_thumb']; ?>" alt="<?php echo $review['prod_name']; ?>" title="<?php echo $review['prod_name']; ?>"/></a></div>
          <?php } ?>
          <div class="name"><a href="<?php echo $review['prod_href']; ?>"><?php echo $review['prod_name']; ?></a></div>
          <?}?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($review['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
      </div>
      <span style="margin:0px;width:100%;"><?php echo $review['description']?> <a href="<?php echo $review['href']?>">...&raquo;</a></span>
      <div style="height:20px;margin-bottom:10px;margin-right:0px;font-style:italic;font-weight:bold;text-align:right;width:100%;"><?php echo $review['author']?></div>
      <?php } ?>
      <div style="height:40px;margin:0px;text-align:right;width:100%;"><a href="<?php echo $link_all_reviews; ?>" class="button"><span><?php echo $text_all_reviews;?></span></a></div>
    </div>
  </div>
</div>