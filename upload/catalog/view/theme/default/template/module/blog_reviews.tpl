<div class="box">
  <?php if ($header) {?>
  <div class="box-heading"><?php echo $header; ?></div>
  <?php } ?>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($reviews as $review) { ?>
      <div style="margin:0 0 20px;width:100%;">
        <?php if ($review['article_id']) {?>
          <?php if ($review['articl_thumb']) { ?>
          <div class="image"><a href="<?php echo $review['articl_href']; ?>"><img src="<?php echo $review['articl_thumb']; ?>" alt="<?php echo $review['articl_name']; ?>" title="<?php echo $review['articl_name']; ?>"/></a></div>
          <?php } ?>
          <div class="name"><a href="<?php echo $review['articl_href']; ?>"><?php echo $review['articl_name']; ?></a></div>
          <?php } ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($review['article_id'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
      <div class="description"><?php echo $review['description']?> <a href="<?php echo $review['href']?>">...&raquo;</a></div>
      <div class="author" style="margin:10px 0 0;text-align:right;width:100%;"><strong><?php echo $review['author']?></strong></div>
	  </div>
      <?php } ?>
    </div>
  </div>
</div>
